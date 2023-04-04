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

    ## Search
    $searchQuery = " ";
    $option = [];
    if (!empty($searchValue)) {
        $searchQuery = " AND (`Numéro_série` LIKE :num_serie) ";
        $option = ['num_serie' => $searchValue . '%'];
    }

    ## Total number of records without filtering
    $id = User::getMonID();
    $sel = $pdo->query("SELECT count(*) as allcount FROM compteurs
                        WHERE modif_par IN
                        (SELECT responsable FROM users_copieurs
                        WHERE responsable = '$id')");
    $records = $sel->fetch();
    $totalRecords = $records['allcount'];

    ## Total number of records with filtering
    $sel = $pdo->prepare("SELECT count(*) as allcount FROM compteurs
                        WHERE modif_par IN
                        (SELECT responsable FROM users_copieurs
                        WHERE responsable = '$id') " . $searchQuery);
    $sel->execute($option);
    $records = $sel->fetch();
    $totalRecordwithFilter = $records['allcount'];
    

    ## Fetch records
    $empQuery = "SELECT `Numéro_série` as num_serie,
                BDD as bdd,
                Date as date_releve,
                `101_Total_1` as total_101,
                `112_Total` as total_112,
                `113_Total` as total_113,
                `122_Total` as total_122,
                `123_Total` as total_123,
                modif_par, date_maj,
                type_relevé as type_releve
                FROM compteurs 
                WHERE modif_par IN
                (SELECT responsable FROM users_copieurs
                WHERE responsable = '$id') " . $searchQuery . " 
                ORDER BY " . $columnName . " " . $columnSortOrder . " limit " . $row . "," . $rowperpage;
    $empRecords = $pdo->prepare($empQuery);
    $empRecords->execute($option);
    $data = [];

    while ($row = $empRecords->fetch()) {
        $num_serie = htmlentities($row['num_serie']);
        $data[] = [
            "num_serie" => "<a href='imprimante/$num_serie'>$num_serie</a>",
            "bdd" => $row['bdd'],
            "date_releve" => convertDate($row['date_releve']),
            "total_101" => $row['total_101'],
            "total_112" => $row['total_112'],
            "total_113" => $row['total_113'],
            "total_122" => $row['total_122'],
            "total_123" => $row['total_123'],
            "modif_par" => $row['modif_par'],
            "date_maj" => convertDate($row['date_maj'], true),
            "type_releve" => $row['type_releve']
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