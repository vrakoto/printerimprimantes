<?php
$title = "Menu Copieurs";
?>
<div class="container mt-5">
    <h1 class="mb-5 text-center">Menu Copieurs</h1>
    <div class="d-flex justify-content-evenly flex-wrap">
        <?= link_list_machines($router->url('list_machines')) ?>
        <?= link_machinesInMyArea($router->url('machines_area')) ?>
        <?= link_machines_without_counter_3_months($router->url('list_machines_without_counter_3_months')) ?>
    </div>
</div>