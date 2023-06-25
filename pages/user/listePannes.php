<?php
use App\Panne;

$title = "Liste des Pannes";
$url = 'liste_pannes';
$perimetre = false;
$nb_results_par_page = 10;

$laTable = Panne::ChampPannes();
require_once 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

try {
    $lesResultats = Panne::getLesPannes($laTable, $perimetre);
    $lesResultatsSansPagination = Panne::getLesPannes($laTable, $perimetre, false);
    require_once 'templates' . DIRECTORY_SEPARATOR . 'pannes.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}