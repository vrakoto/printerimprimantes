<?php
use App\Corsic;

$msg = '';
if (!empty($_POST)) {
    $num_serie = htmlentities($_POST['num_serie']);
    
    if (empty($num_serie)) {
        $msg = "Veuillez saisir ou sélectionner un numéro de série";
    }

    if ($msg === '') {
        try {
            Corsic::ajouterDansPerimetre($num_serie);
        } catch (Throwable $th) {
            if ($th->getCode() === "23000") {
                $msg = "Le copieur " . $num_serie . " figure déjà dans votre périmètre";
            } else {
                $msg = "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur du site";
            }
        }
    }
}
die($msg);