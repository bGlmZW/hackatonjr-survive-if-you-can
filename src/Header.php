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
        <div class="logo"><a href="/index.php">CYTC</a></div> 
        
        <nav class="icon-nav">
            <a href="src/Shop.php" class="nav-icon"><i class="fas fa-shopping-cart"></i></a>
            <a href="src/Utilisateur.php" class="nav-icon"><i class="fas fa-user"></i></a>
        </nav>
    </header>