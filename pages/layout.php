<?php
use App\Driver;

// Driver::addLogs("a visité la page '$title'");
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
    <link type="text/css" rel="stylesheet" href="/src/CSS/navbar.css">

    <link href="/src/CSS/select2/select2.css" rel="stylesheet" />
    <link href="/src/CSS/select2/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <link rel="icon" href="/src/icon/print.png">

    <title><?= $title ?? 'Sapollon' ?></title>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-success navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand">Sapollon</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <?php if (Driver::estConnecte()): ?>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="<?= $router->url('home') ?>">Compte</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="<?= $router->url('logs') ?>">Logs</a>
                    </li> -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Suivi des copieurs
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= $router->url('list_machines') ?>"><i class="fa-solid fa-list"></i> Liste des Copieurs</a></li>
                            <li><a class="dropdown-item" href="<?= $router->url('machines_area') ?>"><i class="fa-solid fa-location-dot"></i> Copieurs de mon périmètre</a></li>
                            <div class="dropdown-divider"></div> <!-- Divider -->
                            <li><a class="dropdown-item" href="<?= $router->url('list_machines_without_owner') ?>"><i class="fa-solid fa-xmark"></i> <i class="fa-solid fa-user"></i> Sans Responsable</a></li>
                            <li><a class="dropdown-item" href="<?= $router->url('view_transfert_machine') ?>"><i class="fa-solid fa-arrow-right"></i> <i class="fa-solid fa-truck"></i> Suivi des Transferts</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Suivi des compteurs
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= $router->url('list_counters') ?>"><i class="fa-solid fa-list"></i> Liste des Compteurs</a></li>
                            <li><a class="dropdown-item" href="<?= $router->url('counters_area') ?>"><i class="fa-solid fa-location-dot"></i> Compteurs de mon périmètre</a></li>
                            <div class="dropdown-divider"></div> <!-- Divider -->
                            <li><a class="dropdown-item" href="<?= $router->url('counters_area_t2_2023') ?>"><i class="fa-solid fa-book"></i> Archive T2-2023</a></li>
                            <li><a class="dropdown-item" href="<?= $router->url('list_machines_without_counter_3_months') ?>"><i class="fa-solid fa-xmark"></i> <i class="fa-solid fa-book"></i> Sans relevé ce Trimestre</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Suivi des pannes
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= $router->url('list_pannes') ?>"><i class="fa-solid fa-list"></i> Liste des Pannes</a></li>
                            <li><a class="dropdown-item" href="<?= $router->url('pannes_area') ?>"><i class="fa-solid fa-location-dot"></i> Mes Pannes</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Administration
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= $router->url('view_users_area') ?>"><i class="fa-solid fa-list"></i> Gestion des utilisateurs</a></li>
                            <li><a class="dropdown-item" href="<?= $router->url('list_owners') ?>"><i class="fa-solid fa-user-tag"></i> Liste des Responsables</a></li>
                            <li><a class="dropdown-item" href="<?= $router->url('owners_area') ?>"><i class="fa-solid fa-location-dot"></i> Responsables du périmtre</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex" action="<?= $router->url('logout') ?>" method="post">
                    <button class="btn btn-danger" type="submit">Déconnexion</button>
                </form>
            </div>
            <?php endif ?>
        </div>
    </nav>

    <div class="body_content">
        <?= $content ?? '' ?>
    </div>

    <?php if (Driver::estConnecte()) : ?>

        <script src="/src/JS/utils/jquery.js"></script>
        <script src="/src/JS/utils/popper.min.js"></script>
        <script src="/src/JS/utils/bootstrap.min.js"></script>
        <script src="/src/JS/utils/select2.js"></script>

        <?php if (isset($jsfile)) : ?>
            <script src="/src/JS/<?= $jsfile ?>.js"></script>
        <?php endif ?>

    <?php endif ?>

    <script src="/src/JS/main.js"></script>

</body>

</html>