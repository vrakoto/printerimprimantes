<?php

use App\User;

$host = "localhost";
$user = "root";
$password = "";
$dbname = "sapollonv2";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    ## Read value
    $draw = $_GET['draw'];
    $row = (int)$_GET['start'];
    $rowperpage = (int)$_GET['length']; // Rows display per page
    $columnIndex = (int)$_GET['order'][0]['column']; // Column index
    $columnName = htmlentities($_GET['columns'][$columnIndex]['data']); // Column name
    $columnSortOrder = $_GET['order'][0]['dir']; // asc or desc
    $searchValue = htmlentities($_GET['search']['value']); // Search value

    $bdd = User::getBDD();

    ## Search
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " AND (`grade-prenom-nom` LIKE '%" . $searchValue . "%') OR (`numéro_série` LIKE '%" . $searchValue . "%') ";
    }

    ## Total number of records without filtering
    $sel = $pdo->query("SELECT count(*) as allcount FROM users_copieurs uc
                        JOIN profil p on uc.responsable = p.id_profil WHERE p.BDD = '$bdd'");
    $records = $sel->fetch();
    $totalRecords = $records['allcount'];

    ## Total number of records with filtering
    $sel = $pdo->query("SELECT count(*) as allcount FROM users_copieurs uc
                        JOIN profil p on uc.responsable = p.id_profil WHERE p.BDD = '$bdd' " . $searchQuery);
    $records = $sel->fetch();
    $totalRecordwithFilter = $records['allcount'];

    ## Fetch records
    $empQuery = "SELECT `grade-prenom-nom` as gpn, numéro_série as num_serie FROM users_copieurs uc
                JOIN profil p on uc.responsable = p.id_profil WHERE p.BDD = '$bdd' "
                . $searchQuery . "
                ORDER BY " . $columnName . " " . $columnSortOrder . " limit " . $row . "," . $rowperpage;
    $empRecords = $pdo->query($empQuery);
    $data = [];

    while ($row = $empRecords->fetch()) {
        $data[] = [
            "gpn" => ($row['gpn']),
            "num_serie" => ($row['num_serie'])
        ];
    }

    ## Response
    $response = [
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    ];

    die(json_encode($response));
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}