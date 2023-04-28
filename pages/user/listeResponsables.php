<?php
use App\UsersCopieurs;
?>

<div class="container mt-5">

    <h1>Liste des Responsables de toutes les BdD</h1>

    <hr class="mt-5 mb-3">

    <form id="form_search" class="row g-3 align-items-center">
        <div class="col-auto">
            <label for="table_search" class="col-form-label">Rechercher par (numéro de série ou grade,nom,prénom) :</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="table_search" name="num_serie" placeholder="Saisir un élement">
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

    <hr>

    <table id="table_users_copieurs" class="table table-striped table-bordered mt-5 personalTable" data-table="getListeResponsables">
        <?= UsersCopieurs::ChampUsersCopieurs() ?>
        <tbody></tbody>
    </table>
</div>