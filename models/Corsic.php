<?php
namespace App;
use App\Imprimante;

class Corsic extends User
{
    static function copieursPerimetrePasDansMaListe(): array
    {
        $req = "SELECT " . Imprimante::getChamps('champ_num_serie') . " FROM copieurs
                WHERE " . Imprimante::getChamps('champ_bdd') . " = :bdd
                AND " . Imprimante::getChamps('champ_num_serie') . " NOT IN
                    (SELECT num_serie FROM users_copieurs
                    WHERE id_user = :id_profil)
                ORDER BY num_serie ASC";

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
        (id_user, num_serie)
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
        $query = "DELETE FROM users_copieurs WHERE id_user = :id_user AND num_serie = :num_serie";
        $p = self::$pdo->prepare($query);
        return $p->execute([
            'id_user' => self::getMonID(),
            'num_serie' => $num_serie
        ]);
    }
}
