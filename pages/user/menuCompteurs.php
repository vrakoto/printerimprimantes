<?php
$title = "Menu Compteurs";
?>
<div class="container mt-5">
    <h1 class="mb-5 text-center">Menu Compteurs</h1>
    <div class="d-flex justify-content-evenly flex-wrap">
        <?= menu($router->url('list_counters'), ['fa-solid fa-book mx-2', 'fa-solid fa-list'], 'Liste des Compteurs') ?>
        <?= menu($router->url('counters_area'), ['fa-solid fa-book mx-2', 'fa-solid fa-location-dot'], 'Compteurs de mon périmètre') ?>
    </div>
</div>