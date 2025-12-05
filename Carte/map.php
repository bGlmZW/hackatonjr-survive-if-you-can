<?php
require_once __DIR__ . '/Location.php';
require_once __DIR__ . '/Road.php';
require_once __DIR__ . '/Transport.php';

use Location\Location;
use Road\Road;
use Transport\Transport;

// ============= 1. CRÉATION DES LIEUX =============
$cyTech        = new Location("Cy Tech", 0, 0);
$rerA          = new Location("RER A", 1, 1);
$downtown      = new Location("Centre-ville", 3, 1);
$hospital      = new Location("Hopital", 4, 2);
$police        = new Location("Commissariat", 4, 0);
$mall          = new Location("Centre commercial", 5, 1);
$stadium       = new Location("Stade", 6, 0);
$cemetery      = new Location("Cimetiere", 7, 0);

$safeHouse     = new Location("Safe House", -1, 2);
$forestCamp    = new Location("Camp de la foret", -2, 3);
$riverBridge   = new Location("Pont de la riviere", -3, 3);
$farm          = new Location("Ferme abandonnee", -4, 2);
$gasStation    = new Location("Station essence", -2, 1);

$oldFactory    = new Location("Ancienne usine", 6, 3);
$powerPlant    = new Location("Centrale electrique", 8, 3);
$tunnel        = new Location("Entree du tunnel", 7, 2);

$abandonedSt   = new Location("Gare abandonnee", 2, 3);
$trainDepot    = new Location("Depot de trains", 4, 4);

$radioTower    = new Location("Tour radio", -1, 5);
$bunker        = new Location("Bunker souterrain", 0, 4);

// ============= 2. DÉFINITION DES ROUTES =============

// Cy Tech <-> RER A
$cyTech->addRoad(5,  $rerA,     Transport::Foot);
$cyTech->addRoad(2,  $rerA,     Transport::Train);
$rerA->addRoad(5,    $cyTech,   Transport::Foot);
$rerA->addRoad(2,    $cyTech,   Transport::Train);

// RER A <-> Centre-ville
$rerA->addRoad(10,   $downtown, Transport::Car);
$rerA->addRoad(5,    $downtown, Transport::Train);
$downtown->addRoad(10, $rerA,   Transport::Car);
$downtown->addRoad(5,  $rerA,   Transport::Train);

// Centre-ville <-> Hopital
$downtown->addRoad(6,  $hospital, Transport::Car);
$downtown->addRoad(15, $hospital, Transport::Foot);
$hospital->addRoad(6,  $downtown, Transport::Car);
$hospital->addRoad(15, $downtown, Transport::Foot);

// Centre-ville <-> Centre commercial
$downtown->addRoad(4,  $mall, Transport::Car);
$downtown->addRoad(12, $mall, Transport::Foot);
$mall->addRoad(4,      $downtown, Transport::Car);
$mall->addRoad(12,     $downtown, Transport::Foot);

// Hopital <-> Commissariat
$hospital->addRoad(3,  $police, Transport::Car);
$hospital->addRoad(8,  $police, Transport::Foot);
$police->addRoad(3,    $hospital, Transport::Car);
$police->addRoad(8,    $hospital, Transport::Foot);

// Centre-ville <-> Commissariat
$downtown->addRoad(3,  $police, Transport::Car);
$police->addRoad(3,    $downtown, Transport::Car);

// Commissariat <-> Stade
$police->addRoad(4,    $stadium, Transport::Car);
$stadium->addRoad(4,   $police,  Transport::Car);

// Stade <-> Cimetiere
$stadium->addRoad(7,   $cemetery, Transport::Foot);
$cemetery->addRoad(7,  $stadium,  Transport::Foot);

// Safe House <-> Camp de la forêt
$safeHouse->addRoad(6,    $forestCamp, Transport::Foot);
$forestCamp->addRoad(6,   $safeHouse,  Transport::Foot);

// Camp de la forêt <-> Pont de la rivière
$forestCamp->addRoad(4,   $riverBridge, Transport::Foot);
$riverBridge->addRoad(4,  $forestCamp,  Transport::Foot);

// Pont de la rivière <-> Ferme
$riverBridge->addRoad(7,  $farm,       Transport::Car);
$farm->addRoad(7,         $riverBridge, Transport::Car);

// Ferme <-> Station essence
$farm->addRoad(5,         $gasStation, Transport::Car);
$gasStation->addRoad(5,   $farm,       Transport::Car);

// Station essence <-> Centre-ville
$gasStation->addRoad(9,   $downtown, Transport::Car);
$downtown->addRoad(9,     $gasStation, Transport::Car);

// Centre commercial <-> Ancienne usine
$mall->addRoad(5,         $oldFactory, Transport::Car);
$oldFactory->addRoad(5,   $mall,       Transport::Car);

// Ancienne usine <-> Centrale électrique
$oldFactory->addRoad(6,   $powerPlant, Transport::Car);
$powerPlant->addRoad(6,   $oldFactory, Transport::Car);

// Centrale électrique <-> Tunnel
$powerPlant->addRoad(5,   $tunnel,     Transport::Car);
$tunnel->addRoad(5,       $powerPlant, Transport::Car);

// Tunnel <-> Centre-ville
$tunnel->addRoad(8,       $downtown,   Transport::Car);
$downtown->addRoad(8,     $tunnel,     Transport::Car);

// Gare abandonnée <-> Dépôt de trains
$abandonedSt->addRoad(3,  $trainDepot, Transport::Train);
$trainDepot->addRoad(3,   $abandonedSt, Transport::Train);

// Dépôt de trains <-> Centre-ville
$trainDepot->addRoad(4,   $downtown,   Transport::Train);
$downtown->addRoad(4,     $trainDepot, Transport::Train);

// Gare abandonnée <-> Cimetiere (à pied)
$abandonedSt->addRoad(10, $cemetery,   Transport::Foot);
$cemetery->addRoad(10,    $abandonedSt, Transport::Foot);

// Tour radio <-> Bunker
$radioTower->addRoad(8,   $bunker,     Transport::Foot);
$bunker->addRoad(8,       $radioTower, Transport::Foot);

// Bunker <-> Safe House
$bunker->addRoad(5,       $safeHouse,  Transport::Foot);
$safeHouse->addRoad(5,    $bunker,     Transport::Foot);

// Bunker <-> Centre-ville
$bunker->addRoad(20,      $downtown,   Transport::Foot);
$downtown->addRoad(20,    $bunker,     Transport::Foot);

$locations = [
    $cyTech,
    $rerA,
    $downtown,
    $hospital,
    $police,
    $mall,
    $stadium,
    $cemetery,
    $safeHouse,
    $forestCamp,
    $riverBridge,
    $farm,
    $gasStation,
    $oldFactory,
    $powerPlant,
    $tunnel,
    $abandonedSt,
    $trainDepot,
    $radioTower,
    $bunker,
];

// ============= 3. FONCTIONS DIJKSTRA =============

function shortestpath(array $locations, Location $start, Location $end, $mode): ?array
{
    // Indexation des lieux par leur nom
    $locByname = [];
    foreach ($locations as $loc) {
        $locByname[$loc->name] = $loc;
    }

    $startName = $start->name;
    $endName   = $end->name;

    if (!isset($locByname[$startName]) || !isset($locByname[$endName])) {
        return null;
    }

    $dist      = [];
    $previous  = [];
    $unvisited = [];

    foreach ($locByname as $name => $loc) {
        $dist[$name]      = INF;
        $previous[$name]  = null;
        $unvisited[$name] = true;
    }

    $dist[$startName] = 0.0;

    while (!empty($unvisited)) {
        $currentName = null;
        $currentDist = INF;

        foreach ($unvisited as $name => $_) {
            if ($dist[$name] < $currentDist) {
                $currentDist = $dist[$name];
                $currentName = $name;
            }
        }

        if ($currentName === null || $currentDist === INF) {
            break;
        }

        if ($currentName === $endName) {
            break;
        }

        unset($unvisited[$currentName]);

        $locBynameLocal = $locByname;
        $currentLoc = $locBynameLocal[$currentName];

        foreach ($currentLoc->roads as $road) {
            if ($road->transport !== $mode) {
                continue;
            }

            $neighbor     = $road->destination;
            $neighborName = $neighbor->name;

            if (!array_key_exists($neighborName, $dist)) {
                continue;
            }

            $alt = $dist[$currentName] + $road->time;

            if ($alt < $dist[$neighborName]) {
                $dist[$neighborName]     = $alt;
                $previous[$neighborName] = $currentName;
            }
        }
    }

    if ($dist[$endName] === INF) {
        return null;
    }

    $pathNames = [];
    $current   = $endName;

    while ($current !== null) {
        array_unshift($pathNames, $current);
        $current = $previous[$current] ?? null;
    }

    $pathLocations = [];
    foreach ($pathNames as $name) {
        $pathLocations[] = $locByname[$name];
    }

    return [
        'distance' => $dist[$endName],
        'path'     => $pathLocations,
        'names'    => $pathNames,
    ];
}

function shortestpath_with_stops(
    array $locations,
    Location $start,
    array $stops,
    Location $end,
    $mode
): ?array {
    $allLocations = [];
    $allNames     = [];
    $totalDist    = 0.0;

    $currentStart = $start;
    $targets      = $stops;
    $targets[]    = $end;

    foreach ($targets as $index => $target) {
        $segment = shortestpath($locations, $currentStart, $target, $mode);
        if ($segment === null) {
            return null;
        }

        $segmentLocations = $segment['path'];
        $segmentNames     = $segment['names'];

        if ($index > 0) {
            array_shift($segmentLocations);
            array_shift($segmentNames);
        }

        $allLocations = array_merge($allLocations, $segmentLocations);
        $allNames     = array_merge($allNames, $segmentNames);
        $totalDist   += $segment['distance'];

        $currentStart = $target;
    }

    return [
        'distance' => $totalDist,
        'path'     => $allLocations,
        'names'    => $allNames,
    ];
}

// ============= 4. BOUNDS BOX & COORDS =============
$minX = $maxX = $locations[0]->position[0];
$minY = $maxY = $locations[0]->position[1];

foreach ($locations as $loc) {
    $x = $loc->position[0];
    $y = $loc->position[1];

    if ($x < $minX) $minX = $x;
    if ($x > $maxX) $maxX = $x;
    if ($y < $minY) $minY = $y;
    if ($y > $maxY) $maxY = $y;
}

$rangeX = max(1, $maxX - $minX);
$rangeY = max(1, $maxY - $minY);

$locCoords    = [];
$locByName    = [];
$coordsByName = [];
foreach ($locations as $loc) {
    $x = $loc->position[0];
    $y = $loc->position[1];

    $nx = ($x - $minX) / $rangeX;
    $ny = ($y - $minY) / $rangeY;

    $left   = $nx * 100;
    $bottom = $ny * 100;

    $hash = spl_object_hash($loc);
    $locCoords[$hash] = [
        'left'   => $left,
        'bottom' => $bottom,
    ];

    $locByName[$loc->name]    = $loc;
    $coordsByName[$loc->name] = [
        'left'   => $left,
        'bottom' => $bottom,
    ];
}

// ============= 5. FORMULAIRE & CHEMIN CALCULÉ =============
$computedPath    = null;
$errorMessage    = null;
$activePathNames = [];
$pathEdges       = [];

$selectedStart = $_GET['start'] ?? '';
$selectedVia   = $_GET['via']   ?? '';
$selectedEnd   = $_GET['end']   ?? '';
$selectedMode  = $_GET['mode']  ?? '';

if (isset($_GET['start'], $_GET['end'], $_GET['mode'])) {

    $startName = $_GET['start'];
    $endName   = $_GET['end'];
    $viaName   = $_GET['via'] ?? '';

    if (!isset($locByName[$startName]) || !isset($locByName[$endName])) {
        $errorMessage = "Lieu de départ ou d'arrivée invalide.";
    } else {
        $startLoc = $locByName[$startName];
        $endLoc   = $locByName[$endName];

        $modeValue = null;
        switch ($_GET['mode']) {
            case 'foot':
                $modeValue = Transport::Foot;
                break;
            case 'car':
                $modeValue = Transport::Car;
                break;
            case 'train':
                $modeValue = Transport::Train;
                break;
        }

        if ($modeValue === null) {
            $errorMessage = "Mode de transport invalide.";
        } else {
            $stops = [];
            if ($viaName !== '' && isset($locByName[$viaName])) {
                $stops[] = $locByName[$viaName];
            }

            if (!empty($stops)) {
                $computedPath = shortestpath_with_stops($locations, $startLoc, $stops, $endLoc, $modeValue);
            } else {
                $computedPath = shortestpath($locations, $startLoc, $endLoc, $modeValue);
            }

            if ($computedPath === null) {
                $errorMessage = "Aucun chemin trouvé pour ces paramètres.";
            } else {
                $activePathNames = $computedPath['names'];

                // Construction des arêtes du chemin : (n_i, n_{i+1})
                $names = $computedPath['names'];
                for ($i = 0; $i < count($names) - 1; $i++) {
                    $a   = $names[$i];
                    $b   = $names[$i + 1];
                    $key = ($a < $b) ? "$a|$b" : "$b|$a";
                    $pathEdges[$key] = [$a, $b];
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Carte de survie</title>
    <style>
        :root {
            --color-dark: #0A0A0A;
            --color-accent: #E50000;
            --color-text: #F0F0F0;
            --font-primary: 'Oswald', sans-serif;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--color-dark);
            color: var(--color-text);
            font-family: Arial, Helvetica, sans-serif;
        }

        a {
            color: var(--color-accent);
            text-decoration: none;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
            border-bottom: 2px solid var(--color-accent);
        }

        .logo {
            font-family: var(--font-primary);
            font-size: 2em;
            font-weight: bold;
            color: var(--color-text);
            letter-spacing: 5px;
        }

        .hero {
            min-height: 80vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            text-align: center;
            gap: 30px;
        }

        .hero h1 {
            font-family: var(--font-primary);
            font-size: 3em;
            letter-spacing: 6px;
            margin-bottom: 10px;
        }

        .hero p {
            color: #888;
            max-width: 700px;
            margin-bottom: 10px;
        }

        /* FORMULAIRE */
        .path-form {
            background-color: #111;
            border: 1px solid #333;
            padding: 20px 25px;
            border-radius: 8px;
            max-width: 700px;
            width: 100%;
            text-align: left;
        }

        .path-form h2 {
            font-family: var(--font-primary);
            margin-bottom: 15px;
            font-size: 1.6em;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group label {
            font-size: 0.9em;
            text-transform: uppercase;
            color: #bbb;
        }

        .form-group select {
            background-color: #1A1A1A;
            border: 1px solid #333;
            border-radius: 3px;
            padding: 8px 10px;
            color: #fff;
        }

        .btn-primary {
            display: inline-block;
            margin-top: 15px;
            background-color: var(--color-accent);
            color: var(--color-dark);
            padding: 10px 24px;
            text-transform: uppercase;
            font-weight: bold;
            font-family: var(--font-primary);
            border: none;
            cursor: pointer;
            box-shadow: 0 0 10px var(--color-accent);
        }

        .btn-primary:hover {
            background-color: #FF6666;
            box-shadow: 0 0 20px #FF6666;
        }

        .path-error {
            margin-top: 10px;
            color: #ff7777;
            font-size: 0.9em;
        }

        .path-result {
            margin-top: 20px;
            text-align: left;
        }

        .path-result h3 {
            font-family: var(--font-primary);
            margin-bottom: 5px;
        }

        .path-result ol {
            margin: 8px 0 0 18px;
            padding: 0;
        }

        /* CARTE */
        .map-wrapper {
            width: 90vw;
            max-width: 900px;
            aspect-ratio: 16 / 9;
            border: 2px solid var(--color-accent);
            border-radius: 8px;
            box-shadow: 0 0 25px rgba(229, 0, 0, 0.4);
            background: radial-gradient(circle at top, #202020 0, #050505 60%);
            position: relative;
            overflow: hidden;
        }

        .map-grid {
            position: relative;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(#222 1px, transparent 1px),
                linear-gradient(90deg, #222 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .map-roads {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .map-roads line {
            stroke-width: 0.6;
            opacity: 0.8;
        }

        .road-foot {
            stroke: #cccccc;
            stroke-dasharray: 2 2;
        }

        .road-car {
            stroke: #E50000;
        }

        .road-train {
            stroke: #00FFC0;
        }

        .road-on-path {
            stroke: #ffffff;
            stroke-width: 1.2;
            opacity: 1;
        }

        .map-point {
            position: absolute;
            transform: translate(-50%, 50%);
            text-align: center;
            font-size: 0.7rem;
            max-width: 100px;
        }

        .point-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 2px solid var(--color-accent);
            background-color: var(--color-dark);
            box-shadow: 0 0 10px rgba(229, 0, 0, 0.7);
            margin: 0 auto 4px auto;
        }

        .map-point.safe .point-dot {
            background-color: #00FFC0;
            border-color: #00FFC0;
            box-shadow: 0 0 10px rgba(0, 255, 192, 0.7);
        }

        .map-point.on-path .point-dot {
            width: 14px;
            height: 14px;
            box-shadow: 0 0 15px #ffffff;
            border-color: #ffffff;
        }

        .point-label {
            background: rgba(0, 0, 0, 0.7);
            padding: 4px 6px;
            border-radius: 4px;
            border: 1px solid #333;
            display: inline-block;
            white-space: nowrap;
        }

        .map-point.on-path .point-label {
            border-color: var(--color-accent);
        }

        .map-legend {
            margin-top: 15px;
            font-size: 0.85rem;
            color: #aaa;
            display: flex;
            gap: 20px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 2px solid var(--color-accent);
        }

        .legend-dot.safe {
            border-color: #00FFC0;
            background-color: #00FFC0;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">BEYOND SURVIVAL</div>
    <nav>
        <a href="index.php">Accueil</a>
    </nav>
</header>

<main class="hero">
    <h1>CARTE DE LA ZONE</h1>
    <p>
        Par défaut, tous les chemins sont visibles. Lorsque tu calcules un itinéraire,
        seule la route empruntée est affichée et les points du trajet sont mis en surbrillance.
    </p>

    <!-- FORMULAIRE ITINÉRAIRE -->
    <section class="path-form">
        <h2>Calculer un itinéraire</h2>
        <form method="get">
            <div class="form-grid">
                <div class="form-group">
                    <label for="start">Départ</label>
                    <select name="start" id="start" required>
                        <?php foreach (array_keys($locByName) as $name): ?>
                            <option value="<?php echo htmlspecialchars($name); ?>"
                                <?php echo ($name === $selectedStart) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="via">Point de passage (optionnel)</label>
                    <select name="via" id="via">
                        <option value="">— Aucun —</option>
                        <?php foreach (array_keys($locByName) as $name): ?>
                            <option value="<?php echo htmlspecialchars($name); ?>"
                                <?php echo ($name === $selectedVia) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="end">Arrivée</label>
                    <select name="end" id="end" required>
                        <?php foreach (array_keys($locByName) as $name): ?>
                            <option value="<?php echo htmlspecialchars($name); ?>"
                                <?php echo ($name === $selectedEnd) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="mode">Mode de transport</label>
                    <select name="mode" id="mode" required>
                        <option value="foot"  <?php echo ($selectedMode === 'foot')  ? 'selected' : ''; ?>>À pied</option>
                        <option value="car"   <?php echo ($selectedMode === 'car')   ? 'selected' : ''; ?>>Voiture</option>
                        <option value="train" <?php echo ($selectedMode === 'train') ? 'selected' : ''; ?>>Train</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-primary">Calculer</button>
        </form>

        <?php if ($errorMessage): ?>
            <div class="path-error">
                <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php elseif ($computedPath): ?>
            <div class="path-result">
                <h3>Itinéraire trouvé</h3>
                <p>Temps total estimé : <strong><?php echo $computedPath['distance']; ?></strong> unités de temps</p>
                <ol>
                    <?php foreach ($computedPath['names'] as $name): ?>
                        <li><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        <?php endif; ?>
    </section>

    <!-- CARTE -->
    <div class="map-wrapper">
        <div class="map-grid">

            <!-- ROUTES : toutes par défaut, seulement le chemin si itinéraire -->
            <svg class="map-roads" viewBox="0 0 100 100" preserveAspectRatio="none">
                <?php if ($computedPath === null): ?>
                    <?php
                    $drawn = [];
                    foreach ($locations as $loc) {
                        foreach ($loc->roads as $road) {
                            $n1 = $road->depart->name;
                            $n2 = $road->destination->name;
                            $key = ($n1 < $n2) ? "$n1|$n2" : "$n2|$n1";
                            if (isset($drawn[$key])) {
                                continue;
                            }
                            $drawn[$key] = true;

                            if (!isset($coordsByName[$n1]) || !isset($coordsByName[$n2])) {
                                continue;
                            }

                            $c1 = $coordsByName[$n1];
                            $c2 = $coordsByName[$n2];

                            $x1 = $c1['left'];
                            $y1 = 100 - $c1['bottom'];
                            $x2 = $c2['left'];
                            $y2 = 100 - $c2['bottom'];

                            $class = 'road-car';
                            if ($road->transport === Transport::Foot) {
                                $class = 'road-foot';
                            } elseif ($road->transport === Transport::Train) {
                                $class = 'road-train';
                            }
                            ?>
                            <line
                                x1="<?php echo $x1; ?>"
                                y1="<?php echo $y1; ?>"
                                x2="<?php echo $x2; ?>"
                                y2="<?php echo $y2; ?>"
                                class="<?php echo $class; ?>"
                            />
                        <?php
                        }
                    }
                    ?>
                <?php else: ?>
                    <?php foreach ($pathEdges as $edge): ?>
                        <?php
                        [$n1, $n2] = $edge;
                        if (!isset($coordsByName[$n1]) || !isset($coordsByName[$n2])) {
                            continue;
                        }
                        $c1 = $coordsByName[$n1];
                        $c2 = $coordsByName[$n2];

                        $x1 = $c1['left'];
                        $y1 = 100 - $c1['bottom'];
                        $x2 = $c2['left'];
                        $y2 = 100 - $c2['bottom'];
                        ?>
                        <line
                            x1="<?php echo $x1; ?>"
                            y1="<?php echo $y1; ?>"
                            x2="<?php echo $x2; ?>"
                            y2="<?php echo $y2; ?>"
                            class="road-on-path"
                        />
                    <?php endforeach; ?>
                <?php endif; ?>
            </svg>

            <!-- POINTS -->
            <?php foreach ($locations as $loc): ?>
                <?php
                    $hash   = spl_object_hash($loc);
                    $coords = $locCoords[$hash];

                    $left   = $coords['left'];
                    $bottom = $coords['bottom'];

                    $isSafe  = str_contains(strtolower($loc->name), 'safe')
                            || str_contains(strtolower($loc->name), 'bunker');
                    $onPath  = in_array($loc->name, $activePathNames, true);
                    $classes = 'map-point';
                    if ($isSafe) $classes .= ' safe';
                    if ($onPath) $classes .= ' on-path';
                ?>
                <div
                    class="<?php echo $classes; ?>"
                    style="left: <?php echo $left; ?>%; bottom: <?php echo $bottom; ?>%;"
                >
                    <div class="point-dot"></div>
                    <div class="point-label">
                        <?php echo htmlspecialchars($loc->name, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <div class="map-legend">
        <div class="legend-item">
            <span class="legend-dot"></span> Lieu standard
        </div>
        <div class="legend-item">
            <span class="legend-dot safe"></span> Refuges / zones sûres
        </div>
    </div>
</main>

</body>
</html>
