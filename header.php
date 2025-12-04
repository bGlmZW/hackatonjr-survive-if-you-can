<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'American Nightmare - Projet Web'; ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header>
        <div class="logo">CYTC</div> 
        
        <nav class="icon-nav">
            <a href="shop.php" class="nav-icon"><i class="fas fa-shopping-cart"></i></a>
            
            <a href="#" class="nav-icon" onclick="openSignInOverlay(event)">
                <i class="fas fa-user"></i>
            </a>
        </nav>
    </header>
    
    <?php 
        // Gestion du chemin relatif
        if (!isset($path_parent)) { $path_parent = ''; } 
    ?>

    <div class="overlay" id="signin_overlay">
        <div class="overlay_content">
            <span class="close_btn" onclick="closeSignInOverlay()">&times;</span>
            <h2>Connexion</h2>
            
            <form id="signin_form" action="<?php echo $path_parent; ?>connexion.php" method="POST">
                <div class="input-container">
                    <input class="overlay_input" type="text" id="emailInputLogin" name="email" placeholder="Email" maxlength="50" required>
                </div>
                
                <div class="input-container">
                    <input class="overlay_input" type="password" id="passwordInputLogin" name="password" placeholder="Mot de passe" maxlength="20" required>
                </div>
                
                <button class="overlay_button" type="submit">Se connecter</button>
                
            
            </form>
        </div>
    </div>

    <div class="overlay" id="signup_overlay">
        <div class="overlay_content">
            <span class="close_btn" onclick="closeSignUpOverlay()">&times;</span>
            <h2>Inscription</h2>
            
            <form id="signup_form" action="<?php echo $path_parent; ?>src/inscription.php" method="POST">
                <div class="input-container">
                    <input class="overlay_input" type="text" id="emailInputSignup" name="email" placeholder="Email" maxlength="50" required>
                </div>
                
                <div class="input-container">
                    <input class="overlay_input" type="password" id="passwordInputSignup" name="password" placeholder="Mot de passe" maxlength="20" required>
                </div>

                 <div class="input-container">
                    <input class="overlay_input" type="password" id="confirmPasswordInput" name="confirm_password" placeholder="Confirmer mot de passe" maxlength="20" required>
                </div>
                
                <button class="overlay_button" type="submit">S'inscrire</button>
                
                <p class="switch_text">
                    Déjà un compte ?
                    <a href="#" onclick="switchToSignIn()">Se connecter</a>
                </p>
            </form>
        </div>
    </div>
    
    <script src="<?php echo $path_parent; ?>registration.js"></script>

    <div id="bubble" class="hidden"></div>
    
    <script src="<?php echo $path_parent; ?>bubble.js"></script>