<?php
namespace App;
use PDO;
use PDOException;

class Driver {
    protected static PDO $pdo;

    static function getPDO(): PDO
    {
        return self::$pdo = new PDO('mysql:dbname=sapollonv2;host=localhost', 'root', null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    static function estConnecte(): bool
    {
        return !empty($_SESSION['user']);
    }

    static function downloadCSV(array $headers, string $filename, array $results)
    {
        $champs = '';
        foreach ($headers as $nom_input => $props) {
            $champs .= $props['libelle'] . ";";
        }
        $filename = $filename . '.csv';
        $output = fopen("php://output", "w");
        $separator = ';';

        // Header en première ligne
        $header_row = explode($separator, $champs);
        fputcsv($output, $header_row, $separator);

        foreach ($results as $row) {
            fputcsv($output, $row, $separator);
        }
        
        fclose($output);
        
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        
        readfile('php://output');
        exit();
    }

    static function addLogs($action): bool
    {
        try {
            $query = "INSERT INTO `logs` (`id_profil`, `action`) VALUES (:id_profil, :action)";
            $p = self::$pdo->prepare($query);
            $p->execute([
                'id_profil' => User::getMonID(),
                'action' => $action
            ]);
        } catch (PDOException $th) {
        }
        return true;
    }
}