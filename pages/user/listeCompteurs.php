<?php

use App\Compteur;

$title = "Liste des Compteurs";
$url = 'liste_compteurs';

$order = getValeurInput('order', 'date_maj');

$searching_num_serie = getValeurInput('num_serie');
$searching_bdd = getValeurInput('bdd');
$searching_date = getValeurInput('date');
$searching_101 = getValeurInput('total_101');
$searching_112 = getValeurInput('total_112');
$searching_113 = getValeurInput('total_113');
$searching_122 = getValeurInput('total_122');
$searching_123 = getValeurInput('total_123');
// $searching_modif_par = getValeurInput('modif_par');
// $searching_date_maj = getValeurInput('date_maj');
$searching_type_releve = getValeurInput('type_releve');

$params = [
    'num_serie' => ['nom_db' => "Numéro_série", 'value' => $searching_num_serie, 'valuePosition' => $searching_num_serie . '%'],
    'bdd' => ['nom_db' => "BDD", 'value' => $searching_bdd, 'valuePosition' => $searching_bdd],
    'date' => ['nom_db' => "Date", 'value' => $searching_date, 'valuePosition' => $searching_date],
    'total_101' => ['nom_db' => "101_Total_1", 'value' => $searching_101, 'valuePosition' => $searching_101],
    'total_112' => ['nom_db' => "112_Total", 'value' => $searching_112, 'valuePosition' => $searching_112],
    'total_113' => ['nom_db' => "113_Total", 'value' => $searching_113, 'valuePosition' => $searching_113],
    'total_122' => ['nom_db' => "122_Total", 'value' => $searching_122, 'valuePosition' => $searching_122],
    'total_123' => ['nom_db' => "123_Total", 'value' => $searching_123, 'valuePosition' => $searching_123],
    // 'modif_par' => ['nom_db' => "grade-prenom-nom", 'value' => $searching_modif_par, 'valuePosition' => $searching_modif_par . '%'],
    // 'date_maj' => ['nom_db' => "date_maj", 'value' => $searching_date_maj, 'valuePosition' => $searching_date_maj],
    'type_releve' => ['nom_db' => "type_relevé", 'value' => $searching_type_releve, 'valuePosition' => $searching_type_releve],
    'order' => ['nom_db' => $order, 'value' => 'DESC']
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

$lesResultats = Compteur::getLesReleves($params, false, [$debut, $nb_results_par_page]);
$lesResultatsSansPagination = Compteur::getLesReleves($params, false);

require_once 'templates' . DIRECTORY_SEPARATOR . 'compteurs.php';