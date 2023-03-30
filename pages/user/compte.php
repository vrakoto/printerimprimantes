<?php
use App\User;

if (!empty($_POST)) {
    $mdp_actuel = htmlentities($_POST['mdp_actuel']);
    $new_mdp = htmlentities($_POST['new_mdp']);
    $confirm_new_mdp = htmlentities($_POST['confirm_new_mdp']);

    if (trim($mdp_actuel) === '') {
        $erreur = "Veuillez saisir votre mot de passe actuel";
    }

    if ($new_mdp !== $confirm_new_mdp) {
        $erreur = "Les mots de passe ne correspondent pas";
    }

    if (trim($new_mdp) === '') {
        $erreur = "Le nouveau mot de passe ne doit pas être vide";
    }

    if (empty($erreur)) {
        try {
            if (User::verifierMDP($mdp_actuel)) {
                User::changerMDP($new_mdp);
                $success = "Votre mot de passe a bien été changé";
            } else {
                $erreur = "Votre mot de passe actuel n'est pas correct";
            }
        } catch (PDOException $th) {
            $erreur = "Erreur interne, veuillez contacter l'administrateur du site";
            echo '<pre>';
            var_dump($th);
            echo '</pre>';
        }
    }
}
?>


<div class="row gutters">
    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">

        <?php if (isset($erreur)) : ?>
            <div class="alert alert-danger text-center"><?= $erreur ?></div>
        <?php endif ?>
        <?php if (isset($success)) : ?>
            <div class="alert alert-success text-center"><?= $success ?></div>
        <?php endif ?>
        
        <div class="card h-100">
            <div class="card-body">
                <div class="account-settings">
                    <div class="user-profile">
                        <h5 class="user-name">Grade Nom Prénom : <?= User::getGPN() ?></h5>
                        <h6 class="user-email">Messagerie : <?= User::getMessagerie() ?></h6>
                        <h6 class="user-role">Base de Défense : <?= User::getBDD() ?></h6>
                        <h6 class="user-role">Rôle : <?= User::getLibelleRole() ?></h6>
                    </div>

                    <hr>
                    <form method="post" action="">

                        <div class="mb-3">
                            <label for="mdp_actuel" class="form-label mb-0">Mot de passe actuel</label>
                            <input type="password" class="form-control" name="mdp_actuel" id="mdp_actuel">
                        </div>

                        <div class="mb-3">
                            <label for="new_mdp" class="form-label mb-0">Nouveau mot de passe</label>
                            <input type="password" class="form-control" name="new_mdp" id="new_mdp">
                        </div>

                        <div class="mb-3">
                            <label for="confirm_new_mdp" class="form-label mb-0">Confirmer le nouveau mot de passe</label>
                            <input type="password" class="form-control" name="confirm_new_mdp" id="confirm_new_mdp">
                        </div>

                        <button type="submit" class="btn btn-primary">Modifier mon mot de passe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>