<?php
use App\User;

if (isset($_POST['num_serie'])) {
    $msg = '';
    $num_serie = htmlentities($_POST['num_serie']);
    $total_112 = (int)$_POST['total_112'];
    $total_113 = (int)$_POST['total_113'];
    $total_122 = (int)$_POST['total_122'];
    $total_123 = (int)$_POST['total_123'];

    try {
        User::ajouterReleve($num_serie, date('Y-m-d'), $total_112, $total_113, $total_122, $total_123, 'MANUEL');
    } catch (\Throwable $th) {
        if ($th->getCode() === "23000") {
            $msg = "Un relevé a déjà été effectué pour la machine " . $num_serie . " à la date actuelle.";
        } else {
            $msg = "Une erreur interne a été rencontrée";
        }
    }
}

die($msg);