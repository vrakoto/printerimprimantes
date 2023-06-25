<?php

$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

// Toutes les tables hormis gestion des utilisateurs ont un champ en commun : "num_serie"
if (!isset($defaultOrder)) {
    $defaultOrder = 'num_serie';
}
if (!isset($defaultOrderType)) {
    $defaultOrderType = 'ASC';
}
$order = getValeurInput('order', $defaultOrder);
$ordertype = getValeurInput('ordertype', $defaultOrderType);

foreach ($laTable as $nom_input => $value) {
    $laTable[$nom_input]['value'] = getValeurInput($nom_input);
}

// l'utilisateur a fait une recherche
$laTable_query = [];
foreach ($laTable as $nom_input => $props) {
    if (!empty($props['value'])) {
        $laTable_query[$nom_input] = $props['value'];
    }
}

$fullURL = http_build_query($laTable_query) . '&order=' . $order . '&ordertype=' . $ordertype;

$laTable['order'] = ['nom_db' => $order, 'value' => $ordertype];
$laTable['page'] = ['value' => $page];
$laTable['debut'] = ['value' => (($page - 1) * $nb_results_par_page)];
$laTable['nb_results_page'] = ['value' => $nb_results_par_page];

if ($page <= 0) {
    header('Location:/' . $url);
    exit();
}