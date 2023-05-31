<?php

use App\Imprimante;

$title = "Liste des Copieurs";
$jsfile = 'listeCopieurs';
$url = 'liste_copieurs';

$order = getValeurInput('order', 'num_serie');
$ordertype = getValeurInput('ordertype', 'ASC');

$searching_num_serie = getValeurInput('num_serie');
$searching_bdd = getValeurInput('bdd');
$searching_modele = getValeurInput('modele');
$searching_statut = getValeurInput('statut_projet');
$searching_site_installation = getValeurInput('site_installation');
$searching_num_ordo = getValeurInput('num_ordo');

$params = [
    'num_serie' => ['nom_db' => "N° de Série", 'value' => $searching_num_serie, 'valuePosition' => $searching_num_serie . '%'],
    'bdd' => ['nom_db' => "BDD", 'value' => $searching_bdd, 'valuePosition' => $searching_bdd . '%'],
    'modele' => ['nom_db' => "Modele demandé", 'value' => $searching_modele, 'valuePosition' => $searching_modele . '%'],
    'statut_projet' => ['nom_db' => "STATUT PROJET", 'value' => $searching_statut, 'valuePosition' => $searching_statut . '%'],
    'site_installation' => ['nom_db' => "Site d'installation", 'value' => $searching_site_installation, 'valuePosition' => '%' . $searching_site_installation . '%'],
    'num_ordo' => ['nom_db' => "N° ORDO", 'value' => $searching_num_ordo, 'valuePosition' => $searching_num_ordo . '%'],
    'order' => ['nom_db' => $order, 'value' => $ordertype]
];


// l'utilisateur a fait une recherche
$params_query = [];
foreach ($params as $nom_input => $props) {
    if ($nom_input === 'order') {
        $params_query['order'] = $props['nom_db'];
        $params_query['ordertype'] = $ordertype;
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

try {
    $lesResultats = Imprimante::getImprimantes($params, [$debut, $nb_results_par_page]);
    $lesResultatsSansPagination = Imprimante::getImprimantes($params);
    require_once 'templates' . DIRECTORY_SEPARATOR . 'copieurs.php';
} catch (\Throwable $th) {
    $msg = "Lien incorrect";
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . '404.php';
}