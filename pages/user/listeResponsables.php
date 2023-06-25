<?php
use App\UsersCopieurs;

$title = "Liste des Responsables";
$url = "liste-responsables";
$perimetre = false;
$nb_results_par_page = 10;

$laTable = UsersCopieurs::ChampUsersCopieurs();
require_once 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

try {
    $lesResultats = UsersCopieurs::getResponsables($laTable, $perimetre);
    $lesResultatsSansPagination = UsersCopieurs::getResponsables($laTable, $perimetre, false);
    require_once 'templates' . DIRECTORY_SEPARATOR . 'responsables.php';
} catch (\Throwable $th) {
    newException('Erreur interne');
}