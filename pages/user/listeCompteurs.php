<?php

use App\Compteur;

$title = "Liste des Compteurs";
$url = 'liste_compteurs';
$perimetre = false;
$nb_results_par_page = 10;

$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$order = getValeurInput('order', 'num_serie');
$ordertype = getValeurInput('ordertype', 'ASC');
$showColumns = $_SESSION['showColumns'];

$laTable = Compteur::testChamps($perimetre);

foreach ($laTable as $key => $value) {
    $nom_input = $value['nom_input'];
    $valuePosition = getValeurInput($value['nom_input']);

    // Ajouter des valeurs spécifiques pour chaque clé
    switch ($nom_input) {
        case 'modif_par':
            $valuePosition = '%' . getValeurInput('modif_par') . '%';
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

$laTable['page'] = ['value' => $page];
$laTable['debut'] = ['value' => (($page - 1) * $nb_results_par_page)];
$laTable['nb_results_page'] = ['value' => $nb_results_par_page];

if ($page <= 0) {
    header('Location:/' . $url);
    exit();
}

try {
    $lesResultats = Compteur::getLesReleves($laTable, $perimetre);
    $lesResultatsSansPagination = Compteur::getLesReleves($laTable, $perimetre, false);
    require_once 'templates' . DIRECTORY_SEPARATOR . 'compteurs.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}