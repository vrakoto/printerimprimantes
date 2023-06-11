<?php
use App\Imprimante;

$title = "Liste des Copieurs";
$url = 'liste_copieurs';
$perimetre = false;
$nb_results_par_page = 10;

$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$order = getValeurInput('order', 'num_serie');
$ordertype = getValeurInput('ordertype', 'ASC');
$showColumns = getValeurInput('showColumns', 'few');

$laTable = Imprimante::ChampsCopieur($perimetre, $showColumns);

foreach ($laTable as $key => $value) {
    $laTable[$key] = array_merge($value, [
        'value' => getValeurInput($value['nom_input']),
        'valuePosition' => getValeurInput($value['nom_input']) . '%'
    ]);
}
$laTable['page'] = ['value' => $page];
$laTable['debut'] = ['value' => (($page - 1) * $nb_results_par_page)];
$laTable['nb_results_page'] = ['value' => $nb_results_par_page];
$laTable['order'] = ['nom_db' => $order, 'value' => $ordertype];
$laTable['showColumns'] = ['value' => $showColumns];


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
unset($laTable_query['page']);
unset($laTable_query['debut']);
unset($laTable_query['nb_results_page']);

$fullURL = http_build_query($laTable_query);

if ($page <= 0) {
    header('Location:/' . $url);
    exit();
}

try {
    $lesResultats = Imprimante::getImprimantes($laTable, true);
    $lesResultatsSansPagination = Imprimante::getImprimantes($laTable, false);
    require_once 'templates' . DIRECTORY_SEPARATOR . 'copieurs.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}