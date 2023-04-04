<?php
namespace App;

use App\User;

class Coordsic extends User {
    static function inscrireCopieur($num_ordo, $num_serie, $modele, $bdd, $site_insta): bool
    {
        $champ_num_ordo_imprimante = Imprimante::getChamps('champ_num_ordo');
        $champ_num_serie_imprimante = Imprimante::getChamps('champ_num_serie');
        $champ_modele_imprimante = Imprimante::getChamps('champ_modele');
        $champ_bdd_imprimante = Imprimante::getChamps('champ_bdd');
        $champ_site_installation_imprimante = Imprimante::getChamps('champ_site_installation');

        $query = "INSERT INTO copieurs
        (`$champ_num_ordo_imprimante`,`$champ_num_serie_imprimante`,`$champ_modele_imprimante`,$champ_bdd_imprimante,`$champ_site_installation_imprimante`)
        VALUES
        (:num_ordo, :num_serie, :modele, :bdd, :site_installation)";

        $p = self::$pdo->prepare($query);
        return $p->execute([
            'num_ordo' => $num_ordo,
            'num_serie' => $num_serie,
            'modele' => $modele,
            'bdd' => $bdd,
            'site_installation' => $site_insta
        ]); 
    }
}