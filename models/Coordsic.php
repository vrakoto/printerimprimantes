<?php
namespace App;

use App\User;

class Coordsic extends User {
    static function inscrireCopieur($num_serie, $modele, $bdd, $site_insta): bool
    {
        $query = "INSERT INTO copieurs
        (" . Imprimante::getChamps('champ_num_serie') . "," .
        Imprimante::getChamps('champ_modele') . "," .
        Imprimante::getChamps('champ_bdd') . "," . Imprimante::getChamps('champ_site_installation') . ")
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
}