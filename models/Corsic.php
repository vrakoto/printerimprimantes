<?php
namespace App;

class Corsic extends User
{
    static function copieursPerimetrePasDansMaListe(): array
    {
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