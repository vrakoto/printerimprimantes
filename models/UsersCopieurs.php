<?php
namespace App;

use App\User;

class UsersCopieurs extends Driver {
    private static $champ_id_user = 'responsable';
    private static $champ_num_serie = 'numéro_série';

    static function getChamps($champ): string
    {
        return self::$$champ;
    }

    static function ChampUsersCopieurs(): string
    {
        return <<<HTML
        <thead>
            <tr>
                <th>Grade Nom Prénom</th>
                <th>Numéro de Série</th>
            </tr>
        </thead>
HTML;
    }

    static function ValuesUsersCopieurs($responsable): void
    {
        $gpn = htmlentities($responsable['gpn']);
        $num_serie = htmlentities($responsable['num_serie']);

        echo <<<HTML
        <tr>
            <td>$gpn</td>
            <td><a href="/imprimante/$num_serie">$num_serie</a></td>
        </tr>
HTML;
    }

    static function getResponsables($bdd = NULL): array
    {
        $champ_id_user_users_copieurs = self::$champ_id_user;
        $champ_num_serie_users_copieurs = self::$champ_num_serie;
        $champ_gpn_user = User::getChamp('champ_gpn');
        $champ_id_user = User::getChamp('champ_id');
        $champ_bdd_user = User::getChamp('champ_bdd');

        $option = [];
        $req = "SELECT p.`$champ_gpn_user` as gpn, `$champ_num_serie_users_copieurs` as num_serie FROM users_copieurs uc
                JOIN profil p on uc.`$champ_id_user_users_copieurs` = p.`$champ_id_user`";

        if ($bdd !== NULL) {
            $req .= "WHERE `$champ_bdd_user` = :bdd";
            $option['bdd'] = User::getBDD();
        }
        $p = self::$pdo->prepare($req);
        $p->execute($option);

        return $p->fetchAll();
    }
}