<?php
use App\UsersCopieurs;

$title = "Responsables du Périmètre";
$url = 'responsables-perimetre';
$perimetre = true;
$nb_results_par_page = 10;

$laTable = UsersCopieurs::ChampUsersCopieurs();
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

try {
    $lesResultats = UsersCopieurs::getResponsables($laTable, $perimetre);
    $lesResultatsSansPagination = UsersCopieurs::getResponsables($laTable, $perimetre, false);
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'responsables.php';
} catch (\Throwable $th) {
    newException("Erreur interne");
}