<?php
use App\Imprimante;
use App\User;

$title = "Copieurs Périmètre";

$mesImprimantes = User::copieursPerimetre();
?>

<div class="container mt-5">
    <h1>Copieurs de mon périmètre</h1>
    
    <div class="mt-5 mb-0">
        <?php if (User::getRole() !== 2): ?>
            <a href="<?= $router->url('view_add_machine_area') ?>" class="btn btn-success" title="Ajouter un copieur existant dans mon périmètre">Ajouter un copieur dans mon périmètre</a>

            <?php if (count($mesImprimantes) > 0): ?>
                <a href="<?= $router->url('remove_machine_area') ?>" class="btn btn-warning" title="Retirer un copieur de mon périmètre">Retirer un copieur de mon périmètre</a>
            <?php endif ?>

        <?php else: ?>
            <a href="<?= $router->url('add_machine') ?>" class="btn btn-primary" title="Inscrire une nouvelle machine dans Sapollon">Inscrire un nouveau copieur</a>
        <?php endif ?>
    </div>

    <hr class="mt-5 mb-0">

    <form class="mt-4 row g-3 align-items-center mb-2" id="form_search_copieurs">
        <div class="col-auto">
            <label for="table_search_copieurs" class="col-form-label">Rechercher un copieur</label>
        </div>
        <div class="col-auto">
            <input type="text" name="num_serie" class="form-control" id="table_search_copieurs" placeholder="Insérer son numéro de série">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary" title="Rechercher le copieur saisie"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
    </form>

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

    <table id="table_imprimantes" class="table table-striped table-bordered personalTable">
        <?= Imprimante::ChampsCopieur() ?>
        <tbody></tbody>
    </table>
</div>

<script defer>
    imprimante('/getImprimantesPerimetre');
</script>