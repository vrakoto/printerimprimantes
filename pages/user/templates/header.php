<?php
use App\User;
?>

<div class="mt-2" id="header">
    <h1><?= $title ?></h1>

    <button class="btn btn-success" id="downloadCSV" title="Télécharger les données en CSV"><i class="fa-solid fa-download"></i> Télécharger les données</button>
    <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-filter"></i> Rechercher / Trier</button>
    <a class="mx-1 btn btn-secondary" href="/<?= $url ?>"><i class="fa-solid fa-arrow-rotate-left"></i> Réinitialiser la recherche</a>

    <?php if (isset($isURLCopieurs)): ?>
        <form action="" method="get" class="d-inline-block">
            <input type="hidden" name="switchColumns">
            <button class="mx-3 btn btn-primary text-white"><?= $showColumns === 'few' ? 'Afficher toutes' : 'Reduire' ?> les informations</button>
        </form>
    <?php endif ?>


    <?php if (isset($isURLCompteurs)): ?>
        <form action="" method="get" class="d-inline-block">
            <input type="hidden" name="uniqueCompteurs">
            <button class="mx-3 btn btn-primary text-white"><i class="fa-solid fa-display"></i> <?= $_SESSION['uniqueCompteurs'] === "false" ? "Afficher uniquement les derniers compteurs" : "Afficher tous les compteurs" ?></button>
        </form>
    <?php endif ?>


    <?php if ((User::getRole() === 2 || User::getRole() === 4) && $url === 'gestion-utilisateurs'): ?>
        <button class="mx-3 btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_create_user"><i class="fa-solid fa-plus"></i> Créer un utilisateur</button>
    <?php endif ?>
</div>