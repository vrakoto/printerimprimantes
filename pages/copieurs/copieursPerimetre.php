<?php

use App\Corsic;
use App\Imprimante;
use App\User;

$title = "Copieurs du périmètre";
$url = 'copieurs_perimetre';
$perimetre = true;
$nb_results_par_page = 10;
$showColumns = $_SESSION['showColumns'];

$laTable = Imprimante::ChampsCopieur($perimetre, $showColumns);

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

if (isset($_POST['add_num_serie'])) {
    $num_serie_to_add = htmlentities($_POST['add_num_serie']);

    try {
        User::ajouterDansPerimetre($num_serie_to_add);
        header('Location:' . $url);
        exit();
    } catch (PDOException $th) {
        die("Une erreur interne a été rencontrée.");
    }
}

if (isset($_POST['remove_num_serie'])) {
    $num_serie_to_remove = htmlentities($_POST['remove_num_serie']);

    try {
        User::retirerDansPerimetre($num_serie_to_remove);
        header('Location:' . $url);
        exit();
    } catch (PDOException $th) {
        die("Une erreur interne a été rencontrée.");
    }
}

try {
    $lesResultats = Imprimante::getImprimantes($laTable, $perimetre);
    $lesResultatsSansPagination = Imprimante::getImprimantes($laTable, $perimetre, false);
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'copieurs.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}

?>

<div class="modal fade" id="modal_add_machine_area" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="post">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Ajouter un copieur dans mon périmètre</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row mb-3">
                    <label for="add_num_serie" class="col-auto">Sélectionnez un N° de Série</label>
                    <select class="select w-100" name="add_num_serie" id="add_num_serie">
                        <?php foreach (Corsic::copieursPerimetrePasDansMaListe() as $numero) : $num = htmlentities($numero['num_serie']) ?>
                            <option value="<?= $num ?>"><?= $num ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="modal_remove_machine_area" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="post">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Retirer un copieur de mon périmètre</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row mb-3">
                    <label for="remove_num_serie" class="col-auto">Sélectionnez un N° de Série</label>
                    <select class="select w-100" name="remove_num_serie" id="remove_num_serie">
                        <?php foreach (Imprimante::getImprimantes([], true, false) as $numero) : $num = htmlentities($numero['num_serie']) ?>
                            <option value="<?= $num ?>"><?= $num ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
    </div>
</div>