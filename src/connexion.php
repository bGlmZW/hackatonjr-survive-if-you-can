<?php

session_start();

$data_file = '../data/utilisateurs.json'; 

if (file_exists($data_file)) {
    $json_data = file_get_contents($data_file);
    $data = json_decode($json_data, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $pseudo = isset($_POST['pseudo']) ? htmlspecialchars(trim(strtolower($_POST['pseudo']))) : ''; 
    
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    $user_found = false;

    foreach ($data as $user) {
        $user_pseudo = trim(strtolower($user['pseudo']));

        if ($user_pseudo === $pseudo) {
            $user_found = true;
            
            if ($password === $user['password']) {
                $_SESSION['user'] = $user;
                header("Location: Utilisateur.php");
                exit;
            } else {
                echo "<script>alert('Mot de passe incorrect.'); window.history.back();</script>";
                exit;
            }
        }
    }

    if (!$user_found) {
        echo "<script>alert('Compte inexistant pour ce pseudo.'); window.history.back();</script>";
        exit;
    }

} else {
    header("Location: index.php");
    exit;
}
?>