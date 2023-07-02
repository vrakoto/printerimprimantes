<?php

use App\Compteur;
use App\Imprimante;

$title = "Liste des Copieurs sans relevé ce trimestre";
$url = 'copieurs-sans-releve-trimestre';
$perimetre = true;
$nb_results_par_page = 10;
$showColumns = $_SESSION['showColumns'];

$laTable = Imprimante::ChampsCopieur($perimetre, $showColumns);
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

try {
    $lesResultats = Compteur::sansReleveTrimestre($laTable);
    $lesResultatsSansPagination = Compteur::sansReleveTrimestre($laTable, false);
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'copieurs.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}