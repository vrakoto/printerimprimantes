<?php
namespace App;
use PDO;
use App\User;

class AjaxController extends Driver {
    private $position;

    function __construct(int $position)
    {
        $this->position = $position;
    }
    
    function getPosition(): string
    {
        switch ($this->position) {
            case 1:
                return '%' . $this->getProperty()['search_value'];
            break;

            case 2:
                return $this->getProperty()['search_value'] . '%';
            break;

            case 3:
                return '%' . $this->getProperty()['search_value'] . '%';
            break;

            case 4:
                return $this->getProperty()['search_value'];
            break;
        }
    }

    function getPositionCSV($search_value): string
    {
        switch ($this->position) {
            case 1:
                return '%' . $search_value;
            break;

            case 2:
                return $search_value . '%';
            break;

            case 3:
                return '%' . $search_value . '%';
            break;

            case 4:
                return $search_value;
            break;
        }
    }

    private function getProperty(): array
    {
        $array = [
            'draw' => $_GET['draw'],
            'start' => $_GET['start'],
            'length' => $_GET['length'],
            'order_column_index' => $_GET['order'][0]['column'],
            'order_column_name' => $_GET['columns'][$_GET['order'][0]['column']]['data'],
            'order_direction' => $_GET['order'][0]['dir'],
            'search_value' => htmlentities($_GET['search']['value'])
        ];

        return $array; 
    }
    
    // Total number of records without filtering
    function getNbRecordsWithoutFiltering($query):int
    {
        $sel = self::$pdo->query($query);
        $records = $sel->fetch();
        return $records['allcount'];
    }

    // Comptage du nombre total de résultats correspondants à la recherche
    function getNbRecordsFiltered($query): int
    {
        $stmt_count = self::$pdo->prepare($query);
        $stmt_count->bindValue(':search_value', $this->getPosition(), PDO::PARAM_STR);
        $stmt_count->execute();
        $row_count = $stmt_count->fetch();
        return $row_count['allcount'];
    }

    // Construction de la requête SQL avec une requête 
    function getRecords($query): array
    {
        $fullQuery = $query . " ORDER BY " . $this->getProperty()['order_column_name'] . " " . $this->getProperty()['order_direction'] . " LIMIT :start, :length";
        $stmt = self::$pdo->prepare($fullQuery);
        $stmt->bindValue(':search_value', $this->getPosition(), PDO::PARAM_STR);
        $stmt->bindValue(':start', $this->getProperty()['start'], PDO::PARAM_INT);
        $stmt->bindValue(':length', $this->getProperty()['length'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    function test($query, $search_value, $table): array
    {
        $stmt = self::$pdo->prepare($query);
        $columns = self::$pdo->query("SHOW columns FROM $table");
        $stmt->execute(['search_value' => $this->getPositionCSV($search_value)]);
        $results = $stmt->fetchAll();

        // ajout des entetes
        $datas = [];
        $lesColonnes = [];
        foreach ($columns->fetchAll() as $column) {
            $lesColonnes[] = $column['Field'];
        }
        $datas[] = $lesColonnes;
    
        foreach ($results as $result) {
            $datas[] = array_values($result);
        }
    
        return $datas;
    }

    function output($total_records, $total_filtered, $results)
    {
        $output = [
            "draw" => $this->getProperty()['draw'],
            "recordsTotal" => $total_records,
            "recordsFiltered" => $total_filtered,
            "data" => []
        ];
        
        foreach ($results as $row) {
            $output['data'][] = $row;
        }
        
        return json_encode($output);
    }

    function removeCopieurPerimetre($num_serie): bool
    {
        $id_profil = User::getMonID();
        $query = "DELETE FROM users_copieurs WHERE responsable = :id_profil AND `numéro_série` LIKE :num_serie";
        $p = self::$pdo->prepare($query);
        return $p->execute([
            'id_profil' => $id_profil,
            'num_serie' => $num_serie
        ]);
    }
}