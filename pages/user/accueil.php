<?php
$title = "Accueil"
?>

<h3 class="text-center mt-5">Copieurs</h3>
<div class="d-flex justify-content-around flex-wrap">
    <?= menu($router->url('list_machines'), ['fa-solid fa-print mx-2', 'fa-solid fa-list'], 'Liste des Copieurs') ?>
    <?= menu($router->url('machines_area'), ['fa-solid fa-print mx-2', 'fa-solid fa-location-dot'], 'Copieurs de mon périmètre') ?>
    <?= menu($router->url('list_machines_without_counter_3_months'), ['fa-solid fa-book mx-2', 'fa-sharp fa-solid fa-circle-exclamation'], 'Copieurs Sans Relevé ce trimestre') ?>
</div>

<br>

<hr>

<h3 class="text-center mt-5">Compteurs</h3>
<div class="d-flex justify-content-around flex-wrap">
    <?= menu($router->url('list_counters'), ['fa-solid fa-book mx-2', 'fa-solid fa-list'], 'Liste des Compteurs') ?>
    <?= menu($router->url('counters_area'), ['fa-solid fa-book mx-2', 'fa-solid fa-location-dot'], 'Compteurs de mon périmètre') ?>
</div>

<hr class="mt-5">

<h3 class="text-center mt-5">Administration</h3>
<div class="d-flex justify-content-around flex-wrap">
    <?= menu($router->url('list_owners'), ['fa-solid fa-list mx-2', 'fa-solid fa-users'], 'Liste des Responsables') ?>
    <?= menu($router->url('owners_area'), ['fa-solid fa-users mx-2', 'fa-solid fa-location-dot'], 'Responsables de mon périmètre') ?>
    <?= menu($router->url('view_users_area'), ['fa-solid fa-file', 'fa-solid fa-user'], 'Gestion des utilisateurs') ?>
</div>