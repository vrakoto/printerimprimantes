<?php
use App\Imprimante;
?>

<div class="container mt-5">
    <h1>Liste des copieurs sans relevés pour ce <?= 'T' . $current_quarter = ceil(date('n') / 3) ?> 2023</h1>
    <div id="export-pdf"></div>
    <div class="mt-2" id="export-csv"></div>
    <div class="mt-2" id="export-excel"></div>

    <form class="mt-4 row g-3 align-items-center mb-2" id="form_search">
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

    <table id="table_imprimantes" class="table table-striped table-bordered personalTable" data-table="getImprimantesSansReleve3Mois">
        <?= Imprimante::ChampsCopieur() ?>
        <tbody></tbody>
    </table>
</div>