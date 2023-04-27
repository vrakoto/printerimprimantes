<?php
use App\Imprimante;
?>

<div class="container mt-5">
    <h1>Liste des copieurs sans relevés pour ce <?= 'T' . $current_quarter = ceil(date('n') / 3) ?> 2023</h1>

    <div class="mt-5 mb-0">
        <span class="mt-2" id="export-csv"></span>
        <span class="mt-2" id="export-excel"></span>
        <span id="export-pdf"></span>
    </div>
    
    <hr class="mt-4">

    <form class="row g-3 align-items-center mb-2" id="form_search">
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

    <hr>

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

    <table id="table_imprimantes" class="table table-striped table-bordered personalTable" data-table="getImprimantesSansReleve3Mois">
        <?= Imprimante::ChampsCopieur() ?>
        <tbody></tbody>
    </table>
</div>