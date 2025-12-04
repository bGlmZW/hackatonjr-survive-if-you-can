<?php

namespace Class;

$pageTitle = "Magasin - CYTC";

class Utilisateur
{
    public $id;
    public $nom;
    public $prenom;
    public $email;
    public $argent;
    public $panier; # liste de produits

    public function __construct($id, $nom, $prenom, $email, $argent, $panier) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->argent = $argent;
        $this->panier = $panier;
    }
}

class Boutique
{
    public $liste_produits;

    public function afficherBoutique() {
        if (empty($this->liste_produits)) {
            echo "<p>Aucun produit dans le panier.</p>";
            return;
        }

        foreach ($this->liste_produits as $product): 
            $disabled = isset($product->stock) && $product->stock <= 0;
        ?>
        
        <div class="product-card <?php echo $disabled ? 'disabled' : ''; ?>">

            <?php if (!empty($product->categorie)): ?>
                <!--<span class="badge"><?php echo $product->categorie; ?></span>-->
            <?php endif; ?>

            <div class="card-image">
                <img src="<?php echo $product->image; ?>" alt="<?php echo $product->nom; ?>">
            </div>

            <div class="card-info">
                <h3><?php echo $product->nom; ?></h3>
                <div class="price"><?php echo number_format($product->prix, 2); ?> €</div>

                <?php if ($disabled): ?>
                    <button class="btn-shop disabled" disabled>ÉPUISÉ</button>
                <?php else: ?>
                    <button class="btn-shop">AJOUTER AU STOCK</button>
                <?php endif; ?>
            </div>

        </div>

        <?php
        endforeach;
    }

    public function ajouterPanier($produit) {
        $this->liste_produits[] = $produit;
    }

    public function enleverPanier() {

    }

    public function total() {
        $somme = 0;
        for ($i = 0; $i < count($this->liste_produits); $i++) {
            $somme += $this->liste_produits->prix;
        }
    }

    public function __construct($liste_produits) {
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
    public $stock;
    public $image;
    public $categorie;

    public function __construct($id, $nom, $prix, $stock, $image, $categorie) {
        $this->id = $id;
        $this->prix = $prix;
        $this->nom = $nom;
        $this->stock = $stock;
        $this->image = $image;
        $this->categorie = $categorie;
    }
}
?>