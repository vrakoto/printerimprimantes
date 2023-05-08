<?php
$title = "Menu Responsables";
?>

<div class="container">
    <h1 class="mt-5 mb-5 text-center">Menu Administration</h1>
    <div class="d-flex justify-content-evenly flex-wrap">
        <?= menu($router->url('list_owners'), ['fa-solid fa-list mx-2', 'fa-solid fa-users'], 'Liste des Responsables') ?>
        <?= menu($router->url('owners_area'), ['fa-solid fa-users mx-2', 'fa-solid fa-location-dot'], 'Responsables de mon périmètre') ?>
        <?= menu($router->url('view_users_area'), ['fa-solid fa-file mx-2', 'fa-solid fa-user'], 'Gestion des utilisateurs') ?>
    </div>
</div>