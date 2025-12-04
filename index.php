<?php
// On définit le titre de la page avant d'inclure le header
$pageTitle = "Accueil - LA PURGE";

// On inclut l'en-tête
include 'header.php';
?>

    <main>
        <section class="hero">
            <h1 class="glitch" data-text="LA PURGE A DEBUTE">LA PURGE A DEBUTE</h1>
            <p>La survie est votre seule préoccupation.</p>
            <div id="countdown">
                <span class="timer-label">Début dans :</span>
                <span class="timer-value">00:00:00</span>
            </div>
            <a href="#" class="btn-primary">ENTRER DANS LA ZONE</a>
        </section>
    </main>

<?php
// On inclut le pied de page
include 'footer.php';
?>