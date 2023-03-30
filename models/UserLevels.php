<?php
namespace App;

class UserLevels {
    private static $champ_id = 'userlevelid';
    private static $champ_name = 'userlevelname';

    static function getChamps($champ): string
    {
        return self::$$champ;
    }
}