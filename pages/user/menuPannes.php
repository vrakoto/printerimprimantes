<?php
$title = "Menu des Pannes";
?>
<div class="container mt-5">
    <h1 class="mb-5 text-center">Menu Pannes</h1>
    <div class="d-flex justify-content-evenly flex-wrap">
        <?= menu($router->url('list_pannes'), ['fa-solid fa-print mx-2', 'fa-solid fa-list'], 'Liste des Pannes') ?>
        <?= menu($router->url('pannes_area'), ['fa-solid fa-print mx-2', 'fa-solid fa-location-dot'], 'Mes pannes') ?>
    </div>
</div>