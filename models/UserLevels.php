<?php
namespace App;

class UserLevels extends Driver {
    private static $champ_id = 'userlevelid';
    private static $champ_name = 'userlevelname';

    static function getChamps($champ): string
    {
        return self::$$champ;
    }

    static function getTable(): array
    {
        $req = "SELECT * FROM userlevels WHERE `userlevelid` BETWEEN 1 AND 3";
        $p = self::$pdo->query($req);

        return $p->fetchAll();
    }
}