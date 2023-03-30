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

        $req = "SELECT `$champ_num_serie_imprimante` FROM copieurs
                WHERE $champ_bdd_imprimante = :bdd
                AND `$champ_num_serie_imprimante` NOT IN
                    (SELECT $champ_num_serie_users_copieurs FROM users_copieurs
                    WHERE $champ_id_user_users_copieurs = :id_profil)
                ORDER BY `$champ_num_serie_imprimante` ASC";

        $p = self::$pdo->prepare($req);
        $p->execute([
            'bdd' => self::getBDD(),
            'id_profil' => self::getMonID()
        ]);
        return $p->fetchAll();
    }

    static function ajouterDansPerimetre($num_serie): bool
    {
        $query = "INSERT INTO users_copieurs
        (" . UsersCopieurs::getChamps('champ_id_user') . "," . UsersCopieurs::getChamps('champ_num_serie') . ")
        VALUES
        (:id_profil, :num_serie)";

        $p = self::$pdo->prepare($query);
        return $p->execute([
            'id_profil' => self::getMonID(),
            'num_serie' => $num_serie
        ]);
    }

    static function retirerDansPerimetre($num_serie): bool
    {
        $query = "DELETE FROM users_copieurs WHERE " . UsersCopieurs::getChamps('champ_id_user') . " = :id_user AND " . UsersCopieurs::getChamps('champ_num_serie') . " = :num_serie";
        $p = self::$pdo->prepare($query);
        return $p->execute([
            'id_user' => self::getMonID(),
            'num_serie' => $num_serie
        ]);
    }
}
