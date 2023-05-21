<?php

use App\Panne;

$title = "Liste des Pannes";
$cssfile = 'listePannes';
$jsfile = 'listePannes';

$num_serie = isset($_GET['num_serie']) ? htmlentities($_GET['num_serie']) : '';
$num_ticket = isset($_GET['num_ticket']) ? htmlentities($_GET['num_ticket']) : '';
$params = ['num_série' => $num_serie . '%', 'id_event' => $num_ticket . '%'];

$nb_results_par_page = 5;
$page = 1;
if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
}
if ($page <= 0) {
    header('Location:/liste_pannes');
    exit();
}
$debut = ($page - 1) * $nb_results_par_page;

$lesPannes = Panne::getLesPannes($params, false, [$debut, $nb_results_par_page]);
$lesPannesSansPagination = Panne::getlesPannes($params, false);

require_once 'templates' . DIRECTORY_SEPARATOR . 'pannes.php';