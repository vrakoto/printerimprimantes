<?php

use App\User;

function addInformationForm($allowEdit, $var, $titre, $value, $type = 'text'): string
{
    $allowInput = !$allowEdit ? "disabled" : "";
    $styleForbiddenInput = !$allowEdit ? "cursor: no-drop;" : "";
    $nameAllowedInput = $allowEdit ? "name=" . $var : "";
    return <<<HTML
    <div class="row mb-3">
        <label for="$var" class="col-sm-2 label">$titre :</label>
        <div class="col-sm-2">
            <input type="$type" id="$var" $nameAllowedInput class="form-control" value="$value" style="$styleForbiddenInput" $allowInput>
        </div>
    </div>
HTML;
}

if (!empty($_POST)) {
    $mdp_actuel = htmlentities($_POST['mdp_actuel']);
    $new_mdp = htmlentities($_POST['new_mdp']);
    $confirm_new_mdp = htmlentities($_POST['confirm_new_mdp']);

    if (trim($mdp_actuel) === '') {
        $erreur = "Veuillez saisir votre mot de passe actuel";
    } else if ($new_mdp !== $confirm_new_mdp) {
        $erreur = "Les mots de passe ne correspondent pas";
    } else if (trim($new_mdp) === '') {
        $erreur = "Le nouveau mot de passe ne doit pas être vide";
    } else if (!User::verifierMDP($mdp_actuel)) {
        $erreur = "Votre mot de passe actuel n'est pas correct";
    }

    if (empty($erreur)) {
        try {
            User::changerMDP($new_mdp);
            $success = "Votre mot de passe a bien été changé";
        } catch (PDOException $th) {
            $erreur = "Erreur interne, veuillez contacter l'administrateur du site";
        }
    }
}
?>

<?php if (isset($erreur)) : ?>
    <div class="alert alert-danger text-center"><?= $erreur ?></div>
<?php endif ?>
<?php if (isset($success)) : ?>
    <div class="alert alert-success text-center"><?= $success ?></div>
<?php endif ?>

<form action="" method="post" class="border mt-5 p-3">
    <?= addInformationForm(false, 'gpn', 'Grade Nom Prénom', User::getGPN()) ?>
    <?= addInformationForm(false, 'messagerie', 'Messagerie', User::getMessagerie()) ?>
    <?= addInformationForm(false, 'bdd', 'Base de Défense', User::getBDD()) ?>
    <?= addInformationForm(false, 'role', 'Rôle', User::getLibelleRole()) ?>
    <?= addInformationForm(true, 'mdp_actuel', 'Votre Mot de passe actuel', '', 'password') ?>
    <?= addInformationForm(true, 'new_mdp', 'Nouveau Mot de passe', '', 'password') ?>
    <?= addInformationForm(true, 'confirm_new_mdp', 'Confirmez le nouveau Mot de passe', '', 'password') ?>
    <button type="submit" class="btn btn-success">Sauvegarder</button>
</form>