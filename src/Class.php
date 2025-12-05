<?php

namespace Class;

class Utilisateur
{
    public $id;
    public $nom;
    public $prenom;
    public $argent;
    public $panier = [];

    public function __construct($id, $nom, $prenom, $argent, array $panier = []) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->argent = $argent;
        $this->panier = $panier;
    }
}

class Boutique
{
    public $liste_produits = [];

    public function afficherBoutique() {
        if (empty($this->liste_produits)) {
            echo "<p>Aucun produit dans le panier.</p>";
            return;
        }

        foreach ($this->liste_produits as $product): 
        ?>
        
        <div class="product-card">

            <?php if (!empty($product->categorie)): ?>
            <?php endif; ?>

            <div class="card-image">
                <img src="<?php echo $product->image; ?>" alt="<?php echo $product->nom; ?>">
            </div>

            <div class="card-info">
                <h3><?php echo $product->nom; ?></h3>
                <div class="price"><?php echo $product->prix?> ₿</div>

                <form method="POST" action="">
                    <input type="hidden" name="produit_id" value="<?php echo $product->id; ?>">
                    <button type="submit" class="btn-shop">
                        AJOUTER AU STOCK
                    </button>
                </form>
            </div>
        </div>
        <?php
        endforeach;
    }

    public function __construct(array $liste_produits = []) {
        $this->liste_produits = $liste_produits;
    }
}

class Categorie
{
    public $id;
    public $nom;

    public function __construct($id, $nom) {
        $this->id = $id;
        $this->nom = $nom;
    }
}

class Produit
{
    public $id;
    public $nom;
    public $prix;
    public $image;
    public $categorie;

    public function __construct($id, $nom, $prix, $image, Categorie $categorie) {
        $this->id = $id;
        $this->prix = $prix;
        $this->nom = $nom;
        $this->image = $image;
        $this->categorie = $categorie;
    }
}

class Conseil
{
    public $id;
    public $categorie;
    public $texte;
    public $image;

    public function __construct($id, Categorie $categorie, $texte, $image) {
        $this->id = $id;
        $this->categorie = $categorie;
        $this->image = $image;
        $this->texte = $texte;
    }
}

function construireTableau() {
    $json_data = file_get_contents("../data/categories.json");
    $data_categories = json_decode($json_data, true);

    $liste_categories = [];

    foreach($data_categories as $item) {
        $liste_categories[$item['id']] = new Categorie(
            $item['id'],
            $item['nom'],
        );
    }

    return $liste_categories;
}

class Panier
{
    public $liste_produits_panier = [];

    public function afficherPanier() {
        foreach ($this->liste_produits_panier as $produit) {
            echo '<div class="produit-card">
                <img class="produit-image" src="' . $produit->image . '" alt="' . $produit->nom . '">
                <div class="produit-info">
                    <h3>' . $produit->nom . '</h3>
                    <p>Prix : ' . $produit->prix . ' ₿</p>
                </div>
                <form method="POST" action="Retirer_produit.php">
                    <input type="hidden" name="produit_id" value="' . $produit->id . '">
                    <a href="Panier.php?supprimer=' . $produit->id . '">Retirer du panier</a>
                </form>
            </div>';
        }
    }

    public function total() {
        $somme = 0;
        for ($i = 0; $i < count($this->liste_produits_panier); $i++) {
            $somme += $this->liste_produits_panier[$i]->prix;
        }
    }

    function __construct($liste_produits_panier)
    {
        $this->liste_produits_panier = $liste_produits_panier;
    }
}

class Notification
{

}
?>