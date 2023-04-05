<?php
use App\User;
use App\AjaxController;
$ajax = new AjaxController(3);

$bdd = User::getBDD();

$query_total_records = "SELECT count(*) as allcount FROM users_copieurs uc
                        JOIN profil p on uc.responsable = p.id_profil";
$total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);

$query_total_filtered = "SELECT count(*) as allcount FROM users_copieurs uc
                        JOIN profil p on uc.responsable = p.id_profil
                        WHERE (`grade-prenom-nom` LIKE :search_value) OR (`numéro_série` LIKE :search_value)";
$total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);


$query = "SELECT `grade-prenom-nom` as gpn, numéro_série as num_serie
        FROM users_copieurs uc
        JOIN profil p on uc.responsable = p.id_profil
        WHERE (`grade-prenom-nom` LIKE :search_value) OR (`numéro_série` LIKE :search_value)";
$results = $ajax->getRecords($query);

die($ajax->output($total_records, $total_filtered, $results));