<?php

use App\Imprimante;

$title = "Copieurs sans relevé ce Trimestre";
$jsfile = 'listeCopieurs';
$url = 'copieurs-sans-releve-trimestre'; // url actuel de la vue

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
];

// l'utilisateur a fait une recherche
$params_query = [];
foreach ($params as $nom_input => $props) {
    $params_query[$nom_input] = $props['value'];
}
$fullURL = http_build_query($params_query);

$nb_results_par_page = 10;
$page = 1;
if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
}
if ($page <= 0) {
    header('Location:/' . $match['name']);
    exit();
}

$debut = ($page - 1) * $nb_results_par_page;

$lesResultats = Imprimante::sansReleves3Mois($params, [$debut, $nb_results_par_page]);
$lesResultatsSansPagination = Imprimante::sansReleves3Mois($params);

require_once 'templates' . DIRECTORY_SEPARATOR . 'copieurs.php';