<?php
$title = "Menu Copieurs";
?>
<div class="container mt-5">
    <h1 class="mb-5 text-center">Menu Copieurs</h1>
    <div class="d-flex justify-content-evenly flex-wrap">
        <?= menu($router->url('list_machines'), ['fa-solid fa-print mx-2', 'fa-solid fa-list'], 'Liste des Copieurs') ?>
        <?= menu($router->url('machines_area'), ['fa-solid fa-print mx-2', 'fa-solid fa-location-dot'], 'Copieurs de mon périmètre') ?>
        <?= menu($router->url('list_machines_without_counter_3_months'), ['fa-solid fa-book mx-2', 'fa-sharp fa-solid fa-circle-exclamation'], 'Copieurs Sans Relevé ce trimestre') ?>
    </div>
</div>