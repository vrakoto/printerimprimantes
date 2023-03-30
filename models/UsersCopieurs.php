<?php
namespace App;

class UsersCopieurs {
    private static $champ_id_user = 'responsable';
    private static $champ_num_serie = 'numéro_série';

    static function getChamps($champ): string
    {
        return self::$$champ;
    }
}