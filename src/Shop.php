<?php

include_once('Session_start.php');
include_once('Header.php');
include_once('Class.php');

use Class\Produit;
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
        $item['description'],
        $item['image'],
        $item['categorie']
    );
}

$boutique = new Boutique($liste_produits);
$boutique->afficherBoutique();

?>

<main>
    <section class="shop-header">
        <h1>ÉQUIPEMENT DE SURVIE</h1>
        <p>Préparez-vous avant la sirène.</p>
    </section>

    <section class="shop-container">
        <div class="product-grid">
            <!-- FCNT DISPLAY CART -->
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>