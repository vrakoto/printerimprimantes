<?php
use App\Imprimante;

$title = "Copieurs du périmètre sans responsable";
$url = 'copieurs-sans-responsable';
$perimetre = true;
$nb_results_par_page = 10;
$showColumns = $_SESSION['showColumns'];

$laTable = Imprimante::ChampsCopieur($perimetre, $showColumns);
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'logique.php';

try {
    $lesResultats = Imprimante::sansResponsable($laTable);
    $lesResultatsSansPagination = Imprimante::sansResponsable($laTable, false);
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'copieurs.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}