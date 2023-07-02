<?php
use App\Compteur;

$title = "Archive des compteurs du T2-2023";
$url = 'compteurs-perimetre-T2-2023';
$perimetre = true;
$nb_results_par_page = 10;

$laTable = Compteur::ChampsCompteur($perimetre);
$defaultOrder = "date_maj";
$defaultOrderType = "DESC";
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

$laTable['trimestre'] = ['t1' => ['debut' => '2023-06-01', 'fin' => '2023-06-28']];

try {
    $lesResultats = Compteur::getLesReleves($laTable, $perimetre);
    $lesResultatsSansPagination = Compteur::getLesReleves($laTable, $perimetre, false);
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'compteurs.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}