<?php
namespace App;

class BdD extends Driver {
    private static $trigramme = 'BDD';

    static function getChampBDD(): string
    {
        return self::$trigramme;
    }

    static function getTousLesBDD(): array
    {
        $req = "SELECT * FROM bdd ORDER BY 1";
        $p = self::$pdo->query($req);
        return $p->fetchAll();
    }
}