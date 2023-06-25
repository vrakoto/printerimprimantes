<?php
use App\Compteur;
use App\Imprimante;
use App\User;

$title = "Compteurs du périmètre";
$url = 'compteurs_perimetre';
$perimetre = true;
$nb_results_par_page = 10;

$laTable = Compteur::ChampsCompteur($perimetre);
require_once 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

// Filtre qui les clés spécifiées pour le formulaire d'ajout d'un compteur
$keysToRemove = ['num_serie', 'date', 'total_101', 'modif_par', 'date_maj', 'order', 'page', 'debut', 'nb_results_page'];
$modalVariables = array_filter($laTable, function ($key) use ($keysToRemove) {
    return !in_array($key, $keysToRemove);
}, ARRAY_FILTER_USE_KEY);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_variables = [];
    foreach ($laTable as $nom_input => $props) {
        if (isset($_POST[$nom_input])) {
            $post_variables[$nom_input] = htmlentities($_POST[$nom_input]);
        }
    }

    try {
        User::ajouterReleve($post_variables['num_serie'], $post_variables['date'], $post_variables['total_112'], $post_variables['total_113'], $post_variables['total_122'], $post_variables['total_123'], $post_variables['type_releve']);
        header('Location:' . $url);
        exit();
    } catch (\Throwable $th) {
        if ($th->getCode() === "23000") {
            $msg = "Un relevé a déjà été effectué pour la machine " . $post_variables['num_serie'] . " à la date du " . convertDate($post_variables['date']) . '<br> Veuillez supprimer son compteur déjà existant.';
        } else {
            $msg = "Une erreur interne a été rencontrée";
        }
        newFormError($msg);
    }
}

try {
    $lesResultats = Compteur::getLesReleves($laTable, $perimetre);
    $lesResultatsSansPagination = Compteur::getLesReleves($laTable, $perimetre, false);
    require_once 'templates' . DIRECTORY_SEPARATOR . 'compteurs.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}

?>

<div class="modal fade" id="modal_add_counter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="post">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Ajouter un relevé de compteur</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row mb-3">
                    <label for="num_serie" class="col-sm-4 label">N° de Série</label>
                    <div class="col-sm-3">
                        <select class="selectize w-100" name="num_serie" id="num_serie">
                            <?php foreach (Imprimante::getImprimantes([], true, false) as $numero) :
                                $num = htmlentities($numero['num_serie']) ?>
                                <option value="<?= $num ?>"><?= $num ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="date" class="col-sm-4 label">Date de relevé</label>
                    <div class="col-sm-5">
                        <input type="date" name="date" id="date" class="form-control" value="date">
                    </div>
                </div>

                <?php foreach ($modalVariables as $nom_input => $props) : ?>
                    <div class="row mb-3">
                        <label for="<?= $nom_input ?>" class="col-sm-4"><?= $props['libelle'] ?> :</label>
                        <div class="col-sm-3">
                            <input type="text" id="<?= $nom_input ?>" name="<?= $nom_input ?>" class="form-control" value="<?= getValeurInput($nom_input) ?>">
                        </div>
                    </div>
                <?php endforeach ?>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>
    </div>
</div>