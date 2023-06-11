<?php

use App\Coordsic;
use App\Imprimante;
use App\User;

$title = "Gestion des Utilisateurs";
$url = "gestion-utilisateurs";
$laTable = User::ChampsGestionUtilisateurs();


if (!empty($_POST)) {
    $gpn = htmlentities($_POST['gpn']);
    $courriel = htmlentities($_POST['courriel']);
    $role = htmlentities($_POST['role']);
    $unite = htmlentities($_POST['unite']);
    $mdp = htmlentities($_POST['mdp']);
    $confirm_mdp = htmlentities($_POST['confirm_mdp']);

    try {
        Coordsic::creerUtilisateur(User::getBDD(), $gpn, $courriel, $role, $mdp, $unite);
    } catch (PDOException $th) {
        die('erreur');
    }
}

$order = getValeurInput('order', 'gpn');
$ordertype = getValeurInput('ordertype', 'DESC');

foreach ($laTable as $key => $value) {
    $nom_input = $value['nom_input'];
    $valuePosition = getValeurInput($value['nom_input']) . '%';

    switch ($nom_input) {
        case 'gpn':
            $valuePosition = '%' . getValeurInput('gpn') . '%';
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


$keysToRemove = ['order'];
$formVariables = array_filter($laTable, function ($key) use ($keysToRemove) {
    return !in_array($key, $keysToRemove);
}, ARRAY_FILTER_USE_KEY);

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

$lesResultats = User::getUtilisateursPerimetre($laTable, [$debut, $nb_results_par_page]);
$lesResultatsSansPagination = User::getUtilisateursPerimetre($laTable);

$total = count($lesResultatsSansPagination);
$nb_pages = ceil($total / $nb_results_par_page);

if (isset($_GET['csv']) && $_GET['csv'] === "yes") {
    $champs = '';
    foreach (User::ChampsGestionUtilisateurs() as $nom_input => $props) {
        $champs .= $props['libelle'] . ";";
    }
    Imprimante::downloadCSV($champs, 'sapollon_liste_utilisateurs_' . User::getBDD(), $lesResultatsSansPagination);
}
?>

<div class="p-4">
    <?php require_once 'templates' . DIRECTORY_SEPARATOR . 'header.php' ?>

    <?php if ($page <= $nb_pages) : ?>
        <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
            <div id="pagination">
                <a class="<?= $page != 1 ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=1&<?= $fullURL ?>">1</a>
                <a <?php if ($page <= 1) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page - 1 ?>&<?= $fullURL ?>"><i class="fa-solid fa-arrow-left"></i></a>

                <button class="btn btn-secondary" style="cursor: unset;"><?= $page ?></button>

                <a <?php if ($page >= $nb_pages) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page + 1 ?>&<?= $fullURL ?>"><i class="fa-solid fa-arrow-right"></i></a>
                <a class="<?= $page != $nb_pages ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=<?= $nb_pages ?>&<?= $fullURL ?>"><?= $nb_pages ?></a>
            </div>

            <h3 class="mt-5">Total d'utilisateurs : <?= $total ?></h3>
        </div>

        <table class="table table-striped table-bordered personalTable">
            <thead>
                <tr>
                    <?php foreach (User::ChampsGestionUtilisateurs() as $nom_input => $props) : ?>
                        <th id="<?= $nom_input ?>"><?= $props['libelle'] ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lesResultats as $data) : ?>
                    <tr>
                        <?php foreach ($data as $nom_input => $value) : ?>
                            <td><?= $value ?></td>
                        <?php endforeach ?>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else : ?>
        <h3 class="mt-4">Aucun utilisateur trouvé</h3>
    <?php endif ?>
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
                        <?php foreach (User::ChampsGestionUtilisateurs() as $key => $s) : ?>
                            <option value="<?= $key ?>" <?php if ($order === $key) : ?>selected<?php endif ?>><?= $s['libelle'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <select class="selectize col-sm-4" id="ordertype" name="ordertype">
                        <option value="ASC" <?php if ($ordertype === 'ASC') : ?>selected<?php endif ?>>Croissant</option>
                        <option value="DESC" <?php if ($ordertype === 'DESC') : ?>selected<?php endif ?>>Décroissant</option>
                    </select>
                </div>

                <?php foreach (User::ChampsGestionUtilisateurs() as $nom_input => $props) { ?>
                    <div class="row mb-3">
                        <label for="<?= $nom_input ?>" class="col-sm-4"><?= $props['libelle'] ?> :</label>
                        <div class="col-sm-3">
                            <input type="text" id="<?= $nom_input ?>" name="<?= $nom_input ?>" class="form-control" value="<?= getValeurInput($nom_input) ?>">
                        </div>
                    </div>
                <?php } ?>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>
    </div>
</div>