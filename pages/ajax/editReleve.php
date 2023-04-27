<?php
use App\Coordsic;

$msg = '';
if (!empty($_POST)) {
    $num_serie = htmlentities($_POST['num_serie']);
    $date_releve = htmlentities($_POST['date_releve']);
    $total_112 = (int)$_POST['total_112'];
    $total_122 = (int)$_POST['total_122'];
    $total_113 = (int)$_POST['total_113'];
    $total_123 = (int)$_POST['total_123'];
    $type_releve = htmlentities($_POST['type_releve']);
    
    if (empty($num_serie)) {
        $msg = "Veuillez saisir ou sélectionner un numéro de série";
    }

    if ($msg === '') {
        try {
            Coordsic::editReleve($num_serie, $date_releve, $total_112, $total_113, $total_122, $total_123, $type_releve);
        } catch (Throwable $th) {
            $msg = "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur du site.";
        }
    }
}
die($msg);