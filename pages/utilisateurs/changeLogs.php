<?php

use App\Coordsic;
use App\Imprimante;
use App\Logs;
use App\User;

$title = "Journal des actions";
$url = "logs";
$nb_results_par_page = 10;

try {
    // $lesResultats = Logs::getLesLogs();
    // $lesResultatsSansPagination = Logs::getLesLogs();
} catch (\Throwable $th) {
    newException($th->getMessage());
}

/* $total = count($lesResultatsSansPagination);
$nb_pages = ceil($total / $nb_results_par_page); */

?>