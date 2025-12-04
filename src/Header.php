<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'American Nightmare - Hackaton Jr'; ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <header>
        <div class="logo"><a href="/index.php">Survive If You Can</a></div> 
        
        <nav class="icon-nav">
            <a href="/src/Conseils.php" class="nav-icon"><i class="fa-solid fa-shield"></i></a>
            <a href="/src/Shop.php" class="nav-icon"><i class="fas fa-gun"></i></a>
            <a href="/src/Panier.php" class="nav-icon"><i class="fa-solid fa-basket-shopping"></i></a>
            <a href="/src/Utilisateur.php" class="nav-icon"><i class="fas fa-user"></i></a>
        </nav>

        <div id="bubble"></div>
    </header>
    <script src="/script/notification.js"></script>