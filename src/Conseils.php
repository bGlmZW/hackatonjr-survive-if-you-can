<?php
$pageTitle = "Guide de Survie";
include_once('Header.php');
include_once('Class.php');

use Class\Conseil;

$liste_categories = \Class\constructeurCategorieTab();

$json_data = file_get_contents("../data/conseils.json");
$data = json_decode($json_data, true);

foreach ($data as $conseil) {
    $categorie_obj = $liste_categories[$conseil['categorie']];
    $liste_conseils[] = new Conseil(
        $conseil['id'],
        $categorie_obj,
        $conseil['texte'],
        $conseil['image']
    );
}
?>

<main>
    <section class="profile-header">
        <h1>GUIDE DU SURVIVANT</h1>
        <p>Essentiel si tu veux survivre.</p>
    </section>
</main>

<div class="conseil-container">
    <div class="conseil-card">
        <h2 class="conseil-categorie"><?php echo $liste_conseils[1]->categorie->nom; ?></h2>

        <img src=<?php echo $liste_conseils[1]->image; ?> alt="Image conseil" class="conseil-image">

        <p class="conseil-texte"><?php echo $liste_conseils[1]->texte; ?></p>

        <button class="conseil-btn-suivant">Suivant</button>
    </div>
</div>

<script>
    const conseils = <?php echo json_encode(array_map(function($c) {
        return [
            'categorie' => $c->categorie->nom,
            'texte' => $c->texte,
            'image' => $c->image
        ];
    }, $liste_conseils)); ?>;

    let currentIndex = 0;

    const categorieElem = document.querySelector('.conseil-categorie');
    const imageElem = document.querySelector('.conseil-image');
    const texteElem = document.querySelector('.conseil-texte');
    const btnSuivant = document.querySelector('.conseil-btn-suivant');

    btnSuivant.addEventListener('click', () => {
        currentIndex++;
        if (currentIndex >= conseils.length) currentIndex = 0;

        categorieElem.textContent = conseils[currentIndex].categorie;
        imageElem.src = conseils[currentIndex].image;
        texteElem.textContent = conseils[currentIndex].texte;
    });
</script>

<?php include 'footer.php'; ?>