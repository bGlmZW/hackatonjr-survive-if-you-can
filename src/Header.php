<?php include_once('Session_start.php'); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="https://static.thenounproject.com/png/3194193-200.png"/>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

    <header>
        <div class="logo"><a href="/index.php">Survive If You Can</a></div> 
        
        <nav class="icon-nav">
            <a href="/src/Conseils.php" class="nav-icon"><i class="fa-solid fa-shield"></i></a>
            <a href="/src/Boutique.php" class="nav-icon"><i class="fas fa-gun"></i></a>
            <a href="/src/Panier.php" class="nav-icon"><i class="fa-solid fa-basket-shopping"></i></a>
            <a href="#" class="nav-icon" id="user_icon"><i class="fas fa-user"></i></a>
            <?php
                if (isset($_SESSION['user'])) {
                    echo '<a href="/src/Parametres.php" class="nav-icon"><i class="fa-solid fa-gear"></i></a>';
                }
            ?>
        </nav>
    </header>

    <div class="overlay" id="signin_overlay">
        <div class="overlay_content">
            <span class="close_btn" onclick="closeSignUpOverlay()">&times;</span>
            <h2>Connexion</h2>
            
            <form id="signin_form" action="/src/Connexion.php" method="POST">
                <div class="input-container">
                    <input class="overlay_input" type="text" id="pseudoInputLogin" name="pseudo" placeholder="Pseudo" maxlength="20" required>
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
        </div>
    </div>
    
    <script src="/script/registration.js"></script>

    <script src="/script/bubble.js"></script>

    <?php
    if (isset($_SESSION['user'])) {
        echo "<div id=\"bubble\"></div>";
        echo "<script src=\"/script/notification.js\"></script>";
    }
    ?>

    <script>
    document.getElementById("user_icon").addEventListener("click", function (e) {
        e.preventDefault();

    <?php if (!isset($_SESSION['user'])) { ?>
        document.getElementById("signin_overlay").classList.add("active");
        document.body.classList.add("no_scroll");
    <?php } else { ?>
        window.location.href = "/src/Utilisateur.php";
    <?php } ?>
});
</script>