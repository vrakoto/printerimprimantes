<?php
use App\Imprimante;
use App\Corsic;
use App\User;

$title = "Copieurs Périmètre";
// $imprimante = new Imprimante;

/* if (User::getRole() === 2) {
    $lesImprimantes = $imprimante->getImprimantesParBDD(User::getBDD());
} else if (User::getRole() === 1 || User::getRole() === 3) {
    $lesImprimantes = Corsic::copieursPerimetre();
} */
$lesImprimantes = Corsic::copieursPerimetre();
?>

<div class="container">
    <h1>Copieurs du périmètre</h1>
    
    <div class="mt-5">

        <?php if (count(Corsic::copieursPerimetrePasDansMaListe()) > 0): ?>
            <a href="<?= $router->url('add_machine_area') ?>" class="btn btn-success" title="Ajouter un copieur existant dans mon périmètre">Ajouter un copieur dans mon périmètre</a>
        <?php endif ?>

        <?php if (count($lesImprimantes) > 0): ?>
            <a href="<?= $router->url('remove_machine_area') ?>" class="btn btn-warning" title="Retirer un copieur de mon périmètre">Retirer un copieur de mon périmètre</a>
        <?php endif ?>

        <?php if (User::getRole() === 2): ?>
            <a href="<?= $router->url('add_machine') ?>" class="btn btn-primary" title="Inscrire une nouvelle machine dans Sapollon">Inscrire un nouveau copieur</a>
        <?php endif ?>
    </div>

    <hr>
    <div class="mt-4 row g-3 align-items-center mb-2">
        <div class="col-auto">
            <label for="table_search" class="col-form-label">Rechercher un copieur</label>
        </div>
        <div class="col-auto">
            <input type="text" name="num_serie" class="form-control" id="table_search" placeholder="Insérer son numéro de série">
        </div>
    </div>

    <table id="table_imprimantes" class="table table-striped personalTable" style="width:100%">
        <?= Imprimante::ChampsCopieur() ?>
        <tbody>
            <?php foreach ($lesImprimantes as $imprimante): ?>
                <?= Imprimante::ValeursCopieur($imprimante) ?>
            <?php endforeach ?>
        </tbody>
    </table>


    <style>
        #modaldata tbody tr>td:last-of-type {
            display: none;
        }
    </style>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="modaldata" class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Office</th>
                                <th>Age</th>
                                <th>Start date</th>
                                <th>Salary</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr></tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>


</div>