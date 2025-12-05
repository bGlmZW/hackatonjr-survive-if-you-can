<?php
$pageTitle = "Boutique";
include_once('Header.php');
include_once('Class.php');

use Class\Produit;
use Class\Boutique;

$liste_categories = \Class\construireTableau();

$json_data = file_get_contents("../data/produits.json");
$data_produits = json_decode($json_data, true); 

foreach($data_produits as $item) {
    $categorie_obj = $liste_categories[$item['categorie']];
    $liste_produits[] = new Produit(
        $item['id'],
        $item['nom'],
        $item['prix'],
        $item['image'],
        $categorie_obj
    );
}

if (!isset($_SESSION['liste_produits_id'])) {
    $_SESSION['liste_produits_id'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produit_id'])) {
    $_SESSION['liste_produits_id'][] = $_POST['produit_id'];
}

function quickSort($array, $type) {
    $length = count($array);

    if ($length <= 1) {
        return $array;
    } else {
        $pivot = $array[0];
        $left = $right = array();

        for ($i = 1; $i < $length; $i++) {
            if (compareProduits($array[$i], $pivot, $type)) {
                $left[] = $array[$i];
            } else {
                $right[] = $array[$i];
            }
        }

        return array_merge(quickSort($left, $type), array($pivot), quickSort($right, $type));
    }
}

function compareProduits($a, $b, $type) {
    switch($type) {
        case 'price-asc':
            return $a->prix < $b->prix;
        case 'price-desc':
            return $a->prix > $b->prix;
        case 'name-asc':
            return $a->nom < $b->nom;
        case 'name-desc':
            return $a->nom > $b->nom;
        default:
            return 'price-asc';
        }
}

if (isset($_GET['sort'])) {
    $type = $_GET['sort'];
} else {
    $type = 'price-asc';
}

$liste_produits = quickSort($liste_produits, $type);
$boutique = new Boutique($liste_produits);
?>

<main>
    <section class="profile-header">
        <h1>ÉQUIPEMENT DE SURVIE</h1>
        <p>Marchandises livrées par drone.</p>
    </section>
    <section class="shop-container">
                <div class="sort-box">
            <label for="sort">Trier par :</label>
            <select id="sort">
                <option value="price-asc" <?php echo $type === 'price-asc' ? 'selected' : ''; ?>>Prix croissant</option>
                <option value="price-desc" <?php echo $type === 'price-desc' ? 'selected' : ''; ?>>Prix décroissant</option>
                <option value="name-asc" <?php echo $type === 'name-asc' ? 'selected' : ''; ?>>Nom A-Z</option>
                <option value="name-desc" <?php echo $type === 'name-desc' ? 'selected' : ''; ?>>Nom Z-A</option>
            </select>
        </div>
    <div class="product-grid">
    <?php $boutique->afficherBoutique(); ?>
    </div>
    </section>

    <section class="shop-container">
        <div class="product-grid"></div>
    </section>
</main>

<script>
document.getElementById('sort').addEventListener('change', function() {
    const sortValue = this.value;
    const url = new URL(window.location);
    url.searchParams.set('sort', sortValue);
    window.location.href = url.toString();
});
</script>

<?php include 'footer.php'; ?>