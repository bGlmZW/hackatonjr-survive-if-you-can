<?php
// ==========================================
// 1. BACKEND : LOGIQUE PHP
// ==========================================

use Location\Location;
use Transport\Transport;
use App\GPS;

// --- AUTOLOADER SPÉCIAL "FICHIERS À LA RACINE" ---
spl_autoload_register(function ($class) {
    // Ex: Si on cherche "Location\Location"
    // 1. On explose le nom pour avoir ["Location", "Location"]
    $parts = explode('\\', $class);
    // 2. On prend juste le dernier mot : "Location"
    $className = end($parts);
    
    // 3. On cherche "Location.php" juste à côté de l'index
    $file = __DIR__ . '/' . $className . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
// --------------------------------------------------

// CONFIGURATION
$nomsDesLieux = [
    "QG de la Résistance", "Hôpital Abandonné", "Armurerie Pillée", "Supermarché Vide",
    "Tour Radio", "Pont Effondré", "Gare Centrale", "Tunnel du Métro",
    "Camp Militaire", "Zone de Quarantaine", "Pharmacie", "Commissariat Brûlé",
    "Parc des Buttes", "Station Essence", "Entrepôt Amazon", "Bunker 404",
    "École en Ruine", "Stade de France", "Tour Eiffel (Sniper)", "Égouts"
];
$categories = ["Safe", "Danger", "Neutre", "Cache"];
$locations = [];

// GÉNÉRATION DE LA CARTE
mt_srand(42); 
foreach ($nomsDesLieux as $index => $nom) {
    $x = rand(-500, 500); 
    $y = rand(-500, 500);
    $cat = ($index === 0) ? "Safe" : $categories[array_rand($categories)];
    
    // C'est ici que ça plantait avant. Avec le nouvel autoloader, ça va marcher.
    $nouveauLieu = new Location($nom, $x, $y, $cat);
    
    if (count($locations) > 0) {
        $keys = array_keys($locations);
        $randomVoisinName = $keys[array_rand($keys)];
        $voisin = $locations[$randomVoisinName];
        
        // Choix du transport (Compatible Enum)
        $rand = rand(0, 2);
        if ($rand === 0) $mode = Transport::Car;
        elseif ($rand === 1) $mode = Transport::Train;
        else $mode = Transport::Foot;
        
        $nouveauLieu->addRoad($voisin, $mode);
        $voisin->addRoad($nouveauLieu, $mode);
        
        // Densité
        if (rand(0, 100) < 40) {
            $autreKey = $keys[array_rand($keys)];
            if ($autreKey !== $randomVoisinName) {
                $autreVoisin = $locations[$autreKey];
                $rand2 = rand(0, 2);
                $autreMode = match($rand2) { 0 => Transport::Car, 1 => Transport::Train, default => Transport::Foot };
                $nouveauLieu->addRoad($autreVoisin, $autreMode);
                $autreVoisin->addRoad($nouveauLieu, $autreMode);
            }
        }
    }
    $locations[$nom] = $nouveauLieu;
}

// LOGIQUE RECHERCHE
$searchPath = null;
$errorMsg = null;
$allNames = array_keys($locations);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startName = $_POST['depart'] ?? '';
    $endName = $_POST['arrivee'] ?? '';
    $stepName = $_POST['etape'] ?? ''; 

    if (isset($locations[$startName]) && isset($locations[$endName])) {
        $startNode = $locations[$startName];
        $endNode = $locations[$endName];

        if (!empty($stepName) && isset($locations[$stepName])) {
            $stepNode = $locations[$stepName];
            $part1 = GPS::findPath($startNode, $stepNode, $locations);
            $part2 = GPS::findPath($stepNode, $endNode, $locations);
            
            if ($part1 && $part2) $searchPath = array_merge($part1, $part2);
            else $errorMsg = "Impossible de relier ces zones via l'étape choisie.";
        } else {
            $searchPath = GPS::findPath($startNode, $endNode, $locations);
            if (!$searchPath) $errorMsg = "Aucun chemin sécurisé trouvé !";
        }
    } else {
        $errorMsg = "Lieu inconnu.";
    }
}

// MAPPING
$svgWidth = 800; $svgHeight = 600;
$minX = -500; $maxX = 500; $minY = -500; $maxY = 500;

function mapX($x) { global $svgWidth, $minX, $maxX; return (($x - $minX) / ($maxX - $minX)) * $svgWidth; }
function mapY($y) { global $svgHeight, $minY, $maxY; return (($y - $minY) / ($maxY - $minY)) * $svgHeight; }
function getColor($cat) {
    return match($cat) { 'Safe' => '#00ff00', 'Danger' => '#E50000', 'Cache' => '#ffaa00', default => '#aaaaaa' };
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Purge - Tactical Map</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body { background-color: #0d0d0d; color: #fff; font-family: 'Courier Prime', monospace; text-align: center; margin: 0; padding: 20px; }
        
        /* SVG & CARTE */
        svg { background-color: #1a1a1a; border: 2px solid #333; box-shadow: 0 0 20px rgba(0,0,0,0.8); margin-top: 20px; max-width: 100%; display:inline-block; }
        .road { stroke: #444; stroke-width: 2; }
        .path-highlight { stroke: #ffff00; stroke-width: 4; stroke-dasharray: 10,5; animation: dash 1s linear infinite; }
        .node-text { fill: white; font-size: 10px; font-weight: bold; text-shadow: 1px 1px 2px black; pointer-events: none; font-family: sans-serif; }
        circle { transition: r 0.3s; cursor: pointer; }
        circle:hover { r: 15; }
        @keyframes dash { to { stroke-dashoffset: -15; } }

        /* THERMAL MODE */
        .heat-blob { display: none; pointer-events: none; filter: blur(30px); mix-blend-mode: screen; transition: all 1s ease; }
        svg.thermal-mode .heat-blob { display: block; animation: pulseHeat 3s infinite alternate; }
        svg.thermal-mode .road { opacity: 0.1; }
        svg.thermal-mode .node-text { opacity: 0.5; }
        @keyframes pulseHeat { from { r: 50; opacity: 0.5; } to { r: 70; opacity: 0.8; } }

        /* UI */
        .mission-panel { background: #1a1a1a; border: 1px solid #444; max-width: 800px; margin: 0 auto 20px auto; padding: 20px; border-left: 5px solid #e91e63; box-shadow: 0 0 20px rgba(0,0,0,0.5); }
        .mission-panel h3 { margin-top: 0; color: #e91e63; font-family: 'Oswald', sans-serif; letter-spacing: 2px; }
        .search-form { display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end; justify-content: center; }
        .input-group { text-align: left; flex: 1; min-width: 200px; }
        .input-group label { display: block; font-size: 0.7em; color: #888; margin-bottom: 5px; font-weight: bold; font-family: 'Oswald', sans-serif;}
        .input-group input { width: 100%; background: #0d0d0d; border: 1px solid #333; color: white; padding: 10px; font-family: 'Courier Prime', monospace; font-weight: bold; }
        .input-group input:focus { outline: none; border-color: #e91e63; box-shadow: 0 0 10px rgba(233, 30, 99, 0.3); }
        
        .cybr-btn-small { background: #e91e63; color: white; border: none; padding: 12px 20px; font-weight: bold; cursor: pointer; text-transform: uppercase; font-family: 'Oswald', sans-serif; transition: 0.3s; }
        .cybr-btn-small:hover { background: #c2185b; box-shadow: 0 0 15px #e91e63; }
        .alert { padding: 15px; margin-top: 15px; font-weight: bold; text-align: left; font-size: 0.9em; }
        .alert.error { background: rgba(255, 0, 0, 0.2); color: #ff5252; border: 1px solid #ff5252; }
        .alert.success { background: rgba(0, 255, 0, 0.1); color: #69f0ae; border: 1px solid #69f0ae; }

        /* TOOLTIP & BUTTONS */
        .cyber-card { position: absolute; display: none; width: 250px; background: rgba(20, 20, 20, 0.95); border: 1px solid #333; backdrop-filter: blur(5px); border-radius: 4px; padding: 15px; color: white; box-shadow: 0 0 15px rgba(0, 255, 255, 0.2); pointer-events: none; z-index: 1000; border-left: 4px solid #00d2ff; text-align: left; }
        .card-header { display: flex; justify-content: space-between; border-bottom: 1px solid #333; padding-bottom: 8px; margin-bottom: 8px; }
        #card-title { font-family: 'Oswald', sans-serif; font-size: 1.1em; }
        .scan-container { margin: 30px; display: flex; justify-content: center; gap: 20px; }
        .cybr-btn { --primary: #e91e63; --shadow-primary: #880e4f; font-family: 'Oswald', sans-serif; text-transform: uppercase; background: transparent; color: white; font-size: 18px; outline: none; cursor: pointer; border: 0; padding: 15px 30px; position: relative; font-weight: 700; letter-spacing: 2px; text-shadow: 2px 2px var(--shadow-primary); }
        .cybr-btn:hover { --primary: #ff6090; }
        .cybr-btn:after, .cybr-btn:before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; clip-path: polygon(0 0, 100% 0, 100% 100%, 8% 100%, 0 66%); z-index: -1; }
        .cybr-btn:before { background: var(--shadow-primary); transform: translate(2px, 2px); }
        .cybr-btn:after { background: var(--primary); }
        .cybr-btn__glitch { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: var(--shadow-primary); clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%); display: none; z-index: 5; }
        .cybr-btn:hover .cybr-btn__glitch { display: block; animation: glitch 0.3s infinite; }

        /* PLAYER MARKER */
        #player-marker { fill: #00FFFF; stroke: #fff; stroke-width: 2; filter: drop-shadow(0 0 5px #00FFFF); pointer-events: none; transition: all 1s ease-out; }
        #player-pulse { fill: transparent; stroke: #00FFFF; stroke-width: 2; opacity: 0; pointer-events: none; }
        .ping-anim { animation: ping 2s infinite; }
        @keyframes ping { 0% { r: 5; opacity: 1; stroke-width: 3; } 100% { r: 50; opacity: 0; stroke-width: 0; } }
        @keyframes glitch { 0% { transform: translate(0) } 20% { transform: translate(-2px, 2px) } 40% { transform: translate(-2px, -2px) } 60% { transform: translate(2px, 2px) } 80% { transform: translate(2px, -2px) } 100% { transform: translate(0) } }
    </style>
</head>
<body>

    <h2 style="font-family: 'Oswald'; letter-spacing: 3px; color: #e91e63;">📡 TACTICAL MAP V1.0</h2>

    <div class="mission-panel">
        <h3>📍 PLANIFICATEUR D'ITINÉRAIRE</h3>
        
        <datalist id="lieux-list">
            <?php foreach($allNames as $name): ?><option value="<?= htmlspecialchars($name) ?>"><?php endforeach; ?>
        </datalist>

        <form method="POST" action="" class="search-form">
            <div class="input-group">
                <label>POINT D'EXTRACTION</label>
                <input type="text" name="depart" list="lieux-list" required value="<?= $_POST['depart'] ?? '' ?>">
            </div>
            <div class="input-group">
                <label>CIBLE À ATTEINDRE</label>
                <input type="text" name="arrivee" list="lieux-list" required value="<?= $_POST['arrivee'] ?? '' ?>">
            </div>
            <div class="input-group optional">
                <label>VIA (OPTIONNEL)</label>
                <input type="text" name="etape" list="lieux-list" value="<?= $_POST['etape'] ?? '' ?>">
            </div>
            <button type="submit" class="cybr-btn-small">CALCULER</button>
        </form>

        <?php if ($errorMsg): ?>
            <div class="alert error">⚠️ <?= $errorMsg ?></div>
        <?php endif; ?>

        <?php if ($searchPath): ?>
            <div class="alert success">
                ✅ ITINÉRAIRE CALCULÉ : <?= count($searchPath) ?> étapes <br>
                ⏱️ Durée estimée : <strong><?= round(array_sum(array_map(fn($s) => $s[1]->time, $searchPath)), 1) ?> min</strong><br><br>
                
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <?php foreach($searchPath as $step): 
                        $road = $step[1]; 
                        // Icônes
                        $icon = $road->transport->getIcon();
                        $nomTransport = $road->transport->getName();
                        $color = ($road->transport === Transport::Foot) ? '#aaa' : '#fff';
                    ?>
                        <div style="border-left: 2px solid #e91e63; padding-left: 10px; color: <?= $color ?>">
                            <?= $icon ?> <strong><?= $road->depart->name ?></strong> ➜ <strong><?= $road->destination->name ?></strong>
                            <br><small style="color:#888">Via <?= $nomTransport ?> (<?= round($road->time, 1) ?> min)</small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <svg width="<?= $svgWidth ?>" height="<?= $svgHeight ?>" xmlns="http://www.w3.org/2000/svg">
        
        <?php foreach ($locations as $loc): ?>
            <?php foreach ($loc->roads as $road): ?>
                <line x1="<?= mapX($loc->position[0]) ?>" y1="<?= mapY($loc->position[1]) ?>"
                    x2="<?= mapX($road->destination->position[0]) ?>" y2="<?= mapY($road->destination->position[1]) ?>"
                    class="road" />
            <?php endforeach; ?>
        <?php endforeach; ?>

        <?php if ($searchPath): ?>
            <?php foreach ($searchPath as $step): $road = $step[1]; ?>
                <line x1="<?= mapX($road->depart->position[0]) ?>" y1="<?= mapY($road->depart->position[1]) ?>"
                    x2="<?= mapX($road->destination->position[0]) ?>" y2="<?= mapY($road->destination->position[1]) ?>"
                    class="path-highlight" />
            <?php endforeach; ?>
        <?php endif; ?>

        <?php foreach ($locations as $loc): ?>
            <circle cx="<?= mapX($loc->position[0]) ?>" cy="<?= mapY($loc->position[1]) ?>" r="60" fill="<?= getColor($loc->category) ?>" class="heat-blob" opacity="0.6"></circle>
            <circle cx="<?= mapX($loc->position[0]) ?>" cy="<?= mapY($loc->position[1]) ?>" r="<?= ($loc->name === 'QG') ? 10 : 6 ?>" 
                    fill="<?= getColor($loc->category) ?>" stroke="white" stroke-width="1" class="node-point"
                    data-nom="<?= htmlspecialchars($loc->name) ?>" data-cat="<?= htmlspecialchars($loc->category) ?>"
                    data-desc="Zone de survie détectée."></circle>
            <text x="<?= mapX($loc->position[0]) + 10 ?>" y="<?= mapY($loc->position[1]) + 4 ?>" class="node-text"><?= $loc->name ?></text>
        <?php endforeach; ?>

        <circle id="player-pulse" cx="0" cy="0" r="10"></circle>
        <circle id="player-marker" cx="0" cy="0" r="8" style="display:none"></circle>
    </svg>

    <div class="scan-container">
        <button class="cybr-btn" onclick="toggleHeatmap()">
            VISION_THERMIQUE<span aria-hidden>_</span>
            <div class="cybr-btn__glitch">SATELLITE</div>
        </button>

        <button class="cybr-btn" onclick="locateMe()" style="--primary: #00FFFF; --shadow-primary: #008888;">
            OÙ_SUIS_JE ?<span aria-hidden>_</span>
            <div class="cybr-btn__glitch">GPS_UPLINK</div>
        </button>
    </div>

    <div id="info-card" class="cyber-card">
        <div class="card-header"><span id="card-title">NOM</span><span id="card-badge" class="badge">TYPE</span></div>
        <div class="card-body"><p id="card-desc">Info...</p></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. GESTION DES HOVERS
            const card = document.getElementById('info-card');
            const title = document.getElementById('card-title');
            const badge = document.getElementById('card-badge');
            const desc = document.getElementById('card-desc');
            const points = document.querySelectorAll('.node-point');

            points.forEach(point => {
                point.addEventListener('mouseenter', (e) => {
                    const nom = point.getAttribute('data-nom');
                    const cat = point.getAttribute('data-cat');
                    const info = point.getAttribute('data-desc');

                    title.textContent = nom;
                    badge.textContent = cat;
                    desc.textContent = info;

                    if(cat === 'Danger') { card.style.borderLeftColor = '#ff0000'; title.style.color = '#ff0000'; } 
                    else if (cat === 'Safe') { card.style.borderLeftColor = '#00ff00'; title.style.color = '#00ff00'; } 
                    else { card.style.borderLeftColor = '#00d2ff'; title.style.color = '#00d2ff'; }
                    
                    card.style.display = 'block';
                });
                point.addEventListener('mousemove', (e) => {
                    card.style.left = (e.pageX + 15) + 'px';
                    card.style.top = (e.pageY + 15) + 'px';
                });
                point.addEventListener('mouseleave', () => card.style.display = 'none');
                point.addEventListener('click', () => {
                    const departInput = document.querySelector('input[name="depart"]');
                    if(departInput) departInput.value = point.getAttribute('data-nom');
                });
            });
        });

        // 2. TOGGLE HEATMAP
        function toggleHeatmap() {
            const svg = document.querySelector('svg');
            if(svg) svg.classList.toggle('thermal-mode');
        }

        // 3. LOCALISATION JOUEUR
        function locateMe() {
            const marker = document.getElementById('player-marker');
            const pulse = document.getElementById('player-pulse');
            const btn = document.querySelector('button[onclick="locateMe()"]');
            
            if(!marker) return;

            if(btn) btn.childNodes[0].nodeValue = "RECHERCHE...";
            
            setTimeout(() => {
                const randomX = Math.floor(Math.random() * 1000) - 500;
                const randomY = Math.floor(Math.random() * 1000) - 500;
                const pixelX = ((randomX + 500) / 1000) * 800;
                const pixelY = ((randomY + 500) / 1000) * 600;

                marker.setAttribute('cx', pixelX); marker.setAttribute('cy', pixelY); marker.style.display = 'block';
                pulse.setAttribute('cx', pixelX); pulse.setAttribute('cy', pixelY); pulse.classList.add('ping-anim');

                alert(`SIGNAL DÉTECTÉ !\nCoordonnées : [${randomX}, ${randomY}]`);
                if(btn) btn.childNodes[0].nodeValue = "SIGNAL ACTIF";
            }, 800);
        }
    </script>
</body>
</html>