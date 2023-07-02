<?php
use App\Imprimante;

$title = "Liste des Copieurs";
$url = 'liste_copieurs';
$perimetre = false;
$nb_results_par_page = 10;
$showColumns = $_SESSION['showColumns'];

$laTable = Imprimante::ChampsCopieur($perimetre, $showColumns);
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

try {
    $lesResultats = Imprimante::getImprimantes($laTable, $perimetre);
    $lesResultatsSansPagination = Imprimante::getImprimantes($laTable, $perimetre, false);
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'copieurs.php';
} catch (\Throwable $th) {
    newException("Erreur interne");
}