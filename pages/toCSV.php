<?php
use App\Compteur;

$num = htmlentities($params['num']);
$num = str_replace('/', '', $num);

$compteurs = Compteur::searchCompteurByNumSerie($num);
$filename = "compteurs_<?= $num ?>.csv";

$output = fopen("php://output", "w");

foreach ($compteurs as $row) {
    fputcsv($output, $row);
}

fclose($output);

header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="' . $filename . '";');

readfile('php://output');
exit();