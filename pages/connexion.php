<?php
use App\Connexion;

if (isset($_POST['messagerie'], $_POST['mdp'])) {
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'User.php';
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Connexion.php';

    $messagerie = htmlentities($_POST['messagerie']);
    $mdp = htmlentities($_POST['mdp']);

    $connexion = new Connexion($messagerie, $mdp);

    if ($connexion->verifierAuth()) {
        $connexion->etablirConnexion();
    } else {
        $erreur = 'Authentification incorrect';
    }
}
$title = "Sapollon - Connexion";
?>
<div class="mt-3 connexion container border p-3">
    <form action="" method="POST">

        <?php if (isset($erreur)) : ?>
            <div class="alert alert-danger text-center">
                <?= $erreur ?>
            </div>
        <?php endif ?>

        <div class="mb-3">
            <label for="messagerie">Adresse de messagerie</label>
            <input class="form-control" type="text" name="messagerie" id="messagerie" placeholder="Insérez votre messagerie intradef" autofocus>
        </div>

        <div class="mb-3">
            <label for="mdp">Mot de passe</label>
            <input class="form-control" type="password" name="mdp" id="mdp" placeholder="Insérez votre mot de passe">
        </div>

        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
</div>