<?php
use App\Panne;

$title = "Pannes du périmètre";
$url = 'pannes_perimetre';
$perimetre = true;
$nb_results_par_page = 10;

$laTable = Panne::ChampPannes();
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

try {
    $lesResultats = Panne::getLesPannes($laTable, $perimetre);
    $lesResultatsSansPagination = Panne::getLesPannes($laTable, $perimetre, false);
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'pannes.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}