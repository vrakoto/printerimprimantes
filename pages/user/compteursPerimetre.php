<?php

use App\Compteur;
use App\User;

$title = "Compteurs du périmètre";
$url = 'compteurs_perimetre';
$perimetre = true;
$nb_results_par_page = 10;

$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$order = getValeurInput('order', 'date_maj');
$ordertype = getValeurInput('ordertype', 'DESC');
$showColumns = $_SESSION['showColumns'];

$laTable = Compteur::testChamps($perimetre);

foreach ($laTable as $key => $value) {
    $nom_input = $value['nom_input'];
    $valuePosition = getValeurInput($value['nom_input']) . '%';

    // Ajouter des valeurs spécifiques pour chaque clé
    switch ($nom_input) {
        case 'date':
            $valuePosition = getValeurInput('date');
            break;
        case 'total_101':
            $valuePosition = getValeurInput('total_101');
            break;
        case 'total_112':
            $valuePosition = getValeurInput('total_112');
            break;
        case 'total_113':
            $valuePosition = getValeurInput('total_113');
            break;
        case 'total_122':
            $valuePosition = getValeurInput('total_122');
            break;
        case 'total_123':
            $valuePosition = getValeurInput('total_123');
            break;
        case 'modif_par':
            $valuePosition = '%' . getValeurInput('modif_par') . '%';
            break;
        case 'date_maj':
            $valuePosition = getValeurInput('date_maj');
            break;
    }

    $laTable[$key] = array_merge($value, [
        'value' => getValeurInput($nom_input),
        'valuePosition' => $valuePosition
    ]);
}

$laTable['order'] = ['nom_db' => $order, 'value' => $ordertype];


// l'utilisateur a fait une recherche
$laTable_query = [];
foreach ($laTable as $nom_input => $props) {
    if ($nom_input === 'order') {
        $laTable_query['order'] = $props['nom_db'];
        $laTable_query['ordertype'] = $ordertype;
    } else if (!empty($props['value'])) {
        $laTable_query[$nom_input] = $props['value'];
    }
}
$fullURL = http_build_query($laTable_query);

$laTable['page'] = ['value' => $page];
$laTable['debut'] = ['value' => (($page - 1) * $nb_results_par_page)];
$laTable['nb_results_page'] = ['value' => $nb_results_par_page];

if ($page <= 0) {
    header('Location:/' . $url);
    exit();
}

$keysToRemove = ['num_serie', 'date', 'total_101', 'modif_par', 'date_maj', 'order', 'page', 'debut', 'nb_results_page'];
// Filtrer le tableau en retirant les clés spécifiées pour le formulaire d'ajout d'un compteur
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
                            <?php foreach (User::getLesNumerosMonPerimetre() as $numero) :
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