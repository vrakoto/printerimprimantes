<div class="container">
    <h1 class="mt-5 mb-5 text-center">Menu Copieurs</h1>
    <div class="d-flex justify-content-between flex-wrap">
        <?= link_machinesInMyArea($router->url('machines_area')) ?>
        <?= link_list_machines($router->url('list_machines')) ?>
        <?= link_add_machine($router->url('add_machine')) ?>
    </div>
</div>