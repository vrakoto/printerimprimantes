<?php

use App\Imprimante;

$title = "Copieurs sans relevé ce Trimestre";
$url = 'copieurs-sans-releve-trimestre'; // url actuel de la vue
$perimetre = true;
$laTable = Imprimante::testChamps($perimetre);

$order = getValeurInput('order', 'num_serie');
$ordertype = getValeurInput('ordertype', 'ASC');

foreach ($laTable as $key => $value) {
    $laTable[$key] = array_merge($value, [
        'value' => getValeurInput($value['nom_input']),
        'valuePosition' => getValeurInput($value['nom_input']) . '%'
    ]);
}
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
    header('Location:/' . $match['name']);
    exit();
}

$debut = ($page - 1) * $nb_results_par_page;

try {
    $lesResultats = Imprimante::sansReleves3Mois($params, [$debut, $nb_results_par_page]);
    $lesResultatsSansPagination = Imprimante::sansReleves3Mois($params);
    require_once 'templates' . DIRECTORY_SEPARATOR . 'copieurs.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}