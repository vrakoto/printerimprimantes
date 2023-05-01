<?php
namespace App;

class Imprimante extends Driver {
    protected static $champ_num_ordo = 'N° ORDO';
    protected static $champ_num_serie = 'N° de Série';
    protected static $champ_bdd = 'BDD';
    protected static $champ_modele = 'Modele demandé';
    protected static $champ_date_cde_minarm = 'DATE CDE MINARM';
    protected static $champ_num_oracle = 'N° Saisie ORACLE';
    protected static $champ_config = 'Config';
    protected static $champ_hostname = 'HostName';
    protected static $champ_mac = 'MAC@';
    protected static $champ_reseau = 'réseau';
    protected static $champ_cp = 'CP INSTA';
    protected static $champ_dep = 'DEP INSTA';
    protected static $champ_adresse = 'adresse';
    protected static $champ_localisation = 'localisation';
    protected static $champ_statut = 'STATUT PROJET';
    protected static $champ_site_installation = "Site d'installation";
    protected static $champ_entite_beneficiaire = "Entité Bénéficiaire";
    protected static $champ_credo_unite = "credo_unité";
    protected static $champ_accessoires = "Accessoires";
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
                <th id="num_serie">Numéro Série</th>
                <th id="bdd">BDD</th>
                <th id="modele">Modèle</th>
                <th id="statut">Statut Projet</th>
                <th id="site_installation">Site d'installation</th>
                <th id="num_ordo">N° ORDO</th>
                <th id="date_cde_minarm">DATE CDE MINARM</th>
                <th id="config">Config</th>
                <th id="num_oracle">N° Oracle</th>
                <th id="num_sfdc">N° OPP SFDC</th>
                <th id="hostname">HostName</th>
                <th id="reseau">Réseau</th>
                <th id="adresse_mac">Adresse MAC@</th>
                <th id="entite_beneficiaire">Entité Bénéficiaire</th>
                <th id="localisation">Localisation</th>
                <th id="cp_insta">CP Insta</th>
                <th id="dep_insta">DEP Insta</th>
                <th id="adresse">Adresse</th>
                <th id="credo_unite">Credo Unité</th>
                <th id="service_uf">ServiceUF</th>
                <th id="accessoires">Accessoires</th>
            </tr>
        </thead>
HTML;
    }

    static function editImprimante($num,$oracle,$config,$modele,$hostname,$reseau,$mac,$entite_beneficiaire,$credo_unite, int $cp, int $dep,$adresse,$site_installation,$localisation,$accessoires): bool
    {
        $req = "UPDATE `copieurs` SET
        `N° Saisie ORACLE`= :oracle,
        `Config`= :config,
        `Modele demandé`= :modele,
        `HostName`= :hostname,
        `réseau`= :reseau,
        `MAC@`= :mac,
        `Entité Bénéficiaire`= :entite_beneficiaire,
        `credo_unité`= :credo_unite,
        `CP INSTA`= :cp,
        `DEP INSTA`= :dep,
        `adresse`= :adresse,
        `Site d'installation`= :site_installation,
        `localisation`= :localisation,
        `last_user`= :id_profil,
        `Accessoires`= :accessoires
        WHERE `N° de Série` = :num_serie";
        $p = self::$pdo->prepare($req);
        
        return $p->execute([
            'num_serie' => $num,
            'oracle' => $oracle,
            'config' => $config,
            'modele' => $modele,
            'hostname' => $hostname,
            'reseau' => $reseau,
            'mac' => $mac,
            'entite_beneficiaire' => $entite_beneficiaire,
            'credo_unite' => $credo_unite,
            'cp' => (int)$cp,
            'dep' => (int)$dep,
            'adresse' => $adresse,
            'site_installation' => $site_installation,
            'localisation' => $localisation,
            'accessoires' => $accessoires,
            'id_profil' => User::getMonID()
        ]);
    }

    /**
     * Récupère une imprimante spécifique
     */
    static function getImprimante($num_serie): array
    {
        $champ_num_serie = self::$champ_num_serie;
        $req = "SELECT * FROM copieurs WHERE `$champ_num_serie` = :num_serie";
        $p = self::$pdo->prepare($req);
        $p->execute(['num_serie' => $num_serie]);
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

    static function getSesResponsables($num_serie): array
    {
        $req = "SELECT `grade-prenom-nom` as responsable, `numéro_série` as num_serie
                FROM users_copieurs
                LEFT JOIN profil on id_profil = `responsable`
                WHERE `numéro_série` = :num_serie";
        $p = self::$pdo->prepare($req);
        $p->execute(['num_serie' => $num_serie]);
        return $p->fetchAll();
    }

    static function sansResponsable($bdd = ''): array
    {
        $champ_num_serie = self::$champ_num_serie;
        $champ_num_serie_users_copieurs = UsersCopieurs::getChamps('champ_num_serie');
        $query = "SELECT * FROM copieurs WHERE `$champ_num_serie` NOT IN (SELECT $champ_num_serie_users_copieurs FROM users_copieurs)";
        $options = [];
        if ($bdd !== '') {
            $query = "SELECT * FROM copieurs WHERE " . self::$champ_bdd . " = :bdd AND `$champ_num_serie` NOT IN (SELECT $champ_num_serie_users_copieurs FROM users_copieurs)";
            $options = ['bdd' => $bdd];
        }
        $p = self::$pdo->prepare($query);
        $p->execute($options);

        return $p->fetchAll();
    }

    static function sansReleves3Mois($bdd = ''): array
    {
        $champ_num_serie = self::$champ_num_serie;
        $champ_statut = self::$champ_statut;
        $query = "SELECT * FROM copieurs WHERE `$champ_statut` = 'LIVRE' AND `$champ_num_serie` NOT IN ( SELECT `$champ_num_serie` FROM compteurs WHERE date_maj >= DATE_SUB(NOW(), INTERVAL 3 MONTH) )"; 
        $options = [];
        if ($bdd !== '') {
            $query = "SELECT * FROM copieurs WHERE `$champ_statut` = 'LIVRE' AND " . self::$champ_bdd . " = :bdd" . " AND `$champ_num_serie` NOT IN ( SELECT `$champ_num_serie` FROM compteurs WHERE date_maj >= DATE_SUB(NOW(), INTERVAL 3 MONTH) )"; 
            $options = ['bdd' => $bdd];
        }
        $p = self::$pdo->prepare($query);
        $p->execute($options);

        return $p->fetchAll();
    }
}