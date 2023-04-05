<?php
use App\AjaxController;
$ajax = new AjaxController(2);

## Total number of records without filtering
$total_records = $ajax->getNbRecordsWithoutFiltering("SELECT count(*) as allcount FROM copieurs");

// Comptage du nombre total de résultats correspondants à la recherche
$total_filtered = $ajax->getNbRecordsFiltered("SELECT COUNT(*) as allcount FROM copieurs WHERE `N° de Série` LIKE :search_value");

// Construction de la requête SQL avec une requête préparée
$query = "SELECT `N° ORDO` as num_ordo, `N° de Série` as num_serie, `Modele demandé` as modele, `STATUT PROJET` as statut, `BDD` as bdd, `Site d'installation` as site_installation
        FROM copieurs
        WHERE `N° de Série` LIKE :search_value";
$results = $ajax->getRecords($query);

die($ajax->output($total_records, $total_filtered, $results));