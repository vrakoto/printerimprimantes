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
        $option = ['num_serie' => '%' . $searchValue . '%'];
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
    $empQuery = "SELECT * FROM compteurs 
                WHERE modif_par IN
                (SELECT responsable FROM users_copieurs
                WHERE responsable = '$id') " . $searchQuery . " 
                ORDER BY " . $columnName . " " . $columnSortOrder . " limit " . $row . "," . $rowperpage;
    $empRecords = $pdo->prepare($empQuery);
    $empRecords->execute($option);
    $data = [];

    while ($row = $empRecords->fetch()) {
        $num_serie = htmlentities($row['Numéro_série']);
        $data[] = [
            "num_serie" => "<a href='imprimante/$num_serie'>$num_serie</a>",
            "bdd" => $row['BDD'],
            "date_releve" => convertDate($row['Date']),
            "101" => $row['101_Total_1'],
            "112" => $row['112_Total'],
            "113" => $row['113_Total'],
            "122" => $row['122_Total'],
            "123" => $row['123_Total'],
            "modif_par" => $row['modif_par'],
            "date_maj" => convertDate($row['date_maj'], true),
            "type_releve" => $row['type_relevé']
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