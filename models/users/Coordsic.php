<?php
namespace App\Users;

use App\Users\User;

class Coordsic extends User {
    static function inscrireCopieur($num_serie, $modele, $bdd, $site_insta): bool
    {
        $query = "INSERT INTO copieurs
        (num_serie, modele, bdd, site_installation)
        VALUES
        (:num_serie, :modele, :bdd, :site_installation)";

        $p = self::$pdo->prepare($query);
        return $p->execute([
            'num_serie' => $num_serie,
            'modele' => $modele,
            'bdd' => $bdd,
            'site_installation' => $site_insta
        ]); 
    }

    static function ajouterReleve($num_serie, $date_releve, $total_112, $total_113, $total_122, $total_123, $type_releve): bool
    {
        $query = "INSERT INTO compteurs
        (num_serie, bdd, date_releve, 112_total, 113_total, 122_total, 123_total, modif_par, type_releve)
        VALUES
        (:num_serie, :bdd, :date_releve, :112_total, :113_total, :122_total, :123_total, :modif_par, :type_releve)";

        $p = self::$pdo->prepare($query);
        return $p->execute([
            'num_serie' => $num_serie,
            'bdd' => self::getBDD(),
            'date_releve' => $date_releve,
            '112_total' => $total_112,
            '113_total' => $total_113,
            '122_total' => $total_122,
            '123_total' => $total_123,
            'modif_par' => self::getMonID(),
            'type_releve' => $type_releve
        ]);
    }
}