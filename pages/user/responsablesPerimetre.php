<?php
use App\UsersCopieurs;
?>

<div class="container mt-5">

    <h1>Responsables du périmètre</h1>

    <div class="mt-5 row g-3 align-items-center">
        <div class="col-auto">
            <label for="table_search_users_copieurs" class="col-form-label">Rechercher par (numéro de série ou grade,nom,prénom) :</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="table_search_users_copieurs" name="num_serie" placeholder="Saisir un élement">
        </div>
    </div>

    <div class="mt-5">
        <table id="table_users_copieurs" class="table table-striped table-bordered personalTable">
            <?= UsersCopieurs::ChampUsersCopieurs() ?>
            <tbody></tbody>
        </table>
    </div>
</div>

<script defer>
    users_copieurs('/getResponsablesPerimetre')
</script>