<?php
use App\User;
?>

<div class="container mt-5">

    <h1>Gestion des utilisateurs</h1>

    <form id="form_search_users_area" class="mt-5 mb-3 row g-3 align-items-center">
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

    <div class="mt-5">
        <table id="table_users_area" class="table table-striped table-bordered personalTable">
            <?= User::getChampsUser() ?>
            <tbody></tbody>
        </table>
    </div>
</div>

<script defer>
    gestion_utilisateurs()
</script>