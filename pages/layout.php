<?php
use App\Driver;
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

                <div class="mt-3"></div>

                <?= addLink('Suivi des copieurs', 'menu_machine', 'fa-solid fa-print', $router, $match,
                    [
                        'list_machines' => ['icon' => 'fa-solid fa-list', 'title' => 'Liste'],
                        'machines_area' => ['icon' => 'fa-solid fa-location-dot', 'title' => 'De mon périmètre'],
                        'list_machines_without_counter_3_months' => ['icon' => 'fa-solid fa-user-slash', 'title' => 'Sans relevé ce trimestre']
                    ], $router, $match)
                ?>

                <div class="mt-3"></div>
                
                <?= addLink('Suivi des compteurs', 'menu_machine_counters', 'fa-solid fa-file', $router, $match,
                    [
                        'list_counters' => ['icon' => 'fa-solid fa-list', 'title' => 'Liste'],
                        'counters_area' => ['icon' => 'fa-solid fa-location-dot', 'title' => 'De mon périmètre'],
                    ], $router, $match)
                ?>

                <div class="mt-3"></div>

                <?= addLink('Suivi des pannes', 'menu_pannes', 'fa-solid fa-virus', $router, $match,
                    [
                        'list_pannes' => ['icon' => 'fa-solid fa-list', 'title' => 'Liste'],
                        'pannes_area' => ['icon' => 'fa-solid fa-location-dot', 'title' => 'Mes pannes'],
                    ], $router, $match)
                ?>

                <div class="mt-3"></div>

                <?= addLink('Administration', 'menu_administration', 'fa-solid fa-users', $router, $match,
                    [
                        'list_owners' => ['icon' => 'fa-solid fa-list', 'title' => 'Liste'],
                        'owners_area' => ['icon' => 'fa-solid fa-location-dot', 'title' => 'De mon périmètre'],
                        'view_users_area' => ['icon' => 'fa-solid fa-user-tag', 'title' => 'Gestion des utilisateurs'],
                    ])
                ?>

            </ul>

            <hr class="text-white">

            <ul class="bottom-links">
                <?= addLink('Foire Aux Questions', 'faq', 'fa-solid fa-question', $router, $match) ?>
                <?= addLink('Mon Compte', 'my_account', 'fa-solid fa-user', $router, $match) ?>
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