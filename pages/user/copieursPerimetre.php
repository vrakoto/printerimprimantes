<?php
use App\Imprimante;
use App\User;

$title = "Copieurs Périmètre";
?>

<div class="container mt-5">
    <h1>Copieurs de mon périmètre</h1>
    <div id="message"></div>
    
    <div class="mt-5 mb-0">
        <?php if (User::getRole() !== 2): ?>
            <a href="<?= $router->url('view_add_machine_area') ?>" class="btn btn-success" title="Ajouter un copieur dans mon périmètre">Ajouter un copieur dans mon périmètre</a>
        <?php endif ?>

        <span id="export-csv"></span>
        <span id="export-excel"></span>
    </div>

    <hr class="mt-5 mb-0">

    <form class="mt-2 row g-3 align-items-center mb-2" id="form_search">
        <div class="col-auto">
            <label for="table_search" class="col-form-label">Rechercher un copieur</label>
        </div>
        <div class="col-auto">
            <input type="text" name="num_serie" class="form-control" id="table_search" placeholder="Insérer son numéro de série">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary" title="Rechercher le copieur saisie"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
    </form>

    <div class="row g-3 align-items-center mt-1">
        <div class="col-auto">
            <label for="table_select_nb_elements_par_pages">Nombre de résultats par page:</label>
        </div>
        <div class="col-auto">
            <select class="form-select" id="table_select_nb_elements_par_pages">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>

    <hr class="mt-3">

    <button class="btn btn-primary" id="display_menu_colonnes"></button>

    <div class="mt-4 mx-5 d-none" id="lesCheckbox">
        <?php $i = 0; foreach (colonnes(Imprimante::ChampsCopieur()) as $id => $values): $i ++; $id_protected = htmlentities($id) ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="<?= $id_protected ?>" id="<?= $id_protected ?>" <?= ($i <= 6) ? "checked" : "" ?>>
                <label class="form-check-label" for="<?= $id_protected ?>"><?= htmlentities($values) ?></label>
            </div>
        <?php endforeach ?>
    </div>

    <div class="mb-5"></div>

</label>
</div>

<div id="large_table" class="container">
    <table id="table_imprimantes" class="table table-striped table-bordered personalTable table-responsive table_imprimantes_perimetre" data-table="getImprimantesPerimetre">
        <?= Imprimante::ChampsCopieur() ?>
        <tbody></tbody>
    </table>
</div>