<?php
use App\Compteur;

$date = $params['year'] . '-' . $params['month'] . '-' . $params['day'];
try {
    // Compteur::supprimerReleve($params['num'], $date);
    header('Location:/compteurs_perimetre?d=1');
    exit();
} catch (\Throwable $th) {
    newFormError("La demande est invalide");
}