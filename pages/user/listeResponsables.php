<?php
use App\UsersCopieurs;
?>

<div class="container mt-5">

    <h1>Liste des Responsables de toutes les BdD</h1>

    <form id="form_search_users_copieurs" class="mt-5 mb-3 row g-3 align-items-center">
        <div class="col-auto">
            <label for="input_search_users_copieurs" class="col-form-label">Rechercher par (numéro de série ou grade,nom,prénom) :</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="input_search_users_copieurs" name="num_serie" placeholder="Saisir un élement">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
    </form>

    <table id="table_users_copieurs" class="table table-striped table-bordered personalTable">
        <?= UsersCopieurs::ChampUsersCopieurs() ?>
        <tbody></tbody>
    </table>
</div>

<script defer>
    users_copieurs('/getListeResponsables')
</script>