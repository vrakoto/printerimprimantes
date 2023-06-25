<?php
namespace App;

class Imprimante extends Driver {
    static function ChampsCopieur($perimetre = false, string $getAllColumns = 'few'): array
    {
        $headers = [
            "num_ordo" => ['nom_db' => "N° ORDO", 'libelle' => "N° ORDO", 'display' => true, 'valuePosition' => 'exact'],
            "num_serie" => ['nom_db' => "N° de Série", 'libelle' => "N° de Série", 'display' => true],
            "bdd" => ['nom_db' => "BDD", 'libelle' => "BDD", 'display' => true, 'valuePosition' => 'exact'],
            "statut_projet" => ['nom_db' => "STATUT PROJET", 'libelle' => "Statut Projet", 'display' => true],
            "modele" => ['nom_db' => "Modele demandé", 'libelle' => "Modèle", 'display' => true],
            "config" => ['nom_db' => "Config", 'libelle' => "Config", 'display' => true],
            "date_cde_minarm" => ['nom_db' => "DATE CDE MINARM", 'libelle' => "DATE CDE MINARM"],
            "num_sfdc" => ['nom_db' => "N° OPP SFDC", 'libelle' => "N° OPP SFDC"],
            "num_oracle" => ['nom_db' => "N° Saisie ORACLE", 'libelle' => "N° Oracle"],
            "hostname" => ['nom_db' => "HostName", 'libelle' => "HostName"],
            "reseau" => ['nom_db' => "réseau", 'libelle' => "Réseau"],
            "adresse_mac" => ['nom_db' => "MAC@", 'libelle' => "Adresse MAC"],
            "entite_beneficiaire" => ['nom_db' => "Entité Bénéficiaire", 'libelle' => "Entité Bénéficiaire"],
            "credo_unite" => ['nom_db' => "credo_unité", 'libelle' => "Credo Unité"],
            "cp_insta" => ['nom_db' => "CP INSTA", 'libelle' => "Code Postal", 'display' => true],
            "dep_insta" => ['nom_db' => "DEP INSTA", 'libelle' => "Code Départemental"],
            "adresse" => ['nom_db' => "adresse", 'libelle' => "Adresse"],
            "site_installation" => ['nom_db' => "Site d'installation", 'libelle' => "Site d'installation", 'display' => true],
            "localisation" => ['nom_db' => "localisation", 'libelle' => "Localisation"],
            "service_uf" => ['nom_db' => "ServiceUF", 'libelle' => "Service UF"],
            "accessoires" => ['nom_db' => "Accessoires", 'libelle' => "Accessoires"]
        ];

        foreach ($headers as $nom_input => $props) {
            if (!isset($headers[$nom_input]['valuePosition'])) {
                $headers[$nom_input]['valuePosition'] = 'right';
            }
        }

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
        $req = "SELECT * FROM copieurs WHERE `N° de Série` = :num_serie";
        $p = self::$pdo->prepare($req);
        $p->execute(['num_serie' => $num]);
        if ($p->rowCount() > 0) {
            return $p->fetch();
        }
        return [];
    }

    static function getImprimantes(array $params, bool $perimetre, bool $enableLimit = true): array
    {
        $where = '';
        $options = [];
        $ordering = '';

        $sql = "SELECT ";
        foreach (self::ChampsCopieur(false, 'all') as $nom_input => $props) {
            $nom_db = $props['nom_db'];
            $sql .= " `$nom_db` as $nom_input,";
        }
        $sql = rtrim($sql, ','); // Suppression de la virgule pour la dernière ligne


        $sql .= " FROM copieurs c";
        if ($perimetre) {
            switch (User::getRole()) {
                case 2:
                    $where .= " AND BDD = :bdd";
                    $options['bdd'] = User::getBDD();
                break;

                default:
                    $sql .= " JOIN users_copieurs uc on uc.`numéro_série` = c.`N° de Série`";
                    $where .= " AND responsable = :responsable";
                    $options['responsable'] = User::getMonID();
                break;
            }
        }


        // WHERE et ORDER
        foreach ($params as $nom_input => $props) {
            $value = $props['value'];
            if (trim($value) && isset($props['nom_db'])) {
                $nom_db = $props['nom_db'];
                
                if ($nom_input !== 'order') {
                    $where .= " AND `$nom_db` LIKE :$nom_input";
                    switch ($props['valuePosition']) {
                        case 'left':
                            $value = '%' . $value;
                        break;

                        case 'right':
                            $value = $value . '%';
                        break;
                    }
                    $options[$nom_input] = $value;
                } else {
                    // order, $value = ASC || DESC
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
        
        
        $sql .= " WHERE 1
                $where
                $ordering
                $limit";
        $p = self::$pdo->prepare($sql);
        $p->execute($options);
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

        $sql = "SELECT ";
        foreach (self::ChampsCopieur(true, 'all') as $nom_input => $props) {
            $nom_db = $props['nom_db'];
            $sql .= " `$nom_db` as $nom_input,";
        }
        $sql = rtrim($sql, ','); // Suppression de la virgule pour la dernière ligne


        // WHERE et ORDER
        foreach ($params as $nom_input => $props) {
            $value = $props['value'];
            if (trim($value) !== '' && isset($props['nom_db'])) {
                $nom_db = $props['nom_db'];
                
                if ($nom_input !== 'order') {
                    $where .= " AND `$nom_db` LIKE :$nom_input";
                    switch ($props['valuePosition']) {
                        case 'left':
                            $value = '%' . $value;
                        break;

                        case 'right':
                            $value = $value . '%';
                        break;
                    }
                    $options[$nom_input] = $value;
                } else {
                    // order, $value = ASC || DESC
                    $ordering = ' ORDER BY `' . $nom_db . '` ' . $value;
                }
            }
        }


        // LIMIT
        $limit = '';
        if ($enableLimit) {
            $debut = (int)$params['debut']['value'];
            $nb_results_page = (int)$params['nb_results_page']['value'];
            $limit = "LIMIT $debut, $nb_results_page";
        }
        
        // Assemblage de la requete finale
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

        $sql = "SELECT ";
        foreach (self::ChampsCopieur(true, 'all') as $nom_input => $props) {
            $nom_db = $props['nom_db'];
            $sql .= " `$nom_db` as $nom_input,";
        }
        $sql = rtrim($sql, ','); // Suppression de la virgule pour la dernière ligne


        // WHERE et ORDER
        foreach ($params as $nom_input => $props) {
            $value = $props['value'];
            if (trim($value) !== '' && isset($props['nom_db'])) {
                $nom_db = $props['nom_db'];
                
                if ($nom_input !== 'order') {
                    $where .= " AND `$nom_db` LIKE :$nom_input";
                    switch ($props['valuePosition']) {
                        case 'left':
                            $value = '%' . $value;
                        break;

                        case 'right':
                            $value = $value . '%';
                        break;
                    }
                    $options[$nom_input] = $value;
                } else {
                    // order, $value = ASC || DESC
                    $ordering = ' ORDER BY `' . $nom_db . '` ' . $value;
                }
            }
        }


        // LIMIT
        $limit = '';
        if ($enableLimit) {
            $debut = (int)$params['debut']['value'];
            $nb_results_page = (int)$params['nb_results_page']['value'];
            $limit = "LIMIT $debut, $nb_results_page";
        }
        // Assemblage de la requete finale
        $sql .= " FROM copieurs WHERE `STATUT PROJET` = '1 - LIVRE'
                AND BDD = :bdd
                AND `N° de Série` NOT IN (SELECT Numéro_série FROM compteurs_trimestre)
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

    static function ChampsTransfert(): array
    {
        $headers = [
            "num_serie" => ['nom_db' => "num_serie", 'libelle' => "N° de Série", "valuePosition" => "right"],
            "old_bdd" => ['nom_db' => "old_bdd", 'libelle' => "Ancienne BDD", "valuePosition" => "exact"],
            "new_bdd" => ['nom_db' => "new_bdd", 'libelle' => "Nouvelle BDD", "valuePosition" => "exact"],
            "modif_par" => ['nom_db' => "grade-prenom-nom", 'libelle' => "Transféré par", "valuePosition" => "right"],
            "date" => ['nom_db' => "date", 'libelle' => "Date du transfert", "valuePosition" => "exact"]
        ];

        return $headers;
    }

    static function getLesTransferts(array $params, bool $enableLimit = true): array
    {
        $where = '';
        $options = [];
        $ordering = '';

        $sql = "SELECT ";
        foreach (self::ChampsTransfert() as $nom_input => $props) {
            $nom_db = $props['nom_db'];
            $sql .= " `$nom_db` as $nom_input,";
        }
        $sql = rtrim($sql, ',');

        foreach ($params as $nom_input => $props) {
            $value = $props['value'];
            if (trim($value) !== '' && isset($props['nom_db'])) {
                $nom_db = $props['nom_db'];
                
                if ($nom_input !== 'order') {
                    $where .= " AND `$nom_db` LIKE :$nom_input";
                    if ($props['valuePosition'] === 'right') {
                        $value = $value . '%';
                    }
                    $options[$nom_input] = $value;
                } else {
                    // order, $value = ASC || DESC
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
        

        $sql .= " FROM copieurs_transfert
                JOIN profil on `id_profil` = `modif_par`
                WHERE 1
                $where
                $ordering
                $limit";

        $p = self::$pdo->prepare($sql);
        $p->execute($options);
        return $p->fetchAll();
    }
}