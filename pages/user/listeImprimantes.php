<?php
use App\Imprimante;
$title = "Liste des machines";
?>

<div class="container mt-5">
    <h1>Liste des Copieurs</h1>
    
    <hr class="mt-5 mb-0">

    <div class="mt-1 row g-3 align-items-center mb-2">
        <div class="col-auto">
            <label for="table_search_copieurs" class="col-form-label">Rechercher un copieur</label>
        </div>
        <div class="col-auto">
            <input type="text" name="num_serie" class="form-control" id="table_search_copieurs" placeholder="Insérer son numéro de série">
        </div>
    </div>

    <div class="row g-3 align-items-center mt-1">
        <div class="col-auto">
            <label for="table_imprimantes_select_nb_elements_par_pages">Nombre de résultats par page:</label>
        </div>
        <div class="col-auto">
            <select class="form-select" id="table_imprimantes_select_nb_elements_par_pages">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>

    <hr>

    <table id="table_imprimantes" class="table table-striped personalTable">
        <?= Imprimante::ChampsCopieur() ?>
        <tbody></tbody>
    </table>
</div>

<script defer>
    imprimante('/getImprimantes');
</script>