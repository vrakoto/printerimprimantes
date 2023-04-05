<?php
use App\User;
use App\AjaxController;
$ajax = new AjaxController(2);

$id_profil = User::getMonID();

$total_records = $ajax->getNbRecordsWithoutFiltering("SELECT COUNT(*) as allcount FROM copieurs");

$query_total_filtered = "SELECT COUNT(*) as allcount FROM copieurs
                        JOIN users_copieurs uc on `numéro_série` = `N° de Série`
                        WHERE responsable = $id_profil
                        AND `numéro_série` LIKE :search_value";
$total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);


$query = "SELECT c.`N° ORDO` as num_ordo, c.`N° de Série` as num_serie, c.`Modele demandé` as modele, c.`STATUT PROJET` as statut, c.`BDD` as bdd, c.`Site d'installation` as site_installation
        FROM copieurs c
        JOIN users_copieurs uc on `numéro_série` = `N° de Série`
        WHERE responsable = $id_profil
        AND `numéro_série` LIKE :search_value";
$results = $ajax->getRecords($query);

die($ajax->output($total_records, $total_filtered, $results));