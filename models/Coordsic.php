<?php
namespace App;

use App\User;

class Coordsic extends User {
    static function inscrireCopieur($num_ordo, $num_serie, $modele, $bdd, $site_insta): bool
    {
        $query = "INSERT INTO copieurs
        (`N° ORDO`, `N° de Série`, `Modele demandé`, `BDD`, `Site d'installation`)
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

    static function creerUtilisateur($bdd, $gpn, $courriel, $role, $mdp, $unite): bool
    {
        $query = "INSERT INTO profil
        (BDD, `grade-prenom-nom`, `Courriel`, `role`, `mdp`, `Unité`)
        VALUES
        (:bdd, :gpn, :courriel, :role, :mdp, :unite)";

        $p = self::$pdo->prepare($query);
        $etat = $p->execute([
            'bdd' => $bdd,
            'gpn' => $gpn,
            'courriel' => $courriel,
            'role' => $role,
            'mdp' => $mdp,
            'unite' => $unite
        ]);

        if ($etat) {
            self::addLogs("a créé un nouvel utilisateur : '$courriel'");   
        }

        return $etat;
    }


    static function transfererCopieur($num, $bdd): bool
    {
        $query = "UPDATE `copieurs` SET BDD = :new_bdd WHERE `N° de Série` = :num_serie AND BDD = :old_bdd";
        $old_bdd = Imprimante::getImprimante($num)['bdd'];

        $p = self::$pdo->prepare($query);
        $etat = $p->execute([
            'num_serie' => $num,
            'new_bdd' => $bdd,
            'old_bdd' => $old_bdd
        ]);

        if ($etat) {
            self::addLogs("a transféré le copieur $num vers la nouvelle BdD $bdd");   
        }

        return $etat;
    }

    static function historiqueTransfert($num, $bdd): bool
    {
        $query = "INSERT INTO copieurs_transfert
        (num_serie, old_bdd, new_bdd, modif_par)
        VALUES
        (:num_serie, :old_bdd, :new_bdd, :modif_par)";

        $old_bdd = Imprimante::getImprimante($num)['bdd'];

        $p = self::$pdo->prepare($query);
        return $p->execute([
            'num_serie' => $num,
            'new_bdd' => $bdd,
            'old_bdd' => $old_bdd,
            'modif_par' => User::getMonID()
        ]);
    }

    
    static function getUsers(): array
    {
        $query = "SELECT id_profil, `grade-prenom-nom` as gpn FROM profil WHERE BDD = :bdd AND role <> 2";
        $p = self::$pdo->prepare($query);
        $p->execute([
            'bdd' => User::getBDD()
        ]);
        return $p->fetchAll();
    }

}