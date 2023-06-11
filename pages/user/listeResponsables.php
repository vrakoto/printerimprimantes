<?php

use App\UsersCopieurs;

$title = "Liste des Responsables";
$url = "liste-responsables";
$laTable = UsersCopieurs::testChamps();

$order = getValeurInput('order', 'gpn');
$ordertype = getValeurInput('ordertype', 'DESC');

foreach ($laTable as $key => $value) {
    $nom_input = $value['nom_input'];
    $valuePosition = getValeurInput($nom_input) . '%';

    switch ($nom_input) {
        case 'gpn':
            $valuePosition = '%' . getValeurInput('gpn') . '%';
        break;
    }

    $laTable[$key] = array_merge($value, [
        'value' => getValeurInput($nom_input),
        'valuePosition' => $valuePosition
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
    header('Location:/' . $url);
    exit();
}

$debut = ($page - 1) * $nb_results_par_page;

$lesResultats = UsersCopieurs::getResponsables($laTable, false, [$debut, $nb_results_par_page]);
$lesResultatsSansPagination = UsersCopieurs::getResponsables($laTable, false);

require_once 'templates' . DIRECTORY_SEPARATOR . 'responsables.php';