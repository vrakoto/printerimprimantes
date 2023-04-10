<?php
use App\User;
use App\AjaxController;
$ajax = new AjaxController(2);

$bdd = User::getBDD();

$query_total_records = "SELECT COUNT(DISTINCT `N° de Série`) as allcount FROM copieurs c
                        WHERE BDD = '$bdd'
                        AND `STATUT PROJET` LIKE '1 - LIVRE'
                        AND `N° de Série` NOT IN
                        (
                                SELECT Numéro_série FROM compteurs_trimestre ct
                                WHERE BDD = '$bdd'
                                GROUP BY ct.Numéro_série
                        )";
$total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);

$query_total_filtered = "SELECT COUNT(DISTINCT `N° de Série`) as allcount FROM copieurs c
                        WHERE c.BDD = '$bdd'
                        AND c.`STATUT PROJET` LIKE '1 - LIVRE'
                        AND c.`N° de Série` NOT IN
                        (
                                SELECT Numéro_série FROM compteurs_trimestre ct
                                WHERE ct.BDD = '$bdd'
                                GROUP BY ct.Numéro_série
                        )
                        AND c.`N° de Série` LIKE :search_value";
$total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);


$query = "SELECT c.`N° ORDO` as num_ordo, c.`N° de Série` as num_serie, c.`Modele demandé` as modele, c.`STATUT PROJET` as statut, c.`BDD` as bdd, c.`Site d'installation` as site_installation
        FROM copieurs c
        WHERE c.BDD = '$bdd'
        AND c.`STATUT PROJET` LIKE '1 - LIVRE'
        AND c.`N° de Série` NOT IN
        (
                SELECT Numéro_série FROM compteurs_trimestre ct
                WHERE ct.BDD = '$bdd'
                GROUP BY ct.Numéro_série
        )
        AND c.`N° de Série` LIKE :search_value";
$results = $ajax->getRecords($query);

die($ajax->output($total_records, $total_filtered, $results));