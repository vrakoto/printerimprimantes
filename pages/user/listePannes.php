<?php
use App\BdD;
use App\Imprimante;
$jsfile = 'listePannes';
$title = "Liste des Pannes";
?>

<div class="d-flex justify-content-between container mt-5" id="header">
    <div>
        <h1>Liste des Pannes</h1>
        <div id="message"></div>

        <div class="mt-5 mb-0">
            <span id="export-csv"></span>
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

        <form class="mt-2 row g-3 align-items-center mb-2" id="form_search_bdd">
            <div class="col-auto">
                <label for="table_search_bdd" class="col-form-label">Filtrer par BdD</label>
            </div>
            <div class="col-sm-3">
                <select class="selectize" name="table_search_bdd" id="table_search_bdd">
                    <?php foreach (BdD::getTousLesBDD() as $bdd): $bdd = htmlentities($bdd['BDD']); ?>
                        <option value="<?= $bdd ?>"><?= $bdd ?></option>
                    <?php endforeach ?>
                </select>
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

        <div class="mb-5"></div>
    </div>
</div>

<div class="container mt-5">
    <table id="table_pannes" class="table table-striped table-bordered personalTable" data-table="getPannes">
        <tbody></tbody>
    </table>
</div>