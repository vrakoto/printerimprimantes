<?php
use App\Compteur;

$title = "Liste des Compteurs";
$url = 'liste_compteurs';
$perimetre = false;
$nb_results_par_page = 10;

$laTable = Compteur::ChampsCompteur($perimetre);
$defaultOrder = "date_maj";
$defaultOrderType = "DESC";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

try {
    $lesResultats = Compteur::getLesReleves($laTable, $perimetre);
    $lesResultatsSansPagination = Compteur::getLesReleves($laTable, $perimetre, false);
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'compteurs.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}