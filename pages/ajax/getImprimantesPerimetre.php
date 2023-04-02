<?php

use App\Imprimante;
use App\User;
use App\UsersCopieurs;

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

    $id_profil = User::getMonID();

    ## Search
    $searchQuery = " ";
    $option = [];
    if (!empty($searchValue)) {
        $searchQuery = " AND (`N° de Série` LIKE :num_serie) ";
        $option = ['num_serie' => '%' . $searchValue . '%'];
    }

    ## Total number of records without filtering
    $sel = $pdo->query("SELECT count(*) as allcount FROM copieurs c
                        JOIN users_copieurs uc on `numéro_série` = `N° de Série`
                        WHERE responsable = $id_profil");
    $records = $sel->fetch();
    $totalRecords = $records['allcount'];

    ## Total number of records with filtering
    $sel = $pdo->prepare("SELECT count(*) as allcount FROM copieurs
                        JOIN users_copieurs uc on `numéro_série` = `N° de Série`
                        WHERE responsable = $id_profil " . $searchQuery);
    $sel->execute($option);
    $records = $sel->fetch();
    $totalRecordwithFilter = $records['allcount'];
    

    ## Fetch records
    $empQuery = "SELECT `N° de Série` as num_serie, `Modele demandé` as modele, `STATUT PROJET` as statut, `BDD` as bdd, `Site d'installation` as site_installation FROM copieurs 
                JOIN users_copieurs uc on `numéro_série` = `N° de Série`
                WHERE responsable = $id_profil " . $searchQuery . " 
                ORDER BY " . $columnName . " " . $columnSortOrder . " limit " . $row . "," . $rowperpage;
    $empRecords = $pdo->prepare($empQuery);
    $empRecords->execute($option);
    $data = [];

    while ($row = $empRecords->fetch()) {
        $data[] = [
            "num_serie" => $row['num_serie'],
            "modele" => $row['modele'],
            "statut" => $row['statut'],
            "bdd" => $row['bdd'],
            "site_installation" => $row['site_installation']
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