<?php

use App\BdD;
use App\Imprimante;
$jsfile = 'listeCopieurs';
$title = "Liste des machines";
?>

<div class="d-flex justify-content-between container mt-5" id="header">
    <div>
        <h1>Liste des copieurs</h1>
        <div id="message"></div>

        <div class="mt-5">
            <span id="export-csv"></span>
            <button id="advanced_search" class="mx-3 btn btn-primary"><i class="fa-solid fa-filter"></i> Recherche avancées</button>
            <button id="advanced_search" class="mx-1 btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-table"></i> Affichage des colonnes</button>
        </div>

        <form class="mt-3 row align-items-center mb-2" id="form_search">
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
    <table id="table_imprimantes" class="table table-striped table-bordered personalTable" data-table="getImprimantes">
        <?= Imprimante::ChampsCopieur() ?>
        <tbody></tbody>
    </table>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="modal-editReleve" method="POST">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Affichage des colonnes</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <?= checkboxColumns() ?>
            </div>
        </form>
    </div>
</div>