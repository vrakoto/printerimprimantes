<?php
namespace App;

class BdD extends Driver {
    static function getTousLesBDD(): array
    {
        $req = "SELECT * FROM bdd ORDER BY 1";
        $p = self::$pdo->query($req);
        return $p->fetchAll();
    }
}