<h3 class="text-center">Copieurs</h3>
<div class="d-flex justify-content-around flex-wrap">
    <?= link_list_machines($router->url('list_machines')) ?>
    <?= link_machinesInMyArea($router->url('machines_area')) ?>
    <?= link_add_machine($router->url('add_machine')) ?>
    <?= link_machines_without_owner($router->url('list_machines_without_owner')) ?>
    <?= link_machines_without_counter_3_months($router->url('list_machines_without_counter_3_months')) ?>
</div>

<br>

<hr>

<h3 class="text-center mt-5">Compteurs</h3>
<div class="d-flex justify-content-around flex-wrap">
    <?= link_counters_area($router->url('counters_area')) ?>
    <?= link_list_counters($router->url('list_counters')) ?>
</div>