<?php
use App\Compteur;

$title = "Liste des Compteurs";
$url = 'liste_compteurs';
$perimetre = false;
$nb_results_par_page = 10;

$laTable = Compteur::ChampsCompteur($perimetre);
require_once 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

try {
    $lesResultats = Compteur::getLesReleves($laTable, $perimetre);
    $lesResultatsSansPagination = Compteur::getLesReleves($laTable, $perimetre, false);
    require_once 'templates' . DIRECTORY_SEPARATOR . 'compteurs.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}