<?php
use App\AjaxController;
$ajax = new AjaxController(2);

## Total number of records without filtering
$total_records = $ajax->getNbRecordsWithoutFiltering("SELECT count(*) as allcount FROM copieurs");

// Comptage du nombre total de résultats correspondants à la recherche
$total_filtered = $ajax->getNbRecordsFiltered("SELECT COUNT(*) as allcount FROM copieurs WHERE `N° de Série` LIKE :search_value");

// Construction de la requête SQL avec une requête préparée
$query = "SELECT `N° ORDO` as num_ordo, 
        `N° de Série` as num_serie, 
        `Modele demandé` as modele, 
        `STATUT PROJET` as statut, 
        `BDD` as bdd, 
        `Site d'installation` as site_installation,
        `DATE CDE MINARM` as date_cde_minarm,
        `Config` as config,
        `N° Saisie ORACLE` as num_oracle,
        `N° OPP SFDC` as num_sfdc,
        `HostName` as hostname,
        `réseau` as reseau,
        `MAC@` as adresse_mac,
        `Entité Bénéficiaire` as entite_beneficiaire,
        `credo_unité` as credo_unite,
        `CP INSTA` as cp_insta,
        `DEP INSTA` as dep_insta,
        `adresse` as adresse,
        `localisation` as localisation,
        `ServiceUF` as service_uf,
        `Accessoires` as accessoires
        FROM copieurs
        WHERE `N° de Série` LIKE :search_value";
$results = $ajax->getRecords($query);

die($ajax->output($total_records, $total_filtered, $results));