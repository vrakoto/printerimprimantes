<?php
use App\User;

if (isset($_GET['switchColumns'])) {
    $_SESSION['showColumns'] = ($_SESSION['showColumns'] === 'few') ? 'all' : 'few';
    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit();
}

if (isset($_GET['uniqueCompteurs'])) {
    $_SESSION['uniqueCompteurs'] = !$_SESSION['uniqueCompteurs'];
    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>

<div class="mt-2" id="header">
    <h1><?= $title ?></h1>

    <button class="btn btn-success" id="downloadCSV" title="Télécharger les données en CSV"><i class="fa-solid fa-download"></i> Télécharger les données</button>
    <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-filter"></i> Recherche / Trier</button>
    <a class="mx-1 btn btn-secondary" href="/<?= $url ?>"><i class="fa-solid fa-arrow-rotate-left"></i> Réinitialiser la recherche</a>

    <?php if (isset($isURLCopieurs)): ?>
        <form action="" method="get" class="d-inline-block">
            <input type="hidden" name="switchColumns">
            <button class="mx-3 btn btn-primary text-white" href="?page=<?= $page ?>&<?= $fullURL ?>"><?= $showColumns === 'few' ? 'Afficher toutes' : 'Reduire' ?> les informations</button>
        </form>
    <?php endif ?>


    <?php if (isset($isURLCompteurs)): ?>
        <form action="" method="get" class="d-inline-block">
            <input type="hidden" name="uniqueCompteurs">
            <button class="mx-3 btn btn-primary text-white" href="?page=<?= $page ?>&<?= $fullURL ?>"><?= $uniqueCompteurs === false ? 'Afficher tous' : 'Simplifier' ?> les compteurs</button>
        </form>
    <?php endif ?>


    <?php if (User::getRole() === 2 && $url === 'gestion-utilisateurs'): ?>
        <button class="mx-3 btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_create_user"><i class="fa-solid fa-plus"></i> Créer un utilisateur</button>
    <?php endif ?>
</div>