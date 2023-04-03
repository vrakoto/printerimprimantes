<?php
use App\UsersCopieurs;
?>

<div class="container">

    <div class="mt-5 row g-3 align-items-center">
        <div class="col-auto">
            <label for="table_search_users_copieurs" class="col-form-label">Rechercher par (numéro de série ou grade,nom,prénom) :</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="table_search_users_copieurs" name="num_serie" placeholder="Saisir un élement">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
    </div>

    <table id="table_users_copieurs" class="table table-striped personalTable">
        <?= UsersCopieurs::ChampUsersCopieurs() ?>
        <tbody></tbody>
    </table>
</div>

<script defer>
    users_copieurs('/getListeResponsables')
</script>