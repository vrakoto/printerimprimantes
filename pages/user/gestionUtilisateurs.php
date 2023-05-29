<?php

use App\Coordsic;
use App\Imprimante;
use App\User;

$title = "Gestion des Utilisateurs";
$url = "gestion-utilisateurs";

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

$searching_gpn = getValeurInput('gpn');
$searching_courriel = getValeurInput('courriel');
$searching_role = getValeurInput('role');

$params = [
    'gpn' => ['nom_db' => "grade-prenom-nom", 'value' => $searching_gpn, 'valuePosition' => '%' . $searching_gpn . '%'],
    'courriel' => ['nom_db' => "Courriel", 'value' => $searching_courriel, 'valuePosition' => $searching_courriel . '%'],
    'role' => ['nom_db' => "userlevelname", 'value' => $searching_role, 'valuePosition' => $searching_role . '%'],
    'order' => ['nom_db' => $order, 'value' => 'ASC']
];


// l'utilisateur a fait une recherche
$params_query = [];
foreach ($params as $nom_input => $props) {
    if ($nom_input === 'order') {
        $params_query['order'] = $props['nom_db'];
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

$lesResultats = User::getUtilisateursPerimetre($params, [$debut, $nb_results_par_page]);
$lesResultatsSansPagination = User::getUtilisateursPerimetre($params);

$total = count($lesResultatsSansPagination);
$nb_pages = ceil($total / $nb_results_par_page);

if (isset($_GET['csv']) && $_GET['csv'] === "yes") {
    $champs = '';
    foreach (colonnes(Imprimante::ChampsCopieur()) as $id => $nom) {
        $champs .= $nom . ";";
    }
    Imprimante::downloadCSV($champs, 'liste_machines', $lesResultatsSansPagination);
}

function addInformationForm($var, $titre, $value, array $size): string
{
    $labelSize = $size[0];
    $inputSize = $size[1];
    return <<<HTML
    <div class="row mb-3">
        <label for="$var" class="col-sm-$labelSize label">$titre :</label>
        <div class="col-sm-$inputSize">
            <input type="text" id="$var" name="$var" class="form-control" value="$value">
        </div>
    </div>
HTML;
}
?>

<style>
    thead th:hover {
        background-color: orange;
        cursor: pointer;
    }

    #pagination a {
        color: black;
        padding: 8px 16px;
        transition: background-color .3s;
        border: 1px solid #ddd;
    }

    #pagination a:hover {
        background-color: #ddd;
    }
</style>

<div class="p-4">
    <div class="mt-2" id="header">
        <h1><?= $title ?></h1>

        <button class="btn btn-success" id="downloadCSV" title="Télécharger les données en CSV"><i class="fa-solid fa-download"></i> Télécharger les données</button>
        <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_search"><i class="fa-solid fa-filter"></i> Rechercher</button>
        <a class="mx-1 btn btn-secondary" href="/<?= $url ?>"><i class="fa-solid fa-arrow-rotate-left"></i> Réinitialiser la recherche</a>
    </div>

    <?php //if (User::getRole() == 2) : 
    ?>
    <button class="mt-5 btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_create_user">Créer un utilisateur</button>
    <?php //endif 
    ?>

    <?php if ($page <= $nb_pages) : ?>
        <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
            <div id="pagination">
                <a class="<?= $page != 1 ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=1&<?= $fullURL ?>">1</a>
                <a <?php if ($page <= 1) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page - 1 ?>&<?= $fullURL ?>"><i class="fa-solid fa-arrow-left"></i></a>

                <button class="btn btn-secondary" style="cursor: unset;"><?= $page ?></button>

                <a <?php if ($page >= $nb_pages) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page + 1 ?>&<?= $fullURL ?>"><i class="fa-solid fa-arrow-right"></i></a>
                <a class="<?= $page != $nb_pages ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=<?= $nb_pages ?>&<?= $fullURL ?>"><?= $nb_pages ?></a>
            </div>

            <h3 class="mt-5">Nombre total d'utilisateurs : <?= $total ?></h3>
        </div>

        <table id="table_imprimantes" class="table table-striped table-bordered personalTable triggerDT">
            <?= User::ChampsGestionUtilisateurs() ?>
            <tbody>
                <?php foreach ($lesResultats as $data) :
                    $gpn = htmlentities($data['gpn']);
                    $courriel = htmlentities($data['courriel']);
                    $role = htmlentities($data['role']);
                ?>
                    <tr>
                        <td class="gpn"><?= $gpn ?></td>
                        <td class="courriel"><?= $courriel ?></td>
                        <td class="bdd"><?= $role ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else : ?>
        <h3 class="mt-4">Aucun utilisateur trouvé</h3>
    <?php endif ?>
</div>

<div class="modal fade" id="modal_search" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Effectuer une recherche</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row mb-3">
                    <label for="order" class="col-sm-4">Trier par</label>
                    <select class="selectize col-sm-4" id="order" name="order">
                        <?php foreach (colonnes(User::ChampsGestionUtilisateurs()) as $key => $s) : ?>
                            <option value="<?= $key ?>" <?php if ($order === $key) : ?>selected<?php endif ?>><?= $s ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <?php foreach ($params as $nom_input => $props) {
                    if ($nom_input !== 'statut_projet' && $nom_input !== 'order') { // statut_projet doit être personnalisé pour les select
                        echo addInformationForm($nom_input, $props['nom_db'], getValeurInput($nom_input), [4, 3]);
                    }
                } ?>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="modal_create_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="post">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Créer un utilisateur</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= addInformationForm("gpn", "Grade Prénom Nom", getValeurInput('gpn'), [5, 3]) ?>
                <?= addInformationForm("courriel", "Courriel", getValeurInput('courriel'), [5, 3]) ?>
                <?= addInformationForm("unite", "Unité", getValeurInput('unite'), [5, 3]) ?>

                <div class="row mb-3">
                    <label for="role" class="col-sm-5">Role</label>
                    <select class="selectize col-sm-4" id="role" name="role">
                        <option value="1">CORSIC</option>
                        <option value="2">COORDSIC</option>
                        <option value="3">CORRESPONDANT SOLIMP</option>
                    </select>
                </div>

                <?= addInformationForm("mdp", "Mot de passe", getValeurInput('mdp'), [5, 3]) ?>
                <?= addInformationForm("confirm_mdp", "Confirmez le mot de passe", getValeurInput('confirm_mdp'), [5, 3]) ?>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Créer</button>
            </div>
        </form>
    </div>
</div>