<?php

use App\Compteur;

$num_serie = htmlentities($params['num']);
$num_serie = str_replace('/', '', $num_serie);
$msg = '';
try {
    $results = Compteur::searchCompteurByNumSerie($num_serie);
} catch (\Throwable $th) {
    $msg = "Une erreur interne a été rencontrée. Veuillez contacter un administrateur";
}
$output = [
    "draw" => 10,
    "recordsTotal" => 50,
    "recordsFiltered" => 50,
    "data" => []
];

foreach ($results as $row) {
    $output['data'][] = $row;
}

die(json_encode($output));