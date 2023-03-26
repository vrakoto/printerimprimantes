<div class="container">
    <h1 class="mt-5 mb-5 text-center">Menu Compteurs</h1>
    <div class="d-flex justify-content-between flex-wrap">
        <?= link_counters_area($router->url('counters_area')) ?>
        <?= link_list_counters($router->url('list_counters')) ?>
        <?= link_add_counter($router->url('add_counter')) ?>
    </div>
</div>