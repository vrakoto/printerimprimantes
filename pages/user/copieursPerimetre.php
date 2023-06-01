<?php

use App\Corsic;
use App\User;

$title = "Copieurs du périmètre";
$jsfile = 'listeCopieurs';
$url = 'copieurs_perimetre';

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

$order = getValeurInput('order', 'num_serie');
$ordertype = getValeurInput('ordertype', 'ASC');

$searching_num_serie = getValeurInput('num_serie');
$searching_bdd = getValeurInput('bdd');
$searching_modele = getValeurInput('modele');
$searching_statut = getValeurInput('statut_projet');
$searching_site_installation = getValeurInput('site_installation');
$searching_num_ordo = getValeurInput('num_ordo');

$params = [
    'num_serie' => ['nom_db' => "N° de Série", 'value' => $searching_num_serie, 'valuePosition' => $searching_num_serie . '%'],
    'modele' => ['nom_db' => "Modele demandé", 'value' => $searching_modele, 'valuePosition' => $searching_modele . '%'],
    'statut_projet' => ['nom_db' => "STATUT PROJET", 'value' => $searching_statut, 'valuePosition' => $searching_statut . '%'],
    'site_installation' => ['nom_db' => "Site d'installation", 'value' => $searching_site_installation, 'valuePosition' => '%' . $searching_site_installation . '%'],
    'num_ordo' => ['nom_db' => "N° ORDO", 'value' => $searching_num_ordo, 'valuePosition' => $searching_num_ordo . '%'],
    'order' => ['nom_db' => $order, 'value' => $ordertype]
];


// l'utilisateur a fait une recherche
$params_query = [];
foreach ($params as $nom_input => $props) {
    if ($nom_input === 'order') {
        $params_query['order'] = $props['nom_db'];
        $params_query['ordertype'] = $ordertype;
    } else {
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
    $lesResultats = User::copieursPerimetre2($params, [$debut, $nb_results_par_page]);
    $lesResultatsSansPagination = User::copieursPerimetre2($params);
    require_once 'templates' . DIRECTORY_SEPARATOR . 'copieurs.php';
} catch (\Throwable $th) {
    $msg = "Lien incorrect";
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . '404.php';
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
                    <select class="selectize w-100" name="add_num_serie" id="add_num_serie">
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
                    <select class="selectize w-100" name="remove_num_serie" id="remove_num_serie">
                        <?php foreach (Corsic::copieursPerimetre() as $numero) : $num = htmlentities($numero['num_serie']) ?>
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