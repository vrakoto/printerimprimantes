<?php
use App\Compteur;
use App\Imprimante;
use App\User;

$title = "Compteurs du périmètre";
$url = 'compteurs_perimetre';
$perimetre = true;
$nb_results_par_page = 10;

$laTable = Compteur::ChampsCompteur($perimetre);
$defaultOrder = "date_maj";
$defaultOrderType = "DESC";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

// Filtre qui les clés spécifiées pour le formulaire d'ajout d'un compteur
$keysToRemove = ['num_serie', 'date', 'total_101', 'modif_par', 'date_maj', 'order', 'page', 'debut', 'nb_results_page'];
$modalVariables = array_filter($laTable, function ($key) use ($keysToRemove) {
    return !in_array($key, $keysToRemove);
}, ARRAY_FILTER_USE_KEY);


if (isset($_POST['num_serie'], $_POST['date'])) {
    $post_variables = [];
    foreach ($laTable as $nom_input => $props) {
        if (isset($_POST[$nom_input])) {
            $post_variables[$nom_input] = htmlentities($_POST[$nom_input]);
        }
    }

    try {
        User::ajouterReleve($post_variables['num_serie'], $post_variables['date'], $post_variables['total_112'], $post_variables['total_113'], $post_variables['total_122'], $post_variables['total_123'], $post_variables['type_releve']);
        header('Location:' . $url);
        exit();
    } catch (\Throwable $th) {
        if ($th->getCode() === "23000") {
            $msg = "Un relevé a déjà été effectué pour la machine " . $post_variables['num_serie'] . " à la date du " . convertDate($post_variables['date']);
        } else {
            $msg = "Une erreur interne a été rencontrée";
        }
        newFormError($msg);
    }
}

try {
    $lesResultats = Compteur::getLesReleves($laTable, $perimetre);
    $lesResultatsSansPagination = Compteur::getLesReleves($laTable, $perimetre, false);
    if (!empty(Imprimante::getImprimantes([], true, false))) {
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'compteurs.php';
    } else {
        newFormError("Vous n'avez aucun copieur dans votre périmètre.");
    }
} catch (\Throwable $th) {
    newException($th->getMessage());
}