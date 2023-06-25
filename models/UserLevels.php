<?php
namespace App;

class UserLevels extends Driver {
    static function getTable(): array
    {
        $req = "SELECT * FROM userlevels WHERE `userlevelid` BETWEEN 1 AND 3";
        $p = self::$pdo->query($req);

        return $p->fetchAll();
    }
}