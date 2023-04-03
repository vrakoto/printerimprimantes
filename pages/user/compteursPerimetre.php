<?php

use App\Compteur;
use App\Imprimante;
use App\User;

$lesNumeros = User::copieursPerimetre();
$hasFormMessage = !empty($_SESSION['message']);
?>

<div class="container" id="container">

    <h1 class="mt-5">Compteurs du périmètre</h1>
    <div id="message"></div>
    <br>

    <?php if (count(User::copieursPerimetre()) > 0) : ?>
        <button class="mb-1 btn btn-primary" id="btn_add_releve" onclick="toggle_inputs_releve(this)">Ajouter un relevé</button>
    <?php elseif (count(User::copieursPerimetre()) <= 0) : ?>
        <div class="mb-3">
            <h5>Vous n'avez aucun copieur dans votre périmètre.</h5>
            <a href="<?= $router->url('add_machine_area') ?>" class="mb-4 btn btn-primary">Ajouter un copieur dans mon périmètre</a>
        </div>
    <?php endif ?>


    <form method="post" id="form_add_counter" class="<?= $hasFormMessage ? "" : "d-none" ?>">
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
                    <td><a title="Annuler la saisie" class="btn btn-danger" onclick="cancelReleve(this)"><i class="fa-solid fa-xmark"></i></a></td>
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
    </form>

    <style>
        form input {
            width: 110px !important;
        }
    </style>

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

    <div class="row g-3 align-items-center mt-1">
        <div class="col-auto">
            <label for="table_compteurs_select_nb_elements_par_pages">Nombre de résultats par page:</label>
        </div>
        <div class="col-auto">
            <select class="form-select" id="table_compteurs_select_nb_elements_par_pages">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>

    <?php if (count(Compteur::getLesRelevesParBDD()) > 0): ?>
        <table id="table_compteurs" class="table table-striped personalTable">
            <?= Compteur::ChampsCompteur() ?>
             <tbody></tbody>
        </table>
    <?php else: ?>
        <h4 class="mt-4 text-center">Aucun relevé</h4>
    <?php endif ?>
</div>

<script>
    compteurs('/getCompteursPerimetre')
</script>