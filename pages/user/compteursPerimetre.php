<?php

use App\Compteur;
use App\User;

$title = "Compteurs du périmètre";
$url = 'compteurs_perimetre';

$order = getValeurInput('order', 'date_maj');
$ordertype = getValeurInput('ordertype', 'DESC');

$searching_num_serie = getValeurInput('num_serie');
$searching_date = getValeurInput('date');
$searching_101 = getValeurInput('total_101');
$searching_112 = getValeurInput('total_112');
$searching_113 = getValeurInput('total_113');
$searching_122 = getValeurInput('total_122');
$searching_123 = getValeurInput('total_123');
$searching_modif_par = getValeurInput('modif_par');
$searching_date_maj = getValeurInput('date_maj');
$searching_type_releve = getValeurInput('type_releve');

$params = [
    'num_serie' => ['nom_db' => "Numéro_série", 'value' => $searching_num_serie, 'valuePosition' => $searching_num_serie . '%'],
    'date' => ['nom_db' => "Date", 'value' => $searching_date, 'valuePosition' => $searching_date],
    'total_101' => ['nom_db' => "101_Total_1", 'value' => $searching_101, 'valuePosition' => $searching_101],
    'total_112' => ['nom_db' => "112_Total", 'value' => $searching_112, 'valuePosition' => $searching_112],
    'total_113' => ['nom_db' => "113_Total", 'value' => $searching_113, 'valuePosition' => $searching_113],
    'total_122' => ['nom_db' => "122_Total", 'value' => $searching_122, 'valuePosition' => $searching_122],
    'total_123' => ['nom_db' => "123_Total", 'value' => $searching_123, 'valuePosition' => $searching_123],
    'modif_par' => ['nom_db' => "grade-prenom-nom", 'value' => $searching_modif_par, 'valuePosition' => '%' . $searching_modif_par . '%'],
    'date_maj' => ['nom_db' => "date_maj", 'value' => $searching_date_maj, 'valuePosition' => $searching_date_maj],
    'type_releve' => ['nom_db' => "type_relevé", 'value' => $searching_type_releve, 'valuePosition' => $searching_type_releve . '%'],
    'order' => ['nom_db' => $order, 'value' => $ordertype]
];

$keysToRemove = ['num_serie', 'date', 'total_101', 'modif_par', 'date_maj', 'order'];

// Filtrer le tableau en retirant les clés spécifiées
$modalVariables = array_filter($params, function ($key) use ($keysToRemove) {
    return !in_array($key, $keysToRemove);
}, ARRAY_FILTER_USE_KEY);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_variables = [];
    foreach ($params as $nom_input => $props) {
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
            $msg = "Un relevé a déjà été effectué pour la machine " . $post_variables['num_serie'] . " à la date du " . convertDate($post_variables['date']);
        } else {
            $msg = "Une erreur interne a été rencontrée";
        }
        newFormError($msg);
    }
}


// l'utilisateur a fait une recherche
$params_query = [];
foreach ($params as $nom_input => $props) {
    if ($nom_input === 'order') {
        $params_query['order'] = $props['nom_db'];
        $params_query['ordertype'] = $ordertype;
    } else if (!empty($props['value'])) {
        $params_query[$nom_input] = $props['value'];
    }
}
$fullURL = http_build_query($params_query);

$nb_results_par_page = 10;
$page = 1;
if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
}
if ($page <= 0) {
    header('Location:/' . $url);
    exit();
}

$debut = ($page - 1) * $nb_results_par_page;

try {
    $perimetre = true;
    $lesResultats = Compteur::getLesReleves($params, $perimetre, [$debut, $nb_results_par_page]);
    $lesResultatsSansPagination = Compteur::getLesReleves($params, $perimetre);
    $laTable = Compteur::testChamps($perimetre);
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
                            <?php foreach (User::getLesNumerosMonPerimetre() as $numero) : debug($numero);
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

                <?php foreach ($modalVariables as $nom_input => $props) {
                    echo addInformationForm($nom_input, Compteur::testChamps($perimetre)[$nom_input]['libelle'], '', [4, 3]);
                } ?>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>
    </div>
</div>