<?php
use App\User;

$error = '';

function validateDate($date, $format = 'd-m-Y'): bool
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

if ($_FILES['csv_file']['error'] == UPLOAD_ERR_OK && $_FILES['csv_file']['type'] == 'text/csv') {
    $csvData = file_get_contents($_FILES['csv_file']['tmp_name']);
    $rows = explode("\n", $csvData);

    // si contient des entetes
    if (isset($_POST['csv_file_header'])) {
        $rows = array_slice($rows, 1);
    }
    
    foreach ($rows as $row) {
        if (!empty($row)) {
            $line = trim($row);
            $t = explode(';', $line);
            $num_serie = htmlentities($t[0]);
            $date = htmlentities($t[2]);
            if (!validateDate($date)) {
                $erreur = "date incorrect";
            }
            try {
                User::ajouterReleve($t[0], $t[2], $t[4], $t[5], $t[6], $t[7], $t[10]);
            } catch (\Throwable $th) {
                var_dump($th->getMessage());
            }
        }
    }
} else {
    $erreur = "Erreur lors de l'upload du fichier CSV.";
    echo $erreur;
}