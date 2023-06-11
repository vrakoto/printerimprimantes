<?php
use App\User;

$date = $params['year'] . '-' . $params['month'] . '-' . $params['day'];
try {
    User::supprimerReleve($params['num'], $date);
    header('Location:/compteurs_perimetre?d=1');
    exit();
} catch (\Throwable $th) {
    newFormError("La demande est invalide");
}