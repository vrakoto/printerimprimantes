<h3 class="text-center mb-5">Copieurs</h3>
<div class="d-flex justify-content-around flex-wrap">
    <?= link_machinesInMyArea($router->url('machines_area')) ?>
    <?= link_list_machines($router->url('list_machines')) ?>
    <?= link_add_machine($router->url('add_machine')) ?>
</div>

<br>

<hr>

<h3 class="text-center mb-5">Compteurs</h3>
<div class="d-flex justify-content-around flex-wrap">
    <?= link_counters_area($router->url('counters_area')) ?>
    <?= link_list_counters($router->url('list_counters')) ?>
    <?php // link_add_counter($router->url('add_counter')) ?>
</div>