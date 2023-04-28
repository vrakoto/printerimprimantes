<?php

use App\User;
use App\AjaxController;

$ajax = new AjaxController(3);

$bdd = User::getBDD();

$query_total_records = "SELECT count(*) as allcount FROM users_copieurs uc
                        JOIN profil p on uc.responsable = p.id_profil WHERE p.BDD = '$bdd'";

$query_total_filtered = "SELECT count(*) as allcount FROM users_copieurs uc
                        JOIN profil p on uc.responsable = p.id_profil
                        WHERE p.BDD = '$bdd'  AND (`grade-prenom-nom` LIKE :search_value OR `numéro_série` LIKE :search_value)";


$query_results = "SELECT `grade-prenom-nom` as gpn, numéro_série as num_serie
        FROM users_copieurs uc
        JOIN profil p on uc.responsable = p.id_profil
        WHERE 1 AND p.BDD = '$bdd'
        AND (`grade-prenom-nom` LIKE :search_value OR `numéro_série` LIKE :search_value)";

if (isset($_GET['csv'], $_GET['search_value'])) {
    $search_value = htmlentities($_GET['search_value']);
    $csv = $ajax->test($query_results, $search_value, 'users_copieurs');
    die(json_encode($csv));
} else {
    $total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);
    $total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);
    $results = $ajax->getRecords($query_results);
    die($ajax->output($total_records, $total_filtered, $results));
}