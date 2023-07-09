<?php

use App\Corsic;
use App\Imprimante;
use App\User;

$title = "Copieurs du périmètre";
$url = 'copieurs_perimetre';
$perimetre = true;
$nb_results_par_page = 10;
$showColumns = $_SESSION['showColumns'];

$laTable = Imprimante::ChampsCopieur($perimetre, $showColumns);

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

if (isset($_POST['add_num_serie'])) {
    $num_serie_to_add = htmlentities($_POST['add_num_serie']);

    $allow = false;
    foreach (Corsic::copieursPerimetrePasDansMaListe() as $imprimante) {
        if ($num_serie_to_add === $imprimante['num_serie']) {
            $allow = true;
        }
    }

    if ($allow) {
        try {
            User::ajouterDansPerimetre($num_serie_to_add);
            header('Location:' . $url);
            exit();
        } catch (PDOException $th) {
            if ((int)$th->getCode() === 23000) {
                newFormError("Le copieur : <b>$num_serie_to_add</b> figure déjà dans votre périmètre.");
            } else {
                newFormError("Une erreur interne a été rencontrée.");
            }
        }
    } else {
        newFormError("Le copieur : <b>$num_serie_to_add</b> ne figure pas dans votre BdD ou est inexistant dans Sapollon.");
    }
}

if (isset($_POST['remove_num_serie'])) {
    $num_serie_to_remove = htmlentities($_POST['remove_num_serie']);

    try {
        User::retirerDansPerimetre($num_serie_to_remove);
        header('Location:' . $url);
        exit();
    } catch (PDOException $th) {
        die("Une erreur interne a été rencontrée.");
    }
}

try {
    $lesResultats = Imprimante::getImprimantes($laTable, $perimetre);
    $lesResultatsSansPagination = Imprimante::getImprimantes($laTable, $perimetre, false);
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'copieurs.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}