<?php

use App\Compteur;
use App\Imprimante;
use App\User;

$lesNumeros = User::copieursPerimetre();

$lesErreurs = [];
if (!empty($_POST)) {
    $num_serie = htmlentities($_POST['num_serie']);
    $date_releve = htmlentities($_POST['date_releve']);
    $total_112 = (int)$_POST['112_total'];
    $total_113 = (int)$_POST['113_total'];
    $total_122 = (int)$_POST['122_total'];
    $total_123 = (int)$_POST['123_total'];
    $type_releve = htmlentities($_POST['type_releve']);
    $currentDay = date('Y-m-d');

    if (trim($num_serie) === '') {
        $lesErreurs[] = "Veuillez saisir ou sélectionner un numéro de série déjà existant dans votre périmètre";
    }

    if (trim($date_releve) === '') {
        $lesErreurs[] = "Veuillez saisir la date de relevé (ne doit pas être supérieur à la date actuelle)";
    }

    if ($date_releve > $currentDay) {
        $lesErreurs[] = "Veuillez saisir une date de relevé inférieur ou égal à la date actuelle";
    }

    if (empty($lesErreurs)) {
        try {
            User::ajouterReleve($num_serie, $date_releve, $total_112, $total_113, $total_122, $total_123, $type_releve);
            $_SESSION['message'] = ['success' => 'Relevé ajouté avec succès.'];
            header('Location:/compteurs_perimetre');
            exit();
        } catch (PDOException $th) {
            if ($th->getCode() === "23000") {
                $_SESSION['message']['error'] = "L'imprimante : " . $num_serie . " possède déjà un relevé de compteurs pour aujourd'hui.
                <br>Veuillez modifier ou supprimer son compteur déjà existant.";
            } else {
                $_SESSION['message']['error'] = "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur du site.";
            }
            var_dump($th->getMessage());
        }
    } else {
        $_SESSION['message']['error'] = $lesErreurs;
    }
}
$hasFormMessage = !empty($_SESSION['message']);
?>

<div class="container" id="container">

    <h1 class="mt-5">Compteurs du périmètre</h1>
    <?= messageForm($lesErreurs) ?>
    <br>

    <?php if (count(User::copieursPerimetre()) > 0) : ?>
        <button class="mb-1 btn btn-primary" id="btn_add_releve" onclick="toggle_inputs_releve(this)">Ajouter un relevé</button>
    <?php elseif (count(User::copieursPerimetre()) <= 0) : ?>
        <div class="mb-3">
            <h5>Vous n'avez aucun copieur dans votre périmètre.</h5>
            <a href="<?= $router->url('add_machine_area') ?>" class="mb-4 btn btn-primary">Ajouter un copieur dans mon périmètre</a>
        </div>
    <?php endif ?>

    <form action="" method="post" id="form_add_counter" class="<?= $hasFormMessage ? "" : "d-none" ?>">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>Numéro Série</th>
                    <th>Date de relevé</th>
                    <th>112 Total</th>
                    <th>113 Total</th>
                    <th>122 Total</th>
                    <th>123 Total</th>
                    <th>Type de relevé</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <button type="submit" title="Valider la saisie" class="btn btn-primary"><i class="fa-solid fa-check"></i></button>
                    </td>
                    <td><a title="Annuler la saisie" class="btn btn-danger" onclick="cancelReleve(this)"><i class="fa-solid fa-xmark"></i></a></td>
                    <td>
                        <select class="selectize" name="num_serie" id="num_serie">
                            <?php foreach ($lesNumeros as $numero) : $num = htmlentities($numero[Imprimante::getChamps('champ_num_serie')]) ?>
                                <option value="<?= $num ?>"><?= $num ?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <td><input name="date_releve" type="date" class="form-control" type="text" id="date_releve" placeholder="MM/JJ/AAAA"></td>
                    <td><input name="112_total" type="number" class="form-control" type="number" min="0" placeholder="Saisir 112 Total " id="total_112"></td>
                    <td><input name="113_total" type="number" class="form-control" type="number" min="0" placeholder="Saisir 113 Total " id="total_113"></td>
                    <td><input name="122_total" type="number" class="form-control" type="number" min="0" placeholder="Saisir 122 Total " id="total_122"></td>
                    <td><input name="123_total" type="number" class="form-control" type="number" min="0" placeholder="Saisir 123 Total " id="total_123"></td>
                    <td><input name="type_releve" class="form-control" type="text" id="type_releve" value="MANUEL"></td>
                </tr>
            </tbody>
        </table>
    </form>

    <hr>

    <div class="row g-3 align-items-center">
        <div class="col-auto">
            <label for="customSearch" class="col-form-label">Rechercher un compteur</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="customSearch" name="num_serie" placeholder="Insérer son numéro de série">
        </div>
    </div>

    <?php if (count(Compteur::getLesRelevesParBDD()) > 0): ?>
        <table id="table_compteurs" class="table table-striped personalTable">
            <?= Compteur::ChampsCompteur() ?>
            <tbody>
                <?php foreach (User::getLesRelevesMonPerimetre() as $releve) : ?>
                    <?= Compteur::ValeursCompteur($releve) ?>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else: ?>
        <h4 class="mt-4 text-center">Aucun relevé</h4>
    <?php endif ?>
</div>