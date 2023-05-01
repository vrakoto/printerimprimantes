<?php
use App\Corsic;
use App\User;

$title = "Ajouter Copieur Perimètre";
$lesNumeros = Corsic::copieursPerimetrePasDansMaListe();

$msg = '';
if (isset($_POST['num_serie'])) {
    $num_serie = htmlentities($_POST['num_serie']);

    if (trim($num_serie) === '') {
        $msg = "Le numéro de série n'est pas renseigné";
    }

    if ($msg !== '') {
        try {
            User::ajouterDansPerimetre($num_serie);
        } catch (PDOException $th) {
            if ($th->getCode() === "23000") {
                $msg = "Le copieur " . $num_serie . " figure déjà dans votre périmètre";
            }
            $msg = "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur du site";
        }
    }
}
die($msg);