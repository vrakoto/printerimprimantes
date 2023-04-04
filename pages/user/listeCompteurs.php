<?php
use App\Compteur;
$title = "Liste total des compteurs";
?>

<div class="container">
    <h1 class="mt-5">Liste des compteurs</h1>

    <div class="mt-5 row g-3 align-items-center">
        <div class="col-auto">
            <label for="table_search_compteurs" class="col-form-label">Rechercher un copieur :</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="table_search_compteurs" name="num_serie" placeholder="Insérer son numéro de série">
        </div>
    </div>

    <div class="row g-3 align-items-center mt-3">
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

    <table id="table_compteurs" class="table table-striped table-bordered personalTable">
        <?= Compteur::ChampsCompteur() ?>
        <tbody></tbody>
    </table>
</div>


<script>
    compteurs('/getCompteurs')
</script>