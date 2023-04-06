<?php
use App\User;

$num_serie = htmlentities($_POST['num_serie']);
$msg = '';
try {
    User::retirerDansPerimetre($num_serie);
} catch (\Throwable $th) {
    $msg = "Une erreur interne a été rencontrée. Veuillez contacter un administrateur";
}
die($msg);