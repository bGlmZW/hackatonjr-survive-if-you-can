<?php
$pageTitle = "Magasin - CYTC";
include 'header.php';

// Simulation d'une base de données produits
$products = [
    [
        "name" => "Masque 'God'",
        "price" => 49.99,
        "image" => "https://img.fruugo.com/product/0/08/1761802080_max.jpg",
        "stock" => 10
    ],
    [
        "name" => "Batte Cloutée",
        "price" => 85.00,
        "image" => "https://m.media-amazon.com/images/I/71ZtjfNk1yL.jpg",
        "stock" => 10
    ],
    [
        "name" => "Kit de Premier Secours",
        "price" => 120.00,
        "image" => "https://www.narescue.com/media/catalog/product/cache/6c9c2a8706ce872329c582c5a62c8bc4/8/0/80-0947_g.jpg",
        "stock" => 10
    ],
    [
        "name" => "Gilet Tactique N-4",
        "price" => 250.00,
        "image" => "https://cdn.military.eu/fr/media/catalog/product/2/3/2373369_Kamizelka-taktyczna-M-Tac-Sturm-Gen-II-Coyote-na-plyty-rozmiar-M-L-XL-front.jpg",
        "stock" => 10
    ],
    [
        "name" => "Rifle",
        "price" => 3500.00,
        "image" => "https://www.roumaillac.com/527043-home_default/Carabine-daniel-defense-m4a1.jpg",
        "stock" => 10
    ]
];
?>

<main>
    <section class="shop-header">
        <h1>ÉQUIPEMENT DE SURVIE</h1>
        <p>Préparez-vous avant la sirène.</p>
    </section>

    <section class="shop-container">
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card <?php echo isset($product['disabled']) ? 'disabled' : ''; ?>">
                    
                    <?php if (!empty($product['tag'])): ?>
                        <span class="badge"><?php echo $product['tag']; ?></span>
                    <?php endif; ?>

                    <div class="card-image">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                    </div>

                    <div class="card-info">
                        <h3><?php echo $product['name']; ?></h3>
                        <div class="price"><?php echo number_format($product['price'], 2); ?> €</div>
                        
                        <?php if (isset($product['disabled'])): ?>
                            <button class="btn-shop disabled" disabled>ÉPUISÉ</button>
                        <?php else: ?>
                            <button class="btn-shop">AJOUTER AU STOCK</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>