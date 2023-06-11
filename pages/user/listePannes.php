<?php

use App\Panne;

$title = "Liste des Pannes";

$order = getValeurInput('order', 'ouverture');

$searching_num_serie = getValeurInput('num_serie');
$searching_num_ticket = getValeurInput('num_ticket');

$params = [
    'num_serie' => ['nom_db' => "num_série", 'value' => $searching_num_serie, 'valuePosition' => $searching_num_serie . '%'],
    'num_ticket' => ['nom_db' => "id_event", 'value' => $searching_num_ticket, 'valuePosition' => $searching_num_ticket . '%'],
    'order' => ['nom_db' => $order, 'value' => 'ASC']
];


// l'utilisateur a fait une recherche
$params_query = [];
foreach ($params as $nom_input => $props) {
    if ($nom_input === 'order') {
        $params_query['order'] = $props['nom_db'];
    } else {
        $params_query[$nom_input] = $props['value'];
    }
}
$fullURL = http_build_query($params_query);

$nb_results_par_page = 10;
$page = 1;
if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
}
if ($page <= 0) {
    header('Location:/' . $url);
    exit();
}

$debut = ($page - 1) * $nb_results_par_page;

$lesResultats = Panne::getLesPannes($params, false, [$debut, $nb_results_par_page]);
$lesResultatsSansPagination = Panne::getLesPannes($params, false);

require_once 'templates' . DIRECTORY_SEPARATOR . 'pannes.php';