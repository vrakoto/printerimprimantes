<?php
use App\Imprimante;
?>

<div class="mt-5">
    <h1>Liste des copieurs sans relevés depuis 3 Mois</h1>

    <div class="mt-4 row g-3 align-items-center mb-2">
        <div class="col-auto">
            <label for="table_search_copieurs" class="col-form-label">Rechercher un copieur</label>
        </div>
        <div class="col-auto">
            <input type="text" name="num_serie" class="form-control" id="table_search_copieurs" placeholder="Insérer son numéro de série">
        </div>
    </div>

    <table id="table_imprimantes" class="table table-striped personalTable">
        <?= Imprimante::ChampsCopieur() ?>
        <tbody></tbody>
    </table>
</div>

<script defer>
    // imprimante('/getImprimantesSansReleve3Mois');
</script>