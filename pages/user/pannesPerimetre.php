<?php
use App\Panne;

$title = "Pannes du périmètre";
$url = 'pannes_perimetre';
$perimetre = true;
$nb_results_par_page = 10;

$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$order = getValeurInput('order', 'ouverture');
$ordertype = getValeurInput('ordertype', 'DESC');

$laTable = Panne::ChampPannes();

foreach ($laTable as $key => $value) {
    $valuePosition = getValeurInput($key);

    // Ajouter des valeurs spécifiques pour chaque clé
    switch ($key) {
        case 'commentaires':
            $valuePosition = '%' . getValeurInput('commentaires') . '%';
        break;
    }

    $laTable[$key] = array_merge($value, [
        'value' => getValeurInput($key),
        'valuePosition' => $valuePosition . '%'
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

$laTable['page'] = ['value' => $page];
$laTable['debut'] = ['value' => (($page - 1) * $nb_results_par_page)];
$laTable['nb_results_page'] = ['value' => $nb_results_par_page];


if ($page <= 0) {
    header('Location:/' . $url);
    exit();
}


try {
    $lesResultats = Panne::getLesPannes($laTable, $perimetre);
    $lesResultatsSansPagination = Panne::getLesPannes($laTable, $perimetre, false);
    require_once 'templates' . DIRECTORY_SEPARATOR . 'pannes.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}