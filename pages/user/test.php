<?php
use App\Coordsic;

$lesErreurs = [];
$msg = '';
if (!empty($_POST)) {
    $num_serie = htmlentities($_POST['num_serie']);
    $date_releve = htmlentities($_POST['date_releve']);
    $total_112 = (int)$_POST['total_112'];
    $total_122 = (int)$_POST['total_122'];
    $total_113 = (int)$_POST['total_113'];
    $total_123 = (int)$_POST['total_123'];
    $type_releve = htmlentities($_POST['type_releve']);
    $currentDay = date('Y-m-d');
    
    if (trim($num_serie) === '') {
        $lesErreurs[] = "Veuillez saisir ou sélectionner un numéro de série";
    }

    if (trim($date_releve) === '') {
        $lesErreurs[] = "Veuillez saisir la date de relevé";
    }

    if ($date_releve > $currentDay) {
        $lesErreurs[] = "Veuillez saisir une date de relevé inférieur ou égal à la date actuelle";
    }

    if (empty($lesErreurs)) {
        try {
            Coordsic::ajouterReleve($num_serie, $date_releve, $total_112, $total_113, $total_122, $total_123, $type_releve);
            $_SESSION['message'] = ['success' => 'Relevé ajouté avec succès.'];
        } catch (PDOException $th) {
            if ($th->getCode() === "23000") {
                $msg = "Error duplication";
                $_SESSION['message']['error'] = "L'imprimante : " . $num_serie . " possède déjà un relevé de compteurs pour aujourd'hui.
                <br>Veuillez modifier ou supprimer son compteur déjà existant.";
            } else {
                $msg = "Error interne";
                $_SESSION['message']['error'] = "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur du site.";
            }
        }
    } else {
        $_SESSION['message']['error'] = $lesErreurs;
        $msg = $lesErreurs;
    }
}
die($msg);