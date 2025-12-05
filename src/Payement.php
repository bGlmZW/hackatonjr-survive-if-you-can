<?php
session_start();
    if($_SESSION['user']['argent'] < $_SESSION['somme']) {
        echo "<script>alert('Echec. Vous n'avez pas les fonds nécessaires.')</script>";
        header('Location: Panier.php');
        exit();
    } else {
        $_SESSION['user']['argent'] -= $_SESSION['somme'];
        var_dump(file_exists('utilisateurs.json'));
        $data = json_decode(file_get_contents('../data/utilisateurs.json'), true);

        foreach ($data as &$u) {
            if ($u['id'] == $_SESSION['user']['id']) {
                $u['argent'] = $_SESSION['user']['argent'];
                break;
            }
        }

        file_put_contents('../data/utilisateurs.json', json_encode($data, JSON_PRETTY_PRINT));
        unset($_SESSION['liste_produits_id']);
        unset($_SESSION['somme']);
        header('Location: Commande.php');
    }
?>