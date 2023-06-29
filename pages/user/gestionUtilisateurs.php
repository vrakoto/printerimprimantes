<?php

use App\Coordsic;
use App\Imprimante;
use App\User;

$title = "Gestion des Utilisateurs";
$url = "gestion-utilisateurs";
$nb_results_par_page = 10;

$laTable = User::ChampsGestionUtilisateurs();
$defaultOrder = 'gpn';
require_once 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

try {
    $lesResultats = User::getUtilisateursPerimetre($laTable);
    $lesResultatsSansPagination = User::getUtilisateursPerimetre($laTable, false);
} catch (\Throwable $th) {
    newException($th->getMessage());
}

$total = count($lesResultatsSansPagination);
$nb_pages = ceil($total / $nb_results_par_page);

if (isset($_GET['csv']) && $_GET['csv'] === "yes") {
    Imprimante::downloadCSV(User::ChampsGestionUtilisateurs(), 'sapollon_liste_utilisateurs_' . User::getBDD(), $lesResultatsSansPagination);
}


$erreur = false;
if (isset($_POST) && !empty($_POST)) {
    $post_variables = [];
    foreach (User::ChampsGestionUtilisateurs(true) as $nom_input => $props) {
        if (isset($_POST[$nom_input])) {
            $post_variables[$nom_input] = htmlentities($_POST[$nom_input]);
        }
    }

    if (empty($post_variables['courriel']) && empty($post_variables['mdp']) && empty($post_variables['courriel']) && empty($post_variables['gpn'])) {
        $erreur = true;
    }

    if (!$erreur) {
        try {
            Coordsic::creerUtilisateur(User::getBDD(), $post_variables['gpn'], $post_variables['courriel'], 1, $post_variables['mdp'], $post_variables['unite']);
            $success = true;
        } catch (PDOException $th) {
            $msg = "Erreur interne";
            if ($th->getCode() === "23000") {
                $msg = "Un utilisateur avec le mail '" . $post_variables['courriel'] . "' est déjà existant";
            }
            newFormError($msg);
        }
    } else {
        newFormError("Veuillez remplir tous les champs. Le champ 'Unité' est falcutatif.");
    }
}
?>

<div class="p-4">

    <?php if (isset($success)): ?>
        <div class="alert alert-success text-center">
            Compte ajouté avec succès.
        </div>
    <?php endif ?>

    <?php require_once 'templates' . DIRECTORY_SEPARATOR . 'header.php' ?>

    <?php if ($page <= $nb_pages) : ?>
        <?php require_once 'templates' . DIRECTORY_SEPARATOR . 'pagination.php' ?>

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
                        <?php foreach (User::ChampsGestionUtilisateurs() as $nom_input => $s) : ?>
                            <option value="<?= $nom_input ?>" <?php if ($order === $nom_input) : ?>selected<?php endif ?>><?= $s['libelle'] ?></option>
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
                            <input type="text" name="<?= $nom_input ?>" class="form-control" value="<?= getValeurInput($nom_input) ?>">
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

<div class="modal fade" id="modal_create_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="post">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Créer un Utilisateur (CORSIC)</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <?php foreach (User::ChampsGestionUtilisateurs(true) as $nom_input => $props) : ?>
                    <?php if ($nom_input !== 'role') : ?>
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
                <button type="submit" class="btn btn-primary">Créer</button>
            </div>
        </form>
    </div>
</div>