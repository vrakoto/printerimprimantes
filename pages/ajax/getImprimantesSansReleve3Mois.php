<?php
use App\User;
use App\AjaxController;
$ajax = new AjaxController(2);

$bdd = User::getBDD();

$query_total_records = "SELECT COUNT(*) as allcount FROM copieurs c
                        LEFT JOIN (
                            SELECT Numéro_série, MAX(date_maj) as dernier_releve
                            FROM compteurs
                            WHERE BDD = '$bdd'
                            GROUP BY Numéro_série
                        ) co ON c.`N° de Série` = co.Numéro_série
                        WHERE c.`STATUT PROJET` = '1 - LIVRE'
                        AND c.BDD = '$bdd'
                        AND (co.dernier_releve < DATE_SUB(NOW(), INTERVAL 3 MONTH) OR co.dernier_releve IS NULL)";
$total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);

$query_total_filtered = "SELECT COUNT(*) as allcount FROM copieurs c
                        LEFT JOIN (
                            SELECT Numéro_série, MAX(date_maj) as dernier_releve
                            FROM compteurs
                            WHERE BDD = '$bdd'
                            GROUP BY Numéro_série
                        ) co ON c.`N° de Série` = co.Numéro_série
                        WHERE c.`STATUT PROJET` = '1 - LIVRE'
                        AND c.BDD = '$bdd'
                        AND (co.dernier_releve < DATE_SUB(NOW(), INTERVAL 3 MONTH) OR co.dernier_releve IS NULL)
                        AND c.`N° de Série` LIKE :search_value";
$total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);


$query = "SELECT c.`N° ORDO` as num_ordo, c.`N° de Série` as num_serie, c.`Modele demandé` as modele, c.`STATUT PROJET` as statut, c.`BDD` as bdd, c.`Site d'installation` as site_installation 
        FROM copieurs c
        LEFT JOIN (
            SELECT Numéro_série, MAX(date_maj) as dernier_releve
            FROM compteurs
            WHERE BDD = '$bdd'
            GROUP BY Numéro_série
        ) co ON c.`N° de Série` = co.Numéro_série
        WHERE c.`STATUT PROJET` = '1 - LIVRE'
        AND c.BDD = 'BSL'
        AND (co.dernier_releve < DATE_SUB(NOW(), INTERVAL 3 MONTH) OR co.dernier_releve IS NULL)
        AND c.`N° de Série` LIKE :search_value";
$results = $ajax->getRecords($query);

die($ajax->output($total_records, $total_filtered, $results));