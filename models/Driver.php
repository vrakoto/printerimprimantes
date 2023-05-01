<?php
namespace App;
use PDO;

class Driver {
    protected static PDO $pdo;

    static function getPDO(): PDO
    {
        return self::$pdo = new PDO('mysql:dbname=sapollonv2;host=localhost;port=4306', 'root', null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    static function estConnecte(): bool
    {
        return !empty($_SESSION['user']);
    }
}