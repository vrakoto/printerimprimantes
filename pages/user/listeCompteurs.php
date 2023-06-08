<?php

use App\Compteur;

$title = "Liste des Compteurs";
$url = 'liste_compteurs';
$perimetre = false;
$laTable = Compteur::testChamps($perimetre);

$order = getValeurInput('order', 'num_serie');
$ordertype = getValeurInput('ordertype', 'ASC');

foreach ($laTable as $key => $value) {
    $laTable[$key] = array_merge($value, [
        'value' => getValeurInput($value['nom_input']),
        'valuePosition' => getValeurInput($value['nom_input']) . '%'
    ]);
}
$laTable['bdd'] = ['valuePosition' => getValeurInput('bdd'), 'anti_ambiguous' => 'c'];
$laTable['date'] = ['valuePosition' => getValeurInput('date')];
$laTable['total_101'] = ['valuePosition' => getValeurInput('total_101')];
$laTable['total_112'] = ['valuePosition' => getValeurInput('total_112')];
$laTable['total_113'] = ['valuePosition' => getValeurInput('total_113')];
$laTable['total_122'] = ['valuePosition' => getValeurInput('total_122')];
$laTable['total_123'] = ['valuePosition' => getValeurInput('total_123')];
$laTable['modif_par'] = ['valuePosition' => '%' .getValeurInput('modif_par') . '%'];
$laTable['date_maj'] = ['valuePosition' => getValeurInput('date_maj')];
$laTable['order'] = ['nom_db' => $order, 'value' => $ordertype];


// l'utilisateur a fait une recherche
$laTable_query = [];
foreach ($laTable as $nom_input => $props) {
    if ($nom_input === 'order') {
        $laTable_query['order'] = $props['nom_db'];
        $laTable_query['ordertype'] = $ordertype;
    } else if (!empty($props['value'])) {
        $laTable_query[$nom_input] = $props['value'];
    }
}
$fullURL = http_build_query($laTable_query);

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
    $lesResultats = Compteur::getLesReleves($params, $perimetre, [$debut, $nb_results_par_page]);
    $lesResultatsSansPagination = Compteur::getLesReleves($params, $perimetre);
    $laTable = Compteur::testChamps($perimetre);
    require_once 'templates' . DIRECTORY_SEPARATOR . 'compteurs.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}