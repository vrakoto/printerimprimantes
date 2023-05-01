<?php
use App\Driver;

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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">
    <link rel="stylesheet" href="/src/CSS/selectize/selectize.css">
    <link rel="stylesheet" href="/src/CSS/datatables/datatables.min.css">

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
                <a href="<?= $router->url('home') ?>" class="link <?= $match['name'] === "home" ? " active" : "" ?>"><i class="fa-solid fa-house"></i> <span class="mx-2">Accueil</span></a>

                <li class="mt-3">
                    <a href="<?= $router->url('menu_machine') ?>" class="link <?= $match['name'] === "menu_machine" ? " active" : "" ?>"><i class="fa-solid fa-print"></i> <span class="mx-2">Suivi des Copieurs</span></a>
                    <ul class="container_submenu">
                        <a href="<?= $router->url('list_machines') ?>" class="submenu <?= $match['name'] === "list_machines" ? " active" : "" ?>"><i class="fa-solid fa-list"></i> <span class="mx-2">Liste</span></a>
                        <a href="<?= $router->url('machines_area') ?>" class="submenu <?= $match['name'] === "machines_area" ? " active" : "" ?>"><i class="fa-solid fa-location-dot"></i> <span class="mx-2">De mon périmètre</span></a>
                        <a href="<?= $router->url('list_machines_without_counter_3_months') ?>" class="submenu <?= $match['name'] === "list_machines_without_counter_3_months" ? " active" : "" ?>"><i class="fa-solid fa-user-slash"></i> <span class="mx-2">Sans Relevé depuis 3 mois</span></a>
                    </ul>
                </li>

                <li class="mt-3 mb-3">
                    <a href="<?= $router->url('menu_machine_counters') ?>" class="link <?= $match['name'] === "menu_machine_counters" ? " active" : "" ?>"><i class="fa-solid fa-file"></i> <span class="mx-2">Suivi des Compteurs</span></a>
                    <ul class="container_submenu">
                        <a href="<?= $router->url('list_counters') ?>" class="submenu <?= $match['name'] === "list_counters" ? " active" : "" ?>"><i class="fa-solid fa-list"></i> <span class="mx-2">Liste</span></a>
                        <a href="<?= $router->url('counters_area') ?>" class="submenu <?= $match['name'] === "counters_area" ? " active" : "" ?>"><i class="fa-solid fa-location-dot"></i> <span class="mx-2">De mon périmètre</span></a>
                    </ul>
                </li>

                <li>
                    <a href="<?= $router->url('menu_machines_owners') ?>" class="link <?= $match['name'] === "menu_machines_owners" ? " active" : "" ?>"><i class="fa-solid fa-users"></i> <span class="mx-2">Administration</span></a>
                    <ul class="container_submenu">
                        <a href="<?= $router->url('list_owners') ?>" class="submenu <?= $match['name'] === "list_owners" ? " active" : "" ?>"><i class="fa-solid fa-list"></i> <span class="mx-2">Liste des Responsables</span></a>
                        <a href="<?= $router->url('owners_area') ?>" class="submenu <?= $match['name'] === "owners_area" ? " active" : "" ?>"><i class="fa-solid fa-location-dot"></i> <span class="mx-2">Responsables du périmètre</span></a>
                        <a href="<?= $router->url('view_users_area') ?>" class="submenu <?= $match['name'] === "view_users_area" ? " active" : "" ?>"><i class="fa-solid fa-user-tag"></i> <span class="mx-2">Gestion des utilisateurs</span></a>
                    </ul>
                </li>

            </ul>

            <hr class="text-white">

            <ul class="bottom-links">
                <li><a href="<?= $router->url('my_account') ?>" class="link"><i class="fa-solid fa-user"></i> <span class="mx-2">Mon Compte</span></a></li>
                <li>
                    <form action="<?= $router->url('logout') ?>" method="post">
                        <button type="submit" class="link logout"><i class="fa-solid fa-right-from-bracket"></i> <span class="mx-2"> Deconnexion</button>
                    </form>
                </li>
            </ul>

        <?php endif ?>
    </nav>

    <div class="body_content">
        <span class="text-primary mx-3"><a href="<?= $router->url('home') ?>"><i class="fa-solid fa-house"></i></a> / <?= trim($_SERVER['REQUEST_URI'], '/') ?></span>

        <?= $content ?? '' ?>
    </div>

    <?php if (Driver::estConnecte()) : ?>

        <script src="/src/JS/utils/jquery.js"></script>
        <script src="/src/JS/utils/popper.min.js"></script>
        <script src="/src/JS/utils/bootstrap.min.js"></script>
        <script src="/src/JS/utils/selectize.js"></script>
        
        <script src="/src/JS/utils/datatables.min.js"></script>

        <script src="/src/JS/utils/jquery_contextMenu.js"></script>
        <script src="/src/JS/utils/jquery_ui_position.js"></script>
        
        <script src="/src/JS/commun.js"></script>

        <?php if (isset($jsfile)): ?>
            <script src="/src/JS/<?= $jsfile ?>.js"></script>
        <?php endif ?>
        
    <?php endif ?>
        
        <script src="/src/JS/main.js"></script>

</body>
</html>