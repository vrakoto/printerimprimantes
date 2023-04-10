<?php
use App\User;
use App\UserLevels;
?>

<div class="container mt-5">

    <h1>Gestion des utilisateurs</h1>
    <div id="message"></div>

    <button class="btn btn-primary mt-3" id="btn_toggle_input_create_user" onclick="toggle_input_create_user(this)">Ajouter un utilisateur</button>

    <form method="post" id="form_create_user" class="d-none">
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>Grade Prénom Nom</th>
                    <th>Courriel</th>
                    <th>Rôle</th>
                    <th>Mot de passe</th>
                    <th>Unité</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <button type="submit" title="Valider la saisie" class="btn btn-primary"><i class="fa-solid fa-check"></i></button>
                    </td>
                    <td><a title="Annuler la saisie" class="btn btn-danger" onclick="cancelCreateUser(this)"><i class="fa-solid fa-xmark"></i></a></td>
                    <td><input name="gpn" type="text" class="form-control" id="gpn" placeholder="Grade prénom nom" required></td>
                    <td><input name="courriel" type="text" class="form-control" id="courriel" placeholder="Courriel" required></td>
                    <td>
                        <select class="selectize w-100" name="role" id="role" required>
                            <?php foreach (UserLevels::getTable() as $niveau) : $nivID = (int)$niveau['userlevelid']; $nivName = htmlentities($niveau['userlevelname'])  ?>
                                <option value="<?= $nivID ?>"><?= $nivName ?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <td><input name="mdp" type="password" class="form-control" id="mdp" placeholder="Mot de passe" required></td>
                    <td><input name="unite" type="text" class="form-control" id="unite" placeholder="Unité" required></td>
                </tr>
            </tbody>
        </table>
    </form>

    <hr class="mt-5 mb-3">

    <form id="form_search_users_area" class="row g-3 align-items-center">
        <div class="col-auto">
            <label for="input_search_users_area" class="col-form-label">Rechercher par (grade, prénom ou nom) :</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="input_search_users_area" name="gpn" placeholder="Saisir un élement">
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

    <hr class="">
    <span class="mt-2" id="export-csv"></span>
    <span class="mt-2" id="export-excel"></span>
    <span id="export-pdf"></span>

    <table id="table_users_area" class="table table-striped table-bordered personalTable" data-table="gestion_utilisateurs">
        <?= User::getChampsUser() ?>
        <tbody></tbody>
    </table>
</div>