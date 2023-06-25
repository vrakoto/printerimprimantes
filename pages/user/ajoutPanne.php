<?php
use App\Imprimante;

$title = "Ajout d'une Panne";

function addInformationForm($var, $titre, $value): string
{
    return <<<HTML
    <div class="row mb-3">
        <label for="$var" class="col-sm-3 label">$titre :</label>
        <div class="col-sm-2">
            <input type="text" id="$var" class="form-control" value="$value">
        </div>
    </div>
HTML;
}

$lesErreurs = [];
if (!empty($_POST)) {
    $num_serie = htmlentities($_POST['num_serie']);
    $contexte = $_POST['num_ordo'];
    $nature = htmlentities($_POST['modele']);
    $statut = htmlentities($_POST['statut_intervention']);
    $commentaire = htmlentities($_POST['commentaires']);
    $date_changement_situation = htmlentities($_POST['date_évolution']);
    $heure_changement_situation = htmlentities($_POST['heure_évolution']);
    $fichier = htmlentities($_POST['fichier']);

    /* if (trim($num_serie) === '') {
        $lesErreurs[] = "Le numéro de série n'est pas renseigné";
    } */

    foreach ($_POST as $key => $value) {
        if (empty($value)) {
            echo 'erreur';
        }
    }

    if (empty($lesErreurs)) {
        try {
            $num_ordo = (int)$num_ordo;
            // User::inscrirePanne($num_ordo, $num_serie, $modele, $bdd, $site_insta);
            // $_SESSION['message']['success'] = 'Imprimante ajoutée avec succès.';
            // header('Location:/inscrireCopieur');
            // exit();
        } catch (PDOException $th) {
            echo "erreur interne";
        }
    }
}
?>

<div class="container">
    <form class="border p-3" action="" method="post">
        <h1 class="mb-5">Ajout d'une Panne</h1>

        <div class="row mb-3">
            <label for="num_serie" class="col-sm-3 label">Numéro de Série</label>
            <div class="col-sm-2">
                <input type="text" id="num_serie" class="form-control">
            </div>
            <select class="selectize w-100" name="num_serie" id="num_serie" required>
                <?php foreach ($lesNumeros as $numero) : $num = htmlentities($numero['N° de Série']) ?>
                    <option value="<?= $num ?>"><?= $num ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="row mb-3">
            <label for="contexte" class="col-sm-3 label">Contexte de panne</label>
            <div class="col-sm-2">
                <input type="text" id="contexte" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <label for="nature" class="col-sm-3 label">Nature de la panne</label>
            <div class="col-sm-2">
                <input type="text" id="nature" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <label for="statut" class="col-sm-3 label">Statut de l'intervention</label>
            <div class="col-sm-2">
                <input type="text" id="statut" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <label for="commentaires" class="col-sm-3 label">Commentaires</label>
            <div class="col-sm-2">
                <input type="text" id="commentaires" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <label for="date_changement_situation" class="col-sm-3 label">Date de changement de situation</label>
            <div class="col-sm-2">
                <input type="text" id="date_changement_situation" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <label for="heure_changement_situation" class="col-sm-3 label">Heure d'évolution de la situation</label>
            <div class="col-sm-2">
                <input type="text" id="heure_changement_situation" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <label for="fichier" class="col-sm-3 label">Fichier(s) complémentaire(s)</label>
            <div class="col-sm-2">
                <input type="text" id="fichier" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>