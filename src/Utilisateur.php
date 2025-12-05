<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['user'];
$pageTitle = "Profil de " . $user['pseudo'];

include 'header.php';
?>

<link rel="stylesheet" href="user_styles.css">

<main>
    <section class="profile-header">
        <h1 class="glitch" data-text="DOSSIER SURVIVANT">DOSSIER SURVIVANT</h1>
        <p>Accès autorisé. Bienvenue dans votre espace personnel.</p>
    </section>

    <div class="dashboard-grid">
        
        <div class="survivor-card">
            <div class="card-title">
                <i class="fas fa-id-card"></i> IDENTITÉ
            </div>
            
            <div class="data-row">
                <span class="data-label">Pseudo</span>
                <span class="data-value"><?php echo $user['pseudo']; ?></span>
            </div>
            <div class="data-row">
                <span class="data-label">Nom</span>
                <span class="data-value"><?php echo $user['nom']; ?></span>
            </div>
            <div class="data-row">
                <span class="data-label">Prénom</span>
                <span class="data-value"><?php echo $user['prenom']; ?></span>
            </div>
        </div>

        <div class="survivor-card">
            <div class="card-title">
                <i class="fas fa-wallet"></i> RESSOURCES
            </div>

            <div class="data-row" style="text-align: center;">
                <span class="data-label">Solde Actuel</span>
                <div class="balance-display">
                    <?php echo $user['argent']; ?> <span style="font-size:1em;">₿</span>
                </div>
            </div>

            <div style="margin-top: auto;">
                <a href="Boutique.php" class="btn-primary" style="width: 100%; text-align: center; display: block;">Accéder au Ravitaillement</a>
            </div>
        </div>
    </div>

    <div class="logout-section">
        <a href="Logout.php" class="btn-shop" style="padding: 15px 50px; border-color: #666; color: #888;">Se déconnecter</a>
    </div>
</main>

<?php include 'footer.php'; ?>