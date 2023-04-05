<?php
use App\User;
use App\AjaxController;
$ajax = new AjaxController(3);

$bdd = User::getBDD();

$total_records = $ajax->getNbRecordsWithoutFiltering("SELECT COUNT(*) as allcount FROM profil WHERE BDD = '$bdd'");

$total_filtered = $ajax->getNbRecordsFiltered("SELECT COUNT(*) as allcount FROM profil WHERE BDD = '$bdd' AND `grade-prenom-nom` LIKE :search_value");

$query = "SELECT p.`grade-prenom-nom` as gpn, p.`BDD` as bdd, p.`Courriel` as courriel, ul.userlevelname as role, p.`Unité` as unite
        FROM profil p
        JOIN userlevels ul on ul.userlevelid = p.role
        WHERE BDD = '$bdd'
        AND `grade-prenom-nom` LIKE :search_value";
$results = $ajax->getRecords($query);

die($ajax->output($total_records, $total_filtered, $results));