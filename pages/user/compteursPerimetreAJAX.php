<?php

use App\Compteur;
use App\Imprimante;
use App\User;

$lesNumeros = User::copieursPerimetre();
$role = User::getRole();
$jsfile = 'compteursPerimetre';
$title = "Compteurs du périmètre"
?>

<div class="container" id="container">

    <h1 class="mt-5 mb-4">Compteurs de mon périmètre</h1>
    <div id="message"></div>
    <br>

    <?php if (count(User::copieursPerimetre()) > 0): ?>
        <button class="btn btn-primary" id="toggle_inputs_releve">Ajouter un relevé</button>
        <a href="<?= $router->url('machines_area') ?>" class="btn btn-secondary">Ajouter un copieur dans mon périmètre</a>
    <?php else: ?>
        <?php if (User::getRole() === 2): ?>
            <h5>Aucune machine de votre BdD n'a été enregistrée.</h5>
        <?php else: ?>
            <h5><i class="fa-solid fa-triangle-exclamation"></i> Aucune machine n'est attribuée à votre compte.</h5>
            <a href="<?= $router->url('machines_area') ?>" class="mb-4 btn btn-secondary">Ajouter un copieur dans mon périmètre</a>
        <?php endif ?>
    <?php endif ?>

    
<?php if (count(User::copieursPerimetre()) > 0) : ?>
    <form method="post" id="form_add_counter" class="d-none mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>N° Série</th>
                    <th>Date de relevé</th>
                    <th>112 Total</th>
                    <th>113 Total</th>
                    <th>122 Total</th>
                    <th>123 Total</th>
                    <th>Type de relevé</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <button type="submit" title="Valider la saisie" class="btn btn-primary"><i class="fa-solid fa-check"></i></button>
                    </td>
                    <td><a title="Annuler la saisie" class="btn btn-danger" id="cancel_input_releve"><i class="fa-solid fa-xmark"></i></a></td>
                    <td>
                        <select class="selectize w-100" name="num_serie" id="num_serie" required>
                            <?php foreach ($lesNumeros as $numero) : $num = htmlentities($numero[Imprimante::getChamps('champ_num_serie')]) ?>
                                <option value="<?= $num ?>"><?= $num ?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <td><input name="date_releve" type="text" class="form-control" type="text" id="date_releve" placeholder="JJ-MM-AAAA" required></td>
                    <td><input name="112_total" type="number" class="form-control" type="number" min="0" placeholder="Saisir 112 Total " id="total_112"></td>
                    <td><input name="113_total" type="number" class="form-control" type="number" min="0" placeholder="Saisir 113 Total " id="total_113"></td>
                    <td><input name="122_total" type="number" class="form-control" type="number" min="0" placeholder="Saisir 122 Total " id="total_122"></td>
                    <td><input name="123_total" type="number" class="form-control" type="number" min="0" placeholder="Saisir 123 Total " id="total_123"></td>
                    <td><input name="type_releve" class="form-control" type="text" id="type_releve" value="MANUEL" required></td>
                </tr>
            </tbody>
        </table>

        <a class="btn btn-secondary" href="<?= $router->url('importCompteurs') ?>">Importer des relevés via un fichier CSV</a>
    </form>

    <style>
        form input {
            width: 110px !important;
        }
    </style>

    <hr class="mt-5">

    <form class="row g-3 align-items-center" id="form_search">
        <div class="col-auto">
            <label for="table_search" class="col-form-label">Rechercher par numéro de série</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="table_search" name="num_serie" placeholder="Saisir un numéro de série">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
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

    <hr class="mb-3">

    <button id="columns_plus" class="btn btn-primary">Afficher + d'infos</button>
    <span class="mt-2" id="export-csv"></span>
</div>

<div id="large_table" class="container mt-4">
    <table id="table_compteurs" class="table table-striped table-bordered personalTable table_compteurs_perimetre" data-table="getCompteursPerimetre">
        <?= Compteur::ChampsCompteur(true) ?>
        <tbody></tbody>
    </table>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="modal-editReleve" method="POST">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modification de relevé de compteur</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 alert alert-danger d-none" id="modal-message"></div>
                <div class="row mb-3">
                    <label for="num_serie" class="col-sm-4 label">N° de Série :</label>
                    <div class="col">
                        <p class="form-text" id="modal-num_serie"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4">Date de relevé :</label>
                    <div class="col">
                        <p class="form-text" id="modal-date_releve"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="modal-112_total" class="col-sm-3 form-label">112 Total :</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" id="modal-112_total">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="modal-113_total" class="col-sm-3 form-label">113 Total :</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" id="modal-113_total">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="modal-122_total" class="col-sm-3 form-label">122 Total :</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" id="modal-122_total">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="modal-123_total" class="col-sm-3 form-label">123 Total :</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" id="modal-123_total">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="modal-type_releve" class="col-sm-3 form-label">Type de relevé :</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="modal-type_releve">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Modifier</button>
            </div>
        </form>
    </div>
</div>
<?php endif ?>