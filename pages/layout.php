<?php

use App\Driver;
use App\User;

$_SESSION['message'] = [];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/src/CSS/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/src/CSS/fontawesome/CSS/all.min.css">

    <link type="text/css" rel="stylesheet" href="/src/CSS/body.css">
    <link type="text/css" rel="stylesheet" href="/src/CSS/accueil.css">
    <link type="text/css" rel="stylesheet" href="/src/CSS/navbar.css">
    <link type="text/css" rel="stylesheet" href="/src/CSS/modal.css">

    <?php if (Driver::estConnecte()) : ?>
        <?php //if (User::getTheme() === "dark") : ?>
            <!-- <link rel="stylesheet" href="/src/CSS/darkTheme/body.css">
            <link rel="stylesheet" href="/src/CSS/darkTheme/navbar.css"> -->
        <?php //endif ?>
    <?php endif ?>

    <link rel="stylesheet" href="/src/CSS/selectize/selectize.css">
    <link rel="stylesheet" href="/src/CSS/datatables/datatables.css">

    <link type="text/css" rel="stylesheet" href="/src/CSS/responsive.css">

    <link rel="icon" href="/src/icon/print.png">

    <title><?= $title ?? 'Sapollon' ?></title>
</head>

<body>
    <nav>
        <div class="nav_header text-center">
            <i class="fa-solid fa-print"></i>
            <h3>Sapollon</h3>
        </div>

        <?php if (Driver::estConnecte()) : ?>
            <hr class="text-white">

            <ul class="middle-links">
                <a href="/" class="link"><i class="fa-solid fa-house"></i> <span class="mx-2">Accueil</span></a>

                <li class="hassubmenu mt-3 mb-3">
                    <a href="/menuCopieur" class="link"><i class="fa-solid fa-print"></i> <span class="mx-2">Suivi des Copieurs</span></a>
                    <ul class="container_submenu">
                        <a href="<?= $router->url('list_machines') ?>" class="submenu"><i class="fa-solid fa-list"></i> <span class="mx-2">Liste</span></a>
                        <a href="<?= $router->url('machines_area') ?>" class="submenu"><i class="fa-solid fa-location-dot"></i> <span class="mx-2">Du périmètre</span></a>
                        <a href="<?= $router->url('view_add_machine') ?>" class="submenu"><i class="fa-solid fa-pen"></i> <span class="mx-2">Ajouter</span></a>
                        <a href="<?= $router->url('list_machines_without_owner') ?>" class="submenu"><i class="fa-solid fa-user-slash"></i> <span class="mx-2">Sans Responsable</span></a>
                        <a href="<?= $router->url('list_machines_without_counter_3_months') ?>" class="submenu"><i class="fa-solid fa-user-slash"></i> <span class="mx-2">Sans Relevé depuis 3 mois</span></a>
                    </ul>
                </li>

                <li class="hassubmenu">
                    <a href="/menuCompteurs" class="link"><i class="fa-solid fa-file"></i> <span class="mx-2">Suivi des Compteurs</span></a>
                    <ul class="container_submenu">
                        <a href="<?= $router->url('list_counters') ?>" class="submenu"><i class="fa-solid fa-list"></i> <span class="mx-2">Liste</span></a>
                        <a href="<?= $router->url('counters_area') ?>" class="submenu"><i class="fa-solid fa-location-dot"></i> <span class="mx-2">Du périmètre</span></a>
                    </ul>
                </li>

            </ul>

            <ul class="bottom-links">
                <li>
                    <a href="<?= $router->url('theme') ?>" class="link">
                        <?php //if (User::getTheme() === 'clair') : ?>
                            <i class="fa-solid fa-moon"></i>
                            <span class="mx-2">Sombre</span>
                        <?php //else : ?>
                            <i class="fa-solid fa-lightbulb"></i>
                            <span class="mx-2">Clair</span>
                        <?php //endif ?>
                    </a>
                </li>
                <li><a href="compte" class="link"><i class="fa-solid fa-user"></i> <span class="mx-2">Mon Compte</span></a></li>
                <li>
                    <form action="deconnexion" method="post">
                        <button type="submit" class="link logout"><i class="fa-solid fa-right-from-bracket"></i> <span class="mx-2"> Deconnexion</button>
                    </form>
                </li>
            </ul>

        <?php endif ?>
    </nav>

    <div class="body_content">
        <!-- <div id="myModal" class="Modal is-hidden is-visuallyHidden">
            <div class="Modal-content">
                <span id="closeModal" class="Close">&times;</span>
                <p>Simple Modal</p>
            </div>
        </div> -->

        <span class="text-primary mx-3"><a href="<?= $router->url('home') ?>"><i class="fa-solid fa-house"></i></a> / <?= trim($_SERVER['REQUEST_URI'], '/') ?></span>

        <?= $content ?? '' ?>
    </div>

    <script src="/src/JS/utils/jquery.js"></script>
    <script src="/src/JS/utils/selectize.js"></script>
    <script src="/src/JS/utils/datatables.js"></script>
    <script src="/src/JS/main.js"></script>
    <script>
    </script>
</body>

</html>