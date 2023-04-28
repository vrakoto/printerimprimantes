<?php

use App\User;
use App\AjaxController;

$ajax = new AjaxController(3);

$bdd = User::getBDD();

$query_total_records = "SELECT COUNT(*) as allcount FROM profil WHERE BDD = '$bdd'";

$query_total_filtered = "SELECT COUNT(*) as allcount FROM profil WHERE BDD = '$bdd' AND `grade-prenom-nom` LIKE :search_value";

$query_results = "SELECT p.`grade-prenom-nom` as gpn, p.`BDD` as bdd, p.`Courriel` as courriel, ul.userlevelname as role, p.`Unité` as unite
        FROM profil p
        JOIN userlevels ul on ul.userlevelid = p.role
        WHERE BDD = '$bdd'
        AND `grade-prenom-nom` LIKE :search_value";

if (isset($_GET['csv'], $_GET['search_value'])) {
    $search_value = htmlentities($_GET['search_value']);
    $csv = $ajax->test($query_results, $search_value, 'profil');
    die(json_encode($csv));
} else {
    $total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);
    $total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);
    $results = $ajax->getRecords($query_results);
    die($ajax->output($total_records, $total_filtered, $results));
}