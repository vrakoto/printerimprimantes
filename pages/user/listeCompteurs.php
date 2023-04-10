<?php
use App\Compteur;
$title = "Liste total des compteurs";
?>

<div class="container mt-5">
    <h1>Liste des compteurs</h1>

    <hr class="mt-5">

    <form class="row g-3 align-items-center" id="form_search_compteurs">
        <div class="col-auto">
            <label for="table_search_compteurs" class="col-form-label">Rechercher par numéro de série</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="table_search_compteurs" name="num_serie" placeholder="Saisir un numéro de série">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
    </form>

    <div class="row g-3 align-items-center mt-3">
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

    <hr class="mb-3">

    <span class="mt-2" id="export-csv"></span>
    <span class="mt-2" id="export-excel"></span>
    <span id="export-pdf"></span>

    <table id="table_compteurs" class="table table-striped table-bordered personalTable" data-table="getCompteurs">
        <?= Compteur::ChampsCompteur() ?>
        <tbody></tbody>
    </table>
</div>