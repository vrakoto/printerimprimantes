<?php
use App\BdD;
use App\Imprimante;
use App\Users\User;

$title = "Liste des machines";
$bdd_selectionne = User::getBDD();
if (!empty($_GET['bdd'])) {
    $bdd_selectionne = htmlentities($_GET['bdd']);
}
$lesImprimantes = Imprimante::getImprimantesParBDD($bdd_selectionne);

if (!empty($_GET['num_serie'])) {
    $num_serie = htmlentities($_GET['num_serie']);
    $lesImprimantes = Imprimante::searchImprimante($num_serie);
}
?>

<div class="container">
    <?php if (!isset($num_serie)): ?>
        <h1>Liste des Copieurs de la BdD <?= $bdd_selectionne ?><span class="info">*</span></h1>
        <span class="info">*</span><i>Pour des raisons de performances, nous avons découpé l'affichage des imprimantes par Base de Défense.</i>
    <?php else: ?>
        <h1>Recherche du copieur par '<?= $num_serie ?>'</h1>
    <?php endif ?>
    

    <form action="" class="mt-5 row g-3 align-items-center mb-2">
        <div class="col-auto">
            <label for="table_search_imprimantes" class="col-form-label">Rechercher un copieur</label>
        </div>
        <div class="col-auto">
            <input type="text" name="num_serie" class="form-control" id="table_search_imprimantes" placeholder="Insérer son numéro de série" value="<?= (isset($num_serie)) ? $num_serie : '' ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Rechercher dans toutes les BdD</button>
        </div>
    </form>

    <br>

    <form action="" class="row g-3 align-items-center">
        <div class="col-auto">
            <label for="bdd" class="form-label">Filtrer par Base de Défense</label>
        </div>
        <div class="col-auto">
            <select name="bdd" class="form-select" onchange="this.form.submit()" id="bdd">
                <?php foreach (BdD::getTousLesBDD() as $bdd) : $bdd = htmlentities($bdd[BdD::getChampBDD()]) ?>
                    <option value="<?= $bdd ?>" <?php if ($bdd_selectionne === $bdd) : ?>selected="selected" <?php endif ?>><?= $bdd ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </form>

    <hr>
    <br>

    <table id="table_content_imprimantes" class="table table-striped personalTable">
        <?= Imprimante::ChampsCopieur() ?>
        <tbody>
            <?php foreach ($lesImprimantes as $uneImprimante) : ?>
                <?= Imprimante::ValeursCopieur($uneImprimante) ?>
            <?php endforeach ?>
        </tbody>
    </table>


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