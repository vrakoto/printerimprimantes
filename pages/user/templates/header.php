<?php
use App\User;

if (isset($_GET['switchColumns'])) {
    $_SESSION['showColumns'] = ($_SESSION['showColumns'] === 'few') ? 'all' : 'few';
    header('Location:' . $url);
    exit();
}
?>

<div class="mt-2" id="header">
    <h1><?= $title ?></h1>

    <button class="btn btn-success" id="downloadCSV" title="Télécharger les données en CSV"><i class="fa-solid fa-download"></i> Télécharger les données</button>
    <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-filter"></i> Recherche / Trier</button>
    <a class="mx-1 btn btn-secondary" href="/<?= $url ?>"><i class="fa-solid fa-arrow-rotate-left"></i> Réinitialiser la recherche</a>

    <?php if (str_contains($url, 'copieur')): ?>
        <form action="" method="get" class="d-inline-block">
            <input type="hidden" name="switchColumns">
            <button class="mx-3 btn btn-primary text-white" href="?page=<?= $page ?>&<?= $fullURL ?>"><?= $showColumns === 'few' ? 'Afficher toutes' : 'Reduire' ?> les informations</button>
        </form>
    <?php endif ?>


    <?php if (User::getRole() === 2 && $url === 'responsables-perimetre'): ?>
        <a class="mx-1 btn btn-success" href="<?= $router->url('view_create_edit_user') ?>"><i class="fa-solid fa-plus"></i> Créer/Modifier un utilisateur</a>
    <?php endif ?>
</div>