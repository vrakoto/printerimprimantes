<?php
use App\AjaxController;
$ajax = new AjaxController(2);

$total_records = $ajax->getNbRecordsWithoutFiltering("SELECT count(*) as allcount FROM compteurs");

$total_filtered = $ajax->getNbRecordsFiltered("SELECT count(*) as allcount FROM compteurs WHERE `Numéro_série` LIKE :search_value");

$query = "SELECT Numéro_série as num_serie,
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
$results = $ajax->getRecords($query);

die($ajax->output($total_records, $total_filtered, $results));