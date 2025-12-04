<?php

include_once('Session_start.php');
include_once('Header.php');
include_once('Class.php');

use Class\Produit;
use Class\Categorie;
use Class\Boutique;

$pageTitle = "Shop - LA PURGE";

$json_data = file_get_contents("../data/produits.json");
$data = json_decode($json_data, true); 

foreach($data as $item) {
    $liste_produits[] = new Produit(
        $item['id'],
        $item['nom'],
        $item['prix'],
        $item['stock'],
        $item['image'],
        $item['categorie']
    );
}


$liste_categories = [
    new Categorie(1, "Accessoires"),
    new Categorie(2, "Armes blanches"),
    new Categorie(3, "Équipement"),
    new Categorie(4, "Fusils"),
    new Categorie(5, "Fusils"),
    ];
?>

<main>
    <section class="shop-header">
        <h1>ÉQUIPEMENT DE SURVIE</h1>
        <p>Marchandises livrées par drone</p>
    </section>

    <section class="shop-container">
                <div class="sort-box">
            <label for="sort">Trier par :</label>
            <select id="sort">
                <option value="price-asc">Prix croissant</option>
                <option value="price-desc">Prix décroissant</option>
                <option value="name-asc">Nom A-Z</option>
                <option value="name-desc">Nom Z-A</option>
            </select>
        </div>
    <div class="product-grid">
    <?php $boutique = new Boutique($liste_produits); ?>
    <?php $boutique->afficherBoutique(); ?>
    </div>
    </section>  

    <section class="shop-container">
        <div class="product-grid"></div>
    </section>
</main>

<?php include 'footer.php'; ?>