<?php
use App\Users\User;

try {
    User::setTheme();
    header("Location:/");
    exit();
} catch (\Throwable $th) {
    die('Erreur interne');
}
?>