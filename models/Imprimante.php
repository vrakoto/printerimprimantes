<?php
namespace App;

class Imprimante extends Driver {
    protected static $champ_num_ordo = 'num_ordo';
    protected static $champ_num_serie = 'num_serie';
    protected static $champ_modele = 'modele';
    protected static $champ_date_cde_minarm = 'date_cde_minarm';
    protected static $champ_num_oracle = 'num_oracle';
    protected static $champ_config = 'Config';
    protected static $champ_hostname = 'HostName';
    protected static $champ_mac = 'adresse_mac';
    protected static $champ_reseau = 'reseau';
    protected static $champ_cp = 'CP INSTA';
    protected static $champ_dep = 'DEP INSTA';
    protected static $champ_adresse = 'adresse';
    protected static $champ_localisation = 'localisation';
    protected static $champ_statut = 'statut';
    protected static $champ_bdd = 'bdd';
    protected static $champ_site_installation = 'site_installation';
    protected static $champ_date_ajout = 'date_ajout';

    static function getChamps($champ): string
    {
        return self::$$champ;
    }

    static function ChampsCopieur(): string
    {
        return <<<HTML
        <thead>
            <tr>
                <th></th>
                <th>Numéro Série</th>
                <th>Modèle</th>
                <th>Statut Projet</th>
                <th>BDD</th>
                <th>Site d'installation</th>
                <th>Date d'ajout</th>
            </tr>
        </thead>
HTML;
    }

    static function ValeursCopieur(array|string $lesCopieurs): void
    {
        $num_serie = htmlentities($lesCopieurs[self::$champ_num_serie]);
        $modele = htmlentities($lesCopieurs[self::$champ_modele]);
        $statut = htmlentities($lesCopieurs[self::$champ_statut]);
        $bdd = htmlentities($lesCopieurs[self::$champ_bdd]);
        $site_installation = htmlentities($lesCopieurs[self::$champ_site_installation]);
        $date_ajout = convertDate(htmlentities($lesCopieurs[self::$champ_date_ajout]));

        echo <<<HTML
        <tr>
            <td class="dt-control"></td>
            <td><a href="imprimante/$num_serie">$num_serie</a></td>
            <td>$modele</td>
            <td>$statut</td>
            <td>$bdd</td>
            <td>$site_installation</td>
            <td>$date_ajout</td>
        </tr>
HTML;
    }

    /**
     * Récupère une imprimante spécifique
     */
    static function getImprimante($num_serie): array
    {
        $req = "SELECT * FROM copieurs WHERE " . self::$champ_num_serie . " LIKE :num_serie";
        $p = self::$pdo->prepare($req);
        $p->execute(['num_serie' => '%' . $num_serie . '%']);
        if ($p->rowCount() > 0) {
            return $p->fetch();
        }
        return [];
    }

    /**
     * Récupère une liste d'imprimantes en fonction d'une chaine de caractère
     * 
     */
    static function searchImprimante($num_serie): array
    {
        $req = "SELECT * FROM copieurs WHERE " . self::$champ_num_serie . " LIKE :num_serie";
        $p = self::$pdo->prepare($req);
        $p->execute(['num_serie' => '%' . $num_serie . '%']);
        return $p->fetchAll();
    }

    static function getImprimantes(): array
    {
        $req = "SELECT * FROM copieurs";
        $p = self::$pdo->query($req);
        return $p->fetchAll();
    }

    static function getImprimantesParBDD($bdd): array
    {
        $req = "SELECT * FROM copieurs WHERE " . self::$champ_bdd . " = :bdd";
        $p = self::$pdo->prepare($req);
        $p->execute(['bdd' => $bdd]);
        return $p->fetchAll();
    }

    static function sansResponsable($bdd = ''): array
    {
        $champ_num_serie = self::$champ_num_serie;
        $query = "SELECT * FROM copieurs WHERE " . $champ_num_serie . " NOT IN (SELECT " . $champ_num_serie . " FROM users_copieurs)";
        $options = [];
        if ($bdd !== '') {
            $query = "SELECT * FROM copieurs WHERE " . self::$champ_bdd . " = :bdd AND " . $champ_num_serie . " NOT IN (SELECT " . $champ_num_serie . " FROM users_copieurs)";
            $options = ['bdd' => $bdd];
        }
        $p = self::$pdo->prepare($query);
        $p->execute($options);

        return $p->fetchAll();
    }

    static function sansReleves3Mois($bdd = ''): array
    {
        $champ_num_serie = self::$champ_num_serie;
        $query = "SELECT * FROM copieurs WHERE " . self::$champ_statut .  " = 'LIVRE' AND " . $champ_num_serie . " NOT IN ( SELECT " . $champ_num_serie . " FROM compteurs WHERE date_maj >= DATE_SUB(NOW(), INTERVAL 3 MONTH) )"; 
        $options = [];
        if ($bdd !== '') {
            $query = "SELECT * FROM copieurs WHERE " . self::$champ_statut .  " = 'LIVRE' AND " . self::$champ_bdd . " = :bdd" . " AND " . $champ_num_serie . " NOT IN ( SELECT " . $champ_num_serie . " FROM compteurs WHERE date_maj >= DATE_SUB(NOW(), INTERVAL 3 MONTH) )"; 
            $options = ['bdd' => $bdd];
        }
        $p = self::$pdo->prepare($query);
        $p->execute($options);

        return $p->fetchAll();
    }
}