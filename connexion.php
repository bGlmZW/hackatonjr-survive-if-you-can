<?php

session_start();

// Tout est à la racine, donc on appelle le fichier directement
require_once('error.php');

// Chemin vers le fichier JSON
// Si votre fichier est dans un dossier 'data' à la racine : 'data/data.json'
// Si votre fichier est directement à la racine avec le reste : 'data.json'
$data_file = 'data/data.json'; 

if (file_exists($data_file)) {
    $json_data = file_get_contents($data_file);
    $data = json_decode($json_data, true);
    
    // Vérification de la validité du JSON
    if ($data === null) {
        // Si la fonction displayError est dans error.php
        if (function_exists('displayError')) {
            displayError("Erreur de lecture du fichier data.json");
        } else {
            die("Erreur de lecture du fichier data.json");
        }
        exit;
    }
} else {
    if (function_exists('displayError')) {
        displayError("Le fichier data.json est introuvable.");
    } else {
        die("Le fichier data.json est introuvable.");
    }
    exit;
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Nettoyage de l'email
    $email = isset($_POST['email']) ? htmlspecialchars(trim(strtolower($_POST['email']))) : ''; 
    
    // Récupération du mot de passe (sans htmlspecialchars pour éviter de casser les caractères spéciaux)
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Vérification des champs vides
    if (empty($email) || empty($password)) {
        echo "<script>alert('Veuillez remplir tous les champs.'); window.history.back();</script>";
        exit;
    }

    $user_found = false;

    // Parcours des utilisateurs
    foreach ($data as $user) {
        $user_email = trim(strtolower($user['email']));

        if ($user_email === $email) {
            $user_found = true;
            
            // Vérification du mot de passe
            if (password_verify($password, $user['password'])) {
                
                // Connexion réussie : On enregistre l'utilisateur en session
                $_SESSION['user'] = $user;

                // Log (optionnel, si la fonction existe)
                if (function_exists('writeToLog')) {
                    writeToLog($user['email'] . " s'est connecté. (ID: ". $_SESSION['user']['id'] . ")");
                }

                // REDIRECTION : Directement vers shop.php (car tout est à la racine)
                header("Location: user_page.php");
                exit;
                
            } else {
                // Mot de passe incorrect
                echo "<script>alert('Mot de passe incorrect.'); window.history.back();</script>";
                exit;
            }
        }
    }

    // Email non trouvé
    if (!$user_found) {
        echo "<script>alert('Compte inexistant pour cet email.'); window.history.back();</script>";
        exit;
    }

} else {
    // Si on essaie d'accéder au fichier sans POST, on renvoie à l'accueil
    header("Location: index.php");
    exit;
}
?>