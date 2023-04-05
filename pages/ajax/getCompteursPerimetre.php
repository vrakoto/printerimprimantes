<?php
use App\User;
use App\AjaxController;
$ajax = new AjaxController(2);

$id = User::getMonID();

$query_total_records = "SELECT count(*) as allcount FROM compteurs
                        WHERE modif_par IN
                        (SELECT responsable FROM users_copieurs
                        WHERE responsable = '$id')";
$total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);

$query_total_filtered = "SELECT count(*) as allcount FROM compteurs
                        WHERE modif_par IN
                        (SELECT responsable FROM users_copieurs
                        WHERE responsable = '$id')
                        AND `Numéro_série` LIKE :search_value";
$total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);

$query = "SELECT `Numéro_série` as num_serie,
        BDD as bdd,
        Date as date_releve,
        `101_Total_1` as total_101,
        `112_Total` as total_112,
        `113_Total` as total_113,
        `122_Total` as total_122,
        `123_Total` as total_123,
        modif_par, date_maj,
        type_relevé as type_releve
        FROM compteurs 
        WHERE modif_par IN
        (SELECT responsable FROM users_copieurs
        WHERE responsable = '$id')
        AND `Numéro_série` LIKE :search_value";
$results = $ajax->getRecords($query);

die($ajax->output($total_records, $total_filtered, $results));