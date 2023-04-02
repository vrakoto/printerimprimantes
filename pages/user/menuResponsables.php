<div class="container">
    <h1 class="mt-5 mb-5 text-center">Menu Responsables</h1>
    <div class="d-flex justify-content-between flex-wrap">
        <?= link_ownersInMyArea($router->url('owners_area')) ?>
        <?= link_list_owners($router->url('list_owners')) ?>
    </div>
</div>