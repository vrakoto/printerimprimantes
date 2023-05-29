<?php

use App\UsersCopieurs;

$title = "Liste des Responsables";
$url = "liste-responsables";

$order = getValeurInput('order', 'gpn');

$searching_gpn = getValeurInput('gpn');
$searching_num_serie = getValeurInput('num_serie');

$params = [
    'gpn' => ['nom_db' => "grade-prenom-nom", 'value' => $searching_gpn, 'valuePosition' => '%' . $searching_gpn . '%'],
    'num_serie' => ['nom_db' => "numéro_série", 'value' => $searching_num_serie, 'valuePosition' => $searching_num_serie . '%'],
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

$lesResultats = UsersCopieurs::getResponsables($params, false, [$debut, $nb_results_par_page]);
$lesResultatsSansPagination = UsersCopieurs::getResponsables($params, false);

require_once 'templates' . DIRECTORY_SEPARATOR . 'responsables.php';