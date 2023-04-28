<?php

use App\AjaxController;

$ajax = new AjaxController(2);

$query_total_records = "SELECT count(*) as allcount FROM compteurs";
$query_total_filtered = "SELECT count(*) as allcount FROM compteurs WHERE `Numéro_série` LIKE :search_value";

$query_results = "SELECT Numéro_série as num_serie,
        c.BDD as bdd,
        Date as date_releve,
        `101_Total_1` as total_101,
        `112_Total` as total_112,
        `113_Total` as total_113,
        `122_Total` as total_122,
        `123_Total` as total_123,
        `grade-prenom-nom` as modif_par,
        date_maj,
        type_relevé as type_releve
        FROM compteurs c
        LEFT JOIN profil p on p.id_profil = c.modif_par
        WHERE `Numéro_série` LIKE :search_value";

if (isset($_GET['csv'], $_GET['search_value'])) {
    $search_value = htmlentities($_GET['search_value']);
    $csv = $ajax->test($query_results, $search_value, 'compteurs');
    die(json_encode($csv));
} else {
    $total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);
    $total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);
    $results = $ajax->getRecords($query_results);
    die($ajax->output($total_records, $total_filtered, $results));
}