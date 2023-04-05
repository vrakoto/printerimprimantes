<div class="container">
    <h1 class="mt-5 mb-5 text-center">Menu Administration</h1>
    <div class="d-flex justify-content-evenly flex-wrap">
        <?= link_list_owners($router->url('list_owners')) ?>
        <?= link_ownersInMyArea($router->url('owners_area')) ?>
    </div>
</div>