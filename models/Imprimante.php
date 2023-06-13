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

    static function ChampsCopieur($perimetre = false, string $getAllColumns = 'few'): array
    {
        $headers = [
            "num_ordo" => ['nom_input' => "num_ordo", 'nom_db' => "N° ORDO", 'libelle' => "N° ORDO", 'display' => true],
            "num_serie" => ['nom_input' => "num_serie", 'nom_db' => "N° de Série", 'libelle' => "N° de Série", 'display' => true],
            "bdd" => ['nom_input' => "bdd", 'nom_db' => "BDD", 'libelle' => "BDD", 'display' => true],
            "statut_projet" => ['nom_input' => "statut_projet", 'nom_db' => "STATUT PROJET", 'libelle' => "Statut Projet", 'display' => true],
            "modele" => ['nom_input' => "modele", 'nom_db' => "Modele demandé", 'libelle' => "Modèle", 'display' => true],
            "config" => ['nom_input' => "config", 'nom_db' => "Config", 'libelle' => "Config", 'display' => true],
            "date_cde_minarm" => ['nom_input' => "date_cde_minarm", 'nom_db' => "DATE CDE MINARM", 'libelle' => "DATE CDE MINARM"],
            "num_sfdc" => ['nom_input' => "num_sfdc", 'nom_db' => "N° OPP SFDC", 'libelle' => "N° OPP SFDC"],
            "num_oracle" => ['nom_input' => "num_oracle", 'nom_db' => "N° Saisie ORACLE", 'libelle' => "N° Oracle"],
            "hostname" => ['nom_input' => "hostname", 'nom_db' => "HostName", 'libelle' => "HostName"],
            "reseau" => ['nom_input' => "reseau", 'nom_db' => "réseau", 'libelle' => "Réseau"],
            "adresse_mac" => ['nom_input' => "adresse_mac", 'nom_db' => "MAC@", 'libelle' => "Adresse MAC"],
            "entite_beneficiaire" => ['nom_input' => "entite_beneficiaire", 'nom_db' => "Entité Bénéficiaire", 'libelle' => "Entité Bénéficiaire"],
            "credo_unite" => ['nom_input' => "credo_unite", 'nom_db' => "credo_unité", 'libelle' => "Credo Unité"],
            "cp_insta" => ['nom_input' => "cp_insta", 'nom_db' => "CP INSTA", 'libelle' => "Code Postal", 'display' => true],
            "dep_insta" => ['nom_input' => "dep_insta", 'nom_db' => "DEP INSTA", 'libelle' => "Code Départemental"],
            "adresse" => ['nom_input' => "adresse", 'nom_db' => "adresse", 'libelle' => "Adresse"],
            "site_installation" => ['nom_input' => "site_installation", 'nom_db' => "Site d'installation", 'libelle' => "Site d'installation", 'display' => true],
            "localisation" => ['nom_input' => "localisation", 'nom_db' => "localisation", 'libelle' => "Localisation"],
            "service_uf" => ['nom_input' => "service_uf", 'nom_db' => "ServiceUF", 'libelle' => "Service UF"],
            "accessoires" => ['nom_input' => "accessoires", 'nom_db' => "Accessoires", 'libelle' => "Accessoires"]
        ];

        if ($getAllColumns === 'few') {
            $headers = array_filter($headers, function($header) {
                return isset($header['display']);
            });
        }

        if ($perimetre) {
            unset($headers['bdd']);
        }
        return $headers;
    }

    /**
     * Récupère une imprimante spécifique
     */
    static function getImprimante($num): array
    {
        $champ_num_serie = self::$champ_num_serie;
        $req = "SELECT * FROM copieurs WHERE `$champ_num_serie` = :num_serie";
        $p = self::$pdo->prepare($req);
        $p->execute(['num_serie' => $num]);
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

    static function getImprimantes(array $params, bool $enableLimit = true): array
    {        
        $where = '';
        $options = [];
        $ordering = '';
        foreach ($params as $nom_input => $props) {
            $value = $props['value'];
            if (trim($value) !== '' && isset($props['nom_db'])) {
                $nom_db = $props['nom_db'];
                
                if ($nom_input !== 'order') {
                    $where .= " AND `$nom_db` LIKE :$nom_input";
                    $options[$nom_input] = $props['valuePosition'];
                } else {
                    // order
                    $ordering = ' ORDER BY `' . $nom_db . '` ' . $value;
                }
            }
        }
        $limit = '';
        if ($enableLimit) {
            $debut = (int)$params['debut']['value'];
            $nb_results_page = (int)$params['nb_results_page']['value'];
            $limit = "LIMIT $debut, $nb_results_page";
        }
        
        $sql = "SELECT ";

        foreach (self::ChampsCopieur(false, $_SESSION['showColumns']) as $nom_input => $props) {
            $nom_db = $props['nom_db'];
            $sql .= " `$nom_db` as $nom_input,";
        }

        // Suppression de la virgule pour la dernière ligne
        $sql = rtrim($sql, ',');

        $sql .= " FROM copieurs
                WHERE 1 $where
                $ordering
                $limit";
        $p = self::$pdo->prepare($sql);
        $p->execute($options);
        return $p->fetchAll();
    }

    static function copieursPerimetre(array $params, bool $enableLimit = true): array
    {
        $where = '';
        $options = [];
        $ordering = '';
        foreach ($params as $nom_input => $props) {
            $value = $props['value'];
            if (trim($value) !== '' && isset($props['nom_db'])) {
                $nom_db = $props['nom_db'];
                
                if ($nom_input !== 'order') {
                    $where .= " AND `$nom_db` LIKE :$nom_input";
                    $options[$nom_input] = $props['valuePosition'];
                } else {
                    // order
                    $ordering = ' ORDER BY `' . $nom_db . '` ' . $value;
                }
            }
        }

        $limit = '';
        if ($enableLimit) {
            $debut = (int)$params['debut']['value'];
            $nb_results_page = (int)$params['nb_results_page']['value'];
            $limit = "LIMIT $debut, $nb_results_page";
        }
        $sql = "SELECT ";

        foreach (self::ChampsCopieur(true, $_SESSION['showColumns']) as $nom_input => $props) {
            $nom_db = $props['nom_db'];
            $sql .= " `$nom_db` as $nom_input,";
        }

        // Suppression de la virgule pour la dernière ligne
        $sql = rtrim($sql, ',');

        $sql .= " FROM copieurs c";
        if (User::getRole() === 2) {
            $where .= " AND BDD = :bdd";
            $options['bdd'] = User::getBDD();
        } else {
            $sql .= " JOIN users_copieurs uc on uc.`numéro_série` = c.`N° de Série`";
            $where .= " AND responsable = :responsable";
            $options['responsable'] = User::getMonID();
        }
        $sql .= " WHERE 1
                $where
                $ordering
                $limit";

        $p = self::$pdo->prepare($sql);
        $p->execute($options);
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

    static function sansResponsable(array $params, bool $enableLimit = true): array
    {
        $where = '';
        $options = [];
        $ordering = '';
        foreach ($params as $nom_input => $props) {
            $value = $props['value'];
            if (trim($value) !== '' && isset($props['nom_db'])) {
                $nom_db = $props['nom_db'];
                
                if ($nom_input !== 'order') {
                    $where .= " AND `$nom_db` LIKE :$nom_input";
                    $options[$nom_input] = $props['valuePosition'];
                } else {
                    // order
                    $ordering = ' ORDER BY `' . $nom_db . '` ' . $value;
                }
            }
        }

        $limit = '';
        if ($enableLimit) {
            $debut = (int)$params['debut']['value'];
            $nb_results_page = (int)$params['nb_results_page']['value'];
            $limit = "LIMIT $debut, $nb_results_page";
        }
        $sql = "SELECT ";

        foreach (self::ChampsCopieur(true, $_SESSION['showColumns']) as $nom_input => $props) {
            $nom_db = $props['nom_db'];
            $sql .= " `$nom_db` as $nom_input,";
        }

        // Suppression de la virgule pour la dernière ligne
        $sql = rtrim($sql, ',');

        $sql .= " FROM copieurs
                WHERE BDD = :bdd AND `N° de Série` NOT IN (SELECT `numéro_série` FROM users_copieurs)
                $where
                $ordering
                $limit";

        $options['bdd'] = User::getBDD();
        $p = self::$pdo->prepare($sql);
        $p->execute($options);
        return $p->fetchAll();
    }

    static function sansReleves3Mois(array $params, bool $enableLimit = true): array
    {
        $where = '';
        $options = [];
        $ordering = '';
        foreach ($params as $nom_input => $props) {
            $value = $props['value'];
            if (trim($value) !== '' && isset($props['nom_db'])) {
                $nom_db = $props['nom_db'];
                
                if ($nom_input !== 'order') {
                    $where .= " AND `$nom_db` LIKE :$nom_input";
                    $options[$nom_input] = $props['valuePosition'];
                } else {
                    // order
                    $ordering = ' ORDER BY `' . $nom_db . '` ' . $value;
                }
            }
        }

        $limit = '';
        if ($enableLimit) {
            $debut = (int)$params['debut']['value'];
            $nb_results_page = (int)$params['nb_results_page']['value'];
            $limit = "LIMIT $debut, $nb_results_page";
        }
        $sql = "SELECT ";

        foreach (self::ChampsCopieur(true, $_SESSION['showColumns']) as $nom_input => $props) {
            $nom_db = $props['nom_db'];
            $sql .= " `$nom_db` as $nom_input,";
        }

        // Suppression de la virgule pour la dernière ligne
        $sql = rtrim($sql, ',');

        $sql .= " FROM copieurs
                WHERE `STATUT PROJET` LIKE '1 - LIVRE' AND BDD = :bdd AND `N° de Série` NOT IN (SELECT `Numéro_série` FROM compteurs_trimestre)
                $where
                $ordering
                $limit";

        $options['bdd'] = User::getBDD();
        $p = self::$pdo->prepare($sql);
        $p->execute($options);
        return $p->fetchAll();
    }

    static function getLesStatuts(): array
    {
        $query = "SELECT * FROM statut_projet"; 
        $p = self::$pdo->query($query);
        return $p->fetchAll();
    }

    static function modifierImprimante(string $num_serie, array $params): bool
    {
        foreach ($params as $nom_input => $props) {
            $nom_db = $props['nom_db'];
            $value = $props['value'];

            $query = "UPDATE `copieurs` SET `$nom_db` = :new_value WHERE `N° de Série` = :num_serie";
            $p = self::$pdo->prepare($query);
            return $p->execute([
                'new_value' => $value,
                'num_serie' => $num_serie,
            ]);
        }
    }
}