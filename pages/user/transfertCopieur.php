<?php

use App\BdD;
use App\Coordsic;
use App\Imprimante;
use App\User;

$title = "Transfert des Copieurs";
$url = "transfert-copieur";
$nb_results_par_page = 10;

$lesNumeros = Imprimante::getImprimantes([], true, false);

$laTable = Imprimante::ChampsTransfert();
$defaultOrder = "date";
$defaultOrderType = "DESC";
require_once 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

try {
    $lesResultats = Imprimante::getLesTransferts($laTable);
    $lesResultatsSansPagination = Imprimante::getLesTransferts($laTable, false);
} catch (\Throwable $th) {
    newException($th->getMessage());
}

$total = count($lesResultatsSansPagination);
$nb_pages = ceil($total / $nb_results_par_page);


if (isset($_POST['num_serie'], $_POST['bdd'])) {
    $num_serie = htmlentities($_POST['num_serie']);
    $bdd = htmlentities($_POST['bdd']);

    if (!empty(trim($num_serie)) && !empty(trim($bdd))) {
        $erreur = 'Veuillez sélectionner un N° de Série et une Base de Défense';
    }

    if (!empty($erreur)) {
        try {
            Coordsic::historiqueTransfert($num_serie, $bdd);
            Coordsic::transfererCopieur($num_serie, $bdd);
            header('Location:' . $url . '?s=1');
            exit();
        } catch (\Throwable $th) {
            newFormError($th->getMessage());
        }
    }
}


if (isset($_GET['csv']) && $_GET['csv'] === "yes") {
    Imprimante::downloadCSV(Imprimante::ChampsTransfert(), 'liste_transfert', $lesResultatsSansPagination);
}
?>

<div class="p-4 mt-">
    <?php require_once 'templates' . DIRECTORY_SEPARATOR . 'header.php' ?>

    <?php if ($page <= $nb_pages) : ?>
        <?php require_once 'templates' . DIRECTORY_SEPARATOR . 'pagination.php' ?>

        <table class="table table-striped table-bordered personalTable">
            <thead>
                <tr>
                    <?php foreach (Imprimante::ChampsTransfert() as $nom_input => $props) : ?>
                        <th id="<?= $nom_input ?>"><?= $props['libelle'] ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lesResultats as $data) : ?>
                    <tr>
                        <?php foreach ($data as $nom_input => $value) :
                            if ($nom_input === 'date') {
                                $value = convertDate($value, true);
                            }
                        ?>
                            <td><?= $value ?></td>
                        <?php endforeach ?>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else : ?>
        <h3 class="mt-4">Aucun transfert trouvé</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_transfert_machine"><i class="fa-solid fa-plus"></i> Transférer un copieur</button>
    <?php endif ?>
</div>


<div class="modal fade" id="modal_transfert_machine" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="post">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Transférer un copieur</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row mb-3">
                    <label for="add_num_serie" class="col-sm-4">N° de Série</label>
                    <select name="num_serie" class="selectize col-sm-4" placeholder="Sélectionnez un N° de Série...">
                        <?php foreach ($lesNumeros as $numero) : $num = htmlentities($numero['num_serie']) ?>
                            <option value="<?= $num ?>"><?= $num ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="row">
                    <label for="select_num_serie" class="col-sm-4">BDD ciblée</label>
                    <select name="bdd" class="selectize col-sm-4" placeholder="Sélectionnez une BdD...">
                        <?php foreach (BdD::getTousLesBDD() as $lesBdDs) : $bdd = htmlentities($lesBdDs['BDD']) ?>
                            <?php if ($bdd !== User::getBDD()) : ?>
                                <option value="<?= $bdd ?>"><?= $bdd ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="return confirm('Voulez-vous transférer ce copieur ?');">Transférer</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Effectuer une recherche et/ou un tri</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row mb-3">
                    <label for="order" class="col-sm-4">Trier par</label>
                    <select class="selectize col-sm-4" id="order" name="order">
                        <?php foreach (Imprimante::ChampsTransfert() as $nom_input => $props) : ?>
                            <option value="<?= $nom_input ?>" <?php if ($order === $nom_input) : ?>selected<?php endif ?>><?= $props['libelle'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <select class="selectize col-sm-4" id="ordertype" name="ordertype">
                        <option value="ASC" <?php if ($ordertype === 'ASC') : ?>selected<?php endif ?>>Croissant</option>
                        <option value="DESC" <?php if ($ordertype === 'DESC') : ?>selected<?php endif ?>>Décroissant</option>
                    </select>
                </div>

                <?php foreach (Imprimante::ChampsTransfert() as $nom_input => $props): ?>
                    <?php if ($nom_input !== 'bdd'): // bdd doit être personnalisé pour les select ?>
                        <div class="row mb-3">
                            <label for="<?= $nom_input ?>" class="col-sm-4"><?= $props['libelle'] ?> :</label>
                            <div class="col-sm-3">
                                <input type="text" id="<?= $nom_input ?>" name="<?= $nom_input ?>" class="form-control" value="<?= getValeurInput($nom_input) ?>">
                            </div>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>
    </div>
</div>