<?php
use App\Compteur;

$num_serie = htmlentities($params['num']);
$num_serie = str_replace('/', '', $num_serie);

$num_serie = htmlentities($params['num']);
$num_serie = str_replace('/', '', $num_serie);
$msg = '';
try {
    $results = Compteur::searchCompteurByNumSerie($num_serie);
} catch (\Throwable $th) {
    $msg = "Une erreur interne a été rencontrée. Veuillez contacter un administrateur";
}
$output = [
    "recordsTotal" => count($results),
    "recordsFiltered" => count($results),
    "data" => []
];

foreach ($results as $row) {
    $output['data'][] = $row;
}

die(json_encode($output));