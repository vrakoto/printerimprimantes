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
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$id_profil = User::getMonID();

// Récupération des paramètres de pagination et de recherche
$draw = $_GET['draw'];
$start = $_GET['start'];
$length = $_GET['length'];

$order_column_index = $_GET['order'][0]['column'];
$order_column_name = $_GET['columns'][$order_column_index]['data'];
$order_direction = $_GET['order'][0]['dir'];

$search_value = htmlentities($_GET['search']['value']);

## Total number of records without filtering
$sel = $pdo->query("SELECT COUNT(*) as allcount FROM copieurs");
$records = $sel->fetch();
$total_records = $records['allcount'];


// Comptage du nombre total de résultats correspondants à la recherche
$stmt_count = $pdo->prepare("SELECT COUNT(*) as allcount FROM copieurs
                            JOIN users_copieurs uc on `numéro_série` = `N° de Série`
                            WHERE responsable = $id_profil
                            AND `numéro_série` LIKE :search_value");
$stmt_count->bindValue(':search_value', "{$search_value}%", PDO::PARAM_STR);
$stmt_count->execute();
$row_count = $stmt_count->fetch();
$total_filtered = $row_count['allcount'];

// Construction de la requête SQL avec une requête préparée
$stmt = $pdo->prepare("SELECT c.`N° ORDO` as num_ordo, c.`N° de Série` as num_serie, c.`Modele demandé` as modele, c.`STATUT PROJET` as statut, c.`BDD` as bdd, c.`Site d'installation` as site_installation FROM copieurs c
                    JOIN users_copieurs uc on `numéro_série` = `N° de Série`
                    WHERE responsable = $id_profil
                    AND `numéro_série` LIKE :search_value
                    ORDER BY `$order_column_name` $order_direction
                    LIMIT :start, :length");
$stmt->bindValue(':search_value', "{$search_value}%", PDO::PARAM_STR);
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':length', $length, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll();


$output = [
    "draw" => intval($draw),
    "recordsTotal" => $total_records,
    "recordsFiltered" => $total_filtered,
    "data" => []
];

foreach ($result as $row) {
    $output['data'][] = $row;
}

die(json_encode($output));