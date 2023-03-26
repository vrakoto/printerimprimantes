<?php
use App\Compteur;
use App\Users\Coordsic;
use App\Users\Corsic;
use App\Users\User;

$title = "Inscrire un nouveau compteur";

$lesNumeros = Corsic::copieursPerimetre();

$lesErreurs = [];
if (!empty($_POST)) {
    $num_serie = htmlentities($_POST['num_serie']);
    $date_releve = htmlentities($_POST['date_releve']);
    $total_112 = (int)$_POST['112_total'];
    $total_113 = (int)$_POST['113_total'];
    $total_123 = (int)$_POST['123_total'];
    $type_releve = htmlentities($_POST['type_releve']);
    $currentDay = date('Y-m-d');
    
    if (trim($num_serie) === '') {
        $lesErreurs[] = "Veuillez saisir ou sélectionner un numéro de série";
    }

    if (trim($date_releve) === '') {
        $lesErreurs[] = "Veuillez saisir la date de relevé";
    }

    if ($date_releve > $currentDay) {
        $lesErreurs[] = "Veuillez saisir une date de relevé inférieur ou égal à la date actuelle";
    }

    if (empty($lesErreurs)) {
        try {
            Coordsic::ajouterReleve($num_serie, $date_releve, $total_112, $total_113, $total_123, $type_releve);
            $_SESSION['message'] = ['success' => 'Relevé ajouté avec succès.'];
            header('Location:/ajouterReleve');
            exit();
        } catch (PDOException $th) {
            if ($th->getCode() === "23000") {
                $_SESSION['message']['error'] = "L'imprimante : " . $num_serie . " possède déjà un relevé de compteurs pour aujourd'hui.
                <br>Veuillez modifier ou supprimer son compteur déjà existant.";
            } else {
                $_SESSION['message']['error'] = "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur du site.";
            }
        }
    } else {
        $_SESSION['message']['error'] = $lesErreurs;
    }
    
}
?>

<div class="container">


    <form class="border p-3" action="/ajouterReleve" id="add_releve" method="post">

        <?= messageForm($lesErreurs) ?>

        <h1>Ajouter un relevé</h1>
        <div class="border mt-3">
            <i>Remarque : <u>Chaque imprimante</u> ne peut avoir <u>qu'un seul relevé par jour</u>.</i>
            <br>
            <i class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> La date de relevé ne doit pas être supérieur à la date actuelle <i class="fa-solid fa-triangle-exclamation"></i></i>
        </div>
        <div class="mt-5"></div>
        <div class="mb-3">
            <label for="num_serie" class="form-label">Numéro de Série<span class="obligatoire">*</span></label>
            <select class="selectize" name="num_serie" id="num_serie">
                <?php foreach ($lesNumeros as $numero) : $num = htmlentities($numero['num_serie']) ?>
                    <option value="<?= $num ?>"><?= $num ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="date_releve" class="form-label">Date de relevé<span class="obligatoire">*</span> (exemple: 01/01/2023) :</label>
            <input type="date" class="form-control" name="date_releve" id="date_releve" placeholder="Saisir la date de relevé">
        </div>

        <div class="mb-3">
            <label for="112_total" class="form-label">112 Total :</label>
            <input type="number" class="form-control" name="112_total" id="112_total" placeholder="Saisir le 112 Total (laissez le champ vide si 0 ou aucun)">
        </div>

        <div class="mb-3">
            <label for="113_total" class="form-label">113 Total :</label>
            <input type="number" class="form-control" name="113_total" id="113_total" placeholder="Saisir le 113 Total (laissez le champ vide si 0 ou aucun)">
        </div>

        <div class="mb-3">
            <label for="123_total" class="form-label">123 Total :</label>
            <input type="number" class="form-control" name="123_total" id="123_total" placeholder="Saisir le 123 Total (laissez le champ vide si 0 ou aucun)">
        </div>

        <div class="mb-3">
            <label for="type_releve" class="form-label">Type de relevé<span class="obligatoire">*</span> :</label>
            <select name="type_releve" id="type_releve" class="form-select">
                <option value="MANUEL" selected="selected">MANUEL</option>
                <option value="IWMC">IWMC (Automatique)</option>
            </select>
        </div>

        <button class="btn btn-primary" type="submit">Ajouter</button>

        <?= mandatoryFieldMessage() ?>
    </form>

    <?php if (count(Compteur::getLesRelevesParBDD(User::getBDD())) > 0) : ?>
        <hr>

        <h3 class="mt-5 text-center">Liste des relevés de compteurs de la BdD <?= User::getBDD() ?></h3>
        <br>

        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="customSearch" class="col-form-label">Rechercher un copieur</label>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" id="customSearch" name="num_serie" placeholder="Insérer son numéro de série">
            </div>
        </div>

        <table id="copieurs_new_added" class="table table-striped personalTable">
            <?= Compteur::ChampsCompteur() ?>
            <tbody>
                <?php foreach (Compteur::getLesRelevesParBDD(User::getBDD()) as $releve): ?>
                    <?= Compteur::ValeursCompteur($releve) ?>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else : ?>
        <h3 class="mt-5 text-center">Aucun relevé</h3>
    <?php endif ?>

</div>