<?php
use App\User;

$msg = '';
if (!empty($_POST)) {
    $num_serie = htmlentities($_POST['num_serie']);
    $date_releve = htmlentities($_POST['date_releve']);
    
    if (empty($num_serie)) {
        $msg = "Veuillez sélectionner un numéro de série";
    }

    if ($msg === '') {
        try {
            User::supprimerReleve($num_serie, $date_releve);
        } catch (Throwable $th) {
            $msg = "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur du site.";
            $msg = $th;
        }
    }
}
die($msg);