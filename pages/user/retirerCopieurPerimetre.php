<?php
use App\User;

$title = "Retirer Copieur Perimètre";
$lesNumeros = Corsic::copieursPerimetre();

$error = '';
if (isset($_POST['num_serie'])) {
    $num_serie = htmlentities($_POST['num_serie']);

    if (trim($num_serie) === '') {
        $error = "Le numéro de série n'est pas renseigné";
    }

    if (empty($error)) {
        try {
            User::retirerDansPerimetre($num_serie);
            $_SESSION['message'] = ['success' => "Le copieur " . $num_serie . " a bien été retiré de votre périmètre"];
            header('Location:/retirerCopieurPerimetre');
            exit();
        } catch (PDOException $th) {
            if ($th->getCode() === "23000") {
                $_SESSION['message'] = ['error' =>  "Le copieur '" . $num_serie . "' est déjà retiré de votre périmètre"];
            }
            $_SESSION['message'] = ['error' =>  "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur du site."];
        }
    }
}

?>

<div class="container mt-5">
    <form class="border p-3" id="add_releve" method="post">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= $error ?>
            </div>
        <?php endif ?>

        <?= messageForm() ?>

        <h1>Retirer un copieur de mon périmètre</h1>
        <i>Remarque : cette action a pour seul effet de retirer un copieur de votre périmètre, mais ne supprime pas entièrement ce dernier de l'application Sapollon. 
            <br>
            Si vous avez besoin de supprimer définitivement un copieur, veuillez contacter un Administrateur ou un COORDSIC de votre périmètre afin qu'il puisse le mettre en RETRAIT.
        </i>
        <div class="mt-5 mb-3">
            <label for="num_serie" class="form-label">Saisir le Numéro de Série <span class="obligatoire">*</span></label>
            <select class="selectize" name="num_serie" id="num_serie">
                <?php foreach ($lesNumeros as $numero) : $num = htmlentities($numero['N° de Série']) ?>
                    <option value="<?= $num ?>"><?= $num ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <a class="btn btn-secondary" href="/copieurs_perimetre">Retour</a>
        <button class="btn btn-danger" type="submit">Retirer de mon périmètre</button>
    </form>
</div>