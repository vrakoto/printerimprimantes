<h3 class="text-center mt-5">Copieurs</h3>
<div class="d-flex justify-content-around flex-wrap">
    <?= link_list_machines($router->url('list_machines')) ?>
    <?= link_machinesInMyArea($router->url('machines_area')) ?>
    <?php // link_add_machine($router->url('add_machine')) ?>
    <?= link_machines_without_counter_3_months($router->url('list_machines_without_counter_3_months')) ?>
</div>

<br>

<hr>

<h3 class="text-center mt-5">Compteurs</h3>
<div class="d-flex justify-content-around flex-wrap">
    <?= link_list_counters($router->url('list_counters')) ?>
    <?= link_counters_area($router->url('counters_area')) ?>
</div>

<hr class="mt-5">

<h3 class="text-center mt-5">Administration</h3>
<div class="d-flex justify-content-around flex-wrap">
    <?= link_list_owners($router->url('list_owners')) ?>
    <?= link_ownersInMyArea($router->url('owners_area')) ?>
    <?= link_users_area($router->url('view_users_area')) ?>
</div>