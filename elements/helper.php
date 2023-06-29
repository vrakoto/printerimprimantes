<?php

function validateDate($date, $format = 'd-m-Y'): bool
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function convertDate(string $date, bool $heure = FALSE): string
{
    if ($heure === TRUE) {
        $heure = " à H:i";
    }
    $date = new DateTime($date);
    return $date->format('d/m/Y' . $heure);
}

function toAmericanDate(string $date): string
{
    $date = new DateTime($date);
    return $date->format('Y-m-d');
}


function debug($render)
{
    echo '<pre>';
    print_r($render);
    echo '</pre>';
}

function getValeurInput($variable_input, string|int $defaultValue = ''): string|int
{
    return isset($_GET[$variable_input]) ? htmlentities($_GET[$variable_input]) : $defaultValue;
}

function newException($msg = "Page introuvable"): void
{
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR. '404.php';
}

function newFormError($msg = "Le formulaire est invalide"): void
{
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR. 'erreurForm.php';
}

function success($msg): void
{
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR. 'success.php';
}