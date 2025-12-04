<?php
$pageTitle = "Accueil - LA PURGE";

include 'src/header.php';
?>

<main>
    <section class="hero">
        <h1 class="glitch" data-text="LA PURGE A DEBUTE">LA PURGE A DÉBUTÉ</h1>
        <p>La survie est votre seule préoccupation.</p>
        <div id="countdown">
            <span class="timer-label">Fin dans</span>
            <span id="demo" class="timer-value">00:00:00</span>
        </div>
        <a href="/includes/Map.php" class="btn-primary">ENTRER DANS LA ZONE</a>
    </section>
</main>

<script src="/script/countdown.js"></script>

<?php
include 'src/footer.php';
?>