<?php
use App\Coordsic;
use App\User;

$msg = '';
if (!empty($_POST)) {
    $gpn = htmlentities($_POST['gpn']);
    $courriel = htmlentities($_POST['courriel']);
    $role = (int)$_POST['role'];
    $mdp = htmlentities($_POST['mdp']);
    $unite = htmlentities($_POST['unite']);

    if (empty($gpn)) {
        $msg = "Le grade prenom nom ne doit pas être vide";
    }

    if (empty($courriel)) {
        $msg = "Le courriel ne doit pas être vide";
    }

    if (empty($role)) {
        $msg = "Le role ne doit pas être vide";
    }

    if ($role <= 0 && $role > 3) {
        $msg = "Le role n'est pas correct";
    }

    if (empty($mdp)) {
        $msg = "Le mot de passe ne doit pas être vide";
    }

    if (empty($msg)) {
        try {
            Coordsic::creerUtilisateur(User::getBDD(), $gpn, $courriel, $role, $mdp, $unite);
        } catch (\Throwable $th) {
            if ($th->getCode() === "23000") {
                $msg = "Un utilisateur possède déjà le courriel '" . $courriel . "'";
            } else {
                $msg = "Une erreur interne a été rencontrée. Veuillez contacter un administrateur";
            }
        }
    }
}

die($msg);