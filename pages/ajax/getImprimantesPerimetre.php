<?php
use App\User;
use App\AjaxController;

$ajax = new AjaxController(2);

$id_profil = User::getMonID();

$role = User::getRole();
$bdd = User::getBDD();
if ($role === 2) { // Si COORDSIC
    $query_total_records = "SELECT COUNT(*) as allcount FROM copieurs
                            WHERE BDD = '$bdd'
                            AND `STATUT PROJET` LIKE '1 - LIVRE'"; 

    $query_total_filtered = "SELECT COUNT(*) as allcount FROM copieurs c
                            WHERE BDD = '$bdd'
                            AND `STATUT PROJET` LIKE '1 - LIVRE'
                            AND `N° de Série` LIKE :search_value";

    $query_results = "SELECT `N° ORDO` as num_ordo, 
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
                    WHERE BDD = '$bdd'
                    AND `STATUT PROJET` LIKE '1 - LIVRE'
                    AND `N° de Série` LIKE :search_value";
} else {
    $query_total_records = "SELECT COUNT(*) as allcount FROM copieurs
                            WHERE `N° de Série` IN
                                (SELECT `numéro_série` FROM users_copieurs
                                WHERE responsable = $id_profil)
                            -- AND `STATUT PROJET` LIKE '1 - LIVRE'
                            ";

    $query_total_filtered = "SELECT COUNT(*) as allcount
                            FROM copieurs c
                            WHERE `N° de Série` IN
                                (SELECT `numéro_série` FROM users_copieurs
                                WHERE responsable = $id_profil)
                            AND `N° de Série` LIKE :search_value";
    
    
    $query_results = "SELECT `N° ORDO` as num_ordo, 
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
                    WHERE `N° de Série` IN
                        (SELECT `numéro_série` FROM users_copieurs
                        WHERE responsable = $id_profil)
                    AND `N° de Série` LIKE :search_value";
}

if (isset($_GET['csv'], $_GET['search_value'])) {
    $search_value = htmlentities($_GET['search_value']);
    $csv = $ajax->test($query_results, $search_value, 'copieurs');
    die(json_encode($csv));
} else {
    $total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);
    $total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);
    $results = $ajax->getRecords($query_results);
    die($ajax->output($total_records, $total_filtered, $results));
}