<?php
use App\Coordsic;

function validateDate($date, $format = 'd-m-Y'): bool
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

$msg = '';
if (!empty($_POST)) {
    $num_serie = htmlentities($_POST['num_serie']);
    $date_input_user = str_replace("/", "-", $_POST['date_releve']); // Ici au format universel
    $total_112 = (int)$_POST['total_112'];
    $total_122 = (int)$_POST['total_122'];
    $total_113 = (int)$_POST['total_113'];
    $total_123 = (int)$_POST['total_123'];
    $type_releve = htmlentities($_POST['type_releve']);
    $currentDay = time();
    
    if (empty($num_serie)) {
        $msg = "Veuillez saisir ou sélectionner un numéro de série";
    }

    if (!validateDate($date_input_user)) {
        $msg = "Le format de la date ou la date est invalide. <br>
        Veuillez saisir une date existante et utiliser le format JJ-MM-AAAA OU avec des slashs JJ/MM/AAAA";
    } else if (strtotime($date_input_user) > $currentDay) {
        $msg = "Veuillez saisir une date de relevé inférieur ou égal à la date actuelle";
    }

    if ($msg === '') {
        try {
            $date_releve = DateTime::createFromFormat('d-m-Y', $date_input_user)->format('Y-m-d'); // convertit au format américain
            Coordsic::ajouterReleve($num_serie, $date_releve, $total_112, $total_113, $total_122, $total_123, $type_releve);
        } catch (Throwable $th) {
            if ($th->getCode() === "23000") {
                $msg = "L'imprimante : " . $num_serie . " possède déjà un relevé de compteur à la date : " . $date_input_user . "
                <br>Veuillez modifier ou supprimer son compteur déjà existant à la date : " . $date_input_user;
            } else {
                $msg = "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur du site.";
            }
        }
    }
}
die($msg);