<?php
namespace App;
use App\Imprimante;
use App\UsersCopieurs;

class Corsic extends User
{
    static function copieursPerimetrePasDansMaListe(): array
    {
        $champ_num_serie_imprimante = Imprimante::getChamps('champ_num_serie');
        $champ_bdd_imprimante = Imprimante::getChamps('champ_bdd');
        $champ_num_serie_users_copieurs = UsersCopieurs::getChamps('champ_num_serie');
        $champ_id_user_users_copieurs = UsersCopieurs::getChamps('champ_id_user');

        // $req = "SELECT `$champ_num_serie_imprimante` FROM copieurs
        //         WHERE $champ_bdd_imprimante = :bdd
        //         AND `$champ_num_serie_imprimante` NOT IN
        //             (SELECT $champ_num_serie_users_copieurs FROM users_copieurs
        //             WHERE $champ_id_user_users_copieurs = :id_profil)
        //         ORDER BY `$champ_num_serie_imprimante` ASC";

        $req = "SELECT `N° de Série` as num_serie FROM copieurs c
                WHERE `BDD` = :bdd
                AND `N° de Série` NOT IN
                    (SELECT `numéro_série` FROM users_copieurs
                    WHERE `responsable` = :id_profil)
                ORDER BY num_serie ASC";

        $p = self::$pdo->prepare($req);
        $p->execute([
            'bdd' => self::getBDD(),
            'id_profil' => self::getMonID()
        ]);
        return $p->fetchAll();
    }
}