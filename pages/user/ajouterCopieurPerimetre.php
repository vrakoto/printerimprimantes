<?php
use App\Users\Corsic;
use App\Users\User;

// User::requireRole(1);

$title = "Ajouter Copieur Perimètre";
$lesNumeros = Corsic::copieursPerimetrePasDansMaListe();

$error = '';
if (isset($_POST['num_serie'])) {
    $num_serie = htmlentities($_POST['num_serie']);

    if (trim($num_serie) === '') {
        $error = "Le numéro de série n'est pas renseigné";
    }

    if (empty($error)) {
        try {
            Corsic::ajouterDansPerimetre($num_serie);
            $_SESSION['message'] = ['success' => "Le copieur " . $num_serie . " a bien été ajouté dans votre périmètre"];
            header('Location:/ajouterCopieurPerimetre');
            exit();
        } catch (PDOException $th) {
            if ($th->getCode() === "23000") {
                $_SESSION['message'] = ['danger' => "Le copieur " . $num_serie . " figure déjà dans votre périmètre"];
            }
            $_SESSION['message'] = ['danger' => "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur du site"];
        }
    } else {
        $_SESSION['message']['error'] = $error;
    }
}
?>

<div class="container">
    <form class="border p-3" id="add_releve" method="post">
        <?= messageForm() ?>

        <h1>Ajouter un copieur dans mon périmètre (<?= User::getBDD() ?>)</h1>
        <i>Remarque : le copieur que vous souhaitez ajouter dans votre périmètre doit être préalablement enregistré dans Sapollon.
            <br>
            Si le copieur ne figure pas dans la liste déroulante ci-dessous, veuillez contacter un COORDSIC ou un administrateur Sapollon pour qu'il puisse l'ajouter.
        </i>
        <div class="mt-5 mb-3">
            <label for="num_serie" class="form-label">Sélectionnez ou saisir le numéro de série <span class="obligatoire">*</span></label>
            <select class="selectize" name="num_serie" id="num_serie">
                <?php foreach ($lesNumeros as $numero) : $num = htmlentities($numero['num_serie']) ?>
                    <option value="<?= $num ?>"><?= $num ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <a class="btn btn-secondary" href="/copieurs_perimetre">Retour</a>
        <button class="btn btn-primary" type="submit">Ajouter dans mon périmètre</button>
    </form>
</div>