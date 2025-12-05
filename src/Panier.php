<?php
$pageTitle = "Panier";
include_once('Header.php');
include_once('Class.php');

if (isset($_GET['supprimer'])) {
    $id_a_supprimer = $_GET['supprimer'];
    $key = array_search($id_a_supprimer, $_SESSION['liste_produits_id']);
    if ($key !== false) {
        if (isset($_SESSION['somme'])) {
            $data = json_decode(file_get_contents('../data/produits.json'), true);
            foreach ($data as $item) {
                if ($item['id'] == $id_a_supprimer) {
                    $_SESSION['somme'] -= $item['prix'];
                    break;
                }
            }
        }
        
        $_SESSION['liste_produits_id'] = array_values($_SESSION['liste_produits_id']);
    }
    header('Location: Panier.php');
    exit();
}

use Class\Produit;
use Class\Panier;

$liste_categories = \Class\construireTableau();

function espaceFooter($contexte) {
    echo "<main>";
    echo "<section class='profile-header'>";
    echo "<h1>" . $contexte . "</h1>";
    echo "<p>Retrouvez vos choix ici.</p>";
    echo "</section>";
    echo "</main>";
    echo "<div style=\"margin-top: 335px;\"></div>";
    include 'footer.php';
    exit();
}
?>
<main>
    <section class="profile-header">
        <?php
        if (!isset($_SESSION['user'])) {
            espaceFooter("VOUS DEVEZ VOUS CONNECTER");
        }

        if (empty($_SESSION['liste_produits_id'])) {
            espaceFooter("VOTRE PANIER EST VIDE");
        }
        ?>
        <h1>PANIER</h1>
        <p>Retrouvez vos choix ici.</p>
    </section>

    <section class="shop-container">
        <div class="panier-container">
        <?php
        $json_data = file_get_contents("../data/produits.json");
        $data_produits = json_decode($json_data, true); 

        $liste_produits_panier = [];
        foreach ($_SESSION['liste_produits_id'] as $id) {
            foreach ($data_produits as $item) {
                if ($item['id'] == $id) {
                    $categorie_obj = $liste_categories[$item['categorie']];
                    $produit = new Produit(
                        $item['id'],
                        $item['nom'],
                        $item['prix'],
                        $item['image'],
                        $categorie_obj
                    );
                    $liste_produits_panier[] = $produit;
                }
            }
        }

        $panier = new Panier($liste_produits_panier);
        $panier->afficherPanier();
        ?>
        </div>
    <div class="panier-summary">
            <div class="panier-total">
                    <span class="total-label">TOTAL PANIER</span>
                    <span class="total-amount"><?php echo isset($_SESSION['somme']) ? $_SESSION['somme'] : 0; ?> ₿</span>
                </div>
                <form method="POST" action="Payement.php">
                    <button type="submit" class="btn-payer">
                        <i class="fas fa-lock"></i> PAYER MAINTENANT
                    </button>
                </form>
            </div>
        </section>
    </main>
<?php include 'footer.php'; ?>