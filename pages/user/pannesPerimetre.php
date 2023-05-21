<?php
use App\Panne;
use App\User;

$title = "Les Pannes de mon Périmètre";
$cssfile = 'listePannes';
$jsfile = 'listePannes';

$num_serie = isset($_GET['num_serie']) ? htmlentities($_GET['num_serie']) : '';
$num_ticket = isset($_GET['num_ticket']) ? htmlentities($_GET['num_ticket']) : '';
$params = ['num_série' => $num_serie . '%', 'id_event' => $num_ticket . '%', 'c.BDD' => User::getBDD()];

$nb_results_par_page = 5;
$page = 1;
if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
}
if ($page <= 0) {
    header('Location:/pannes_perimetre');
    exit();
}
$debut = ($page - 1) * $nb_results_par_page;

$lesPannes = Panne::getLesPannes($params, true, [$debut, $nb_results_par_page]);
$lesPannesSansPagination = Panne::getlesPannes($params, true);

require_once 'templates' . DIRECTORY_SEPARATOR . 'pannes.php';