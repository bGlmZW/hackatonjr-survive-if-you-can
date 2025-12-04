<?php
session_start();

// Vérification de sécurité : si pas connecté, retour à l'accueil
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Récupération des infos utilisateur
$user = $_SESSION['user'];
$pageTitle = "Profil de " . htmlspecialchars($user['forename']) . " - CYTC";

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
                <span class="data-label">Nom complet</span>
                <span class="data-value">
                    <?php echo htmlspecialchars(ucfirst($user['forename']) . ' ' . strtoupper($user['name'])); ?>
                    
                    <?php 
                    $roleClass = '';
                    if($user['role'] === 'vip') $roleClass = 'role-vip';
                    if($user['role'] === 'admin') $roleClass = 'role-admin';
                    ?>
                    <span class="role-badge <?php echo $roleClass; ?>"><?php echo htmlspecialchars($user['role']); ?></span>
                </span>
            </div>

            <div class="data-row">
                <span class="data-label">Email de contact</span>
                <span class="data-value"><?php echo htmlspecialchars($user['email']); ?></span>
            </div>

            <div class="data-row">
                <span class="data-label">Téléphone</span>
                <span class="data-value"><?php echo htmlspecialchars($user['telephone']); ?></span>
            </div>
            
            <div class="data-row">
                <span class="data-label">Matricule (ID)</span>
                <span class="data-value" style="font-size: 0.8em; color: #444;"><?php echo htmlspecialchars($user['id']); ?></span>
            </div>
        </div>

        <div class="survivor-card">
            <div class="card-title">
                <i class="fas fa-wallet"></i> RESSOURCES
            </div>

            <div class="data-row" style="text-align: center;">
                <span class="data-label">Solde Actuel</span>
                <div class="balance-display">
                    <?php echo number_format($user['points'], 0); ?> <span style="font-size:0.4em;">PTS</span>
                </div>
            </div>

            <div style="margin-top: auto;">
                <a href="shop.php" class="btn-primary" style="width: 100%; text-align: center; display: block;">
                    Accéder au Ravitaillement
                </a>
            </div>
        </div>

    </div>

    <div class="logout-section">
        <a href="logout.php" class="btn-shop" style="padding: 15px 50px; border-color: #666; color: #888;">
            Se déconnecter
        </a>
    </div>

</main>

<?php include 'footer.php'; ?>