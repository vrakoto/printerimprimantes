<?php
namespace App;
use PDO;

class Driver {
    protected static PDO $pdo;

    static function getPDO(): PDO
    {
        return self::$pdo = new PDO('mysql:dbname=new_spl;host=localhost', 'root', null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    static function estConnecte(): bool
    {
        return !empty($_SESSION['user']);
    }
}