<?php

use App\BdD;
use App\Compteur;
use App\User;

$title = "Liste total des compteurs";
$bdd_selectionne = User::getBDD();
if (isset($_GET['bdd']) && !empty($_GET['bdd'])) {
    $bdd_selectionne = htmlentities($_GET['bdd']);
}
$lesImprimantes = Compteur::getLesRelevesParBDD($bdd_selectionne);

if (!empty($_GET['num_serie'])) {
    $num_serie = htmlentities($_GET['num_serie']);
    $lesImprimantes = Compteur::searchCompteurByNumSerie($num_serie);
}
?>

<div class="container">
    <?php if (!isset($num_serie)): ?>
        <h1 class="mt-5">Liste des compteurs de la BdD <?= $bdd_selectionne ?>*</h1>
        <span class="info">*</span><i>Pour des raisons de performances, nous avons découpé l'affichage des compteurs par Base de Défense.</i>
    <?php else: ?>
        <h1>Recherche les relevés du copieur : '<?= $num_serie ?>'</h1>
    <?php endif ?>

    <form action="" class="mt-5 row g-3 align-items-center">
        <div class="col-auto">
            <label for="customSearch" class="col-form-label">Rechercher un copieur :</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="customSearch" name="num_serie" placeholder="Insérer son numéro de série">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Rechercher dans toutes les BdD</button>
        </div>
    </form>

    <form action="" class="mt-1 mb-4 row g-3 align-items-center mb-3">
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
    
    <table id="table_compteurs" class="table table-striped personalTable">
        <?= Compteur::ChampsCompteur() ?>
        <tbody>
            <?php foreach ($lesImprimantes as $releve): ?>
                <?= Compteur::ValeursCompteur($releve) ?>
            <?php endforeach ?>
        </tbody>
    </table>
</div>