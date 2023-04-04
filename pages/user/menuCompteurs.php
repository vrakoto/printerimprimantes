<div class="container mt-5">
    <h1 class="mb-5 text-center">Menu Compteurs</h1>
    <div class="d-flex justify-content-evenly flex-wrap">
        <?= link_list_counters($router->url('list_counters')) ?>
        <?= link_counters_area($router->url('counters_area')) ?>
    </div>
</div>