<?php
namespace App;

use App\UserLevels;
use App\Driver;
use App\Compteur;

class User extends Driver {
    protected static $champ_id = 'id_profil';
    protected static $champ_bdd = 'BDD';
    protected static $champ_gpn = 'grade-prenom-nom';
    protected static $champ_messagerie = 'Courriel';
    protected static $champ_role = 'role';
    protected static $champ_mdp = 'mdp';
    protected static $champ_theme = 'theme';

    
    private static function getInfoFromDatabase($champ): string
    {
        $req = "SELECT " . $champ . " FROM profil WHERE " . self::$champ_id . " = :id AND " . self::$champ_messagerie . " = :messagerie"; 
        $p = self::$pdo->prepare($req);
        $p->execute([
            self::$champ_id => $_SESSION['user'][self::$champ_id],
            self::$champ_messagerie => $_SESSION['user'][self::$champ_messagerie],
        ]);
        return $p->fetch()[$champ];
    }


    static function getChamp($champ): string
    {
        return self::$$champ;
    }

    static function getMonID(): int
    {
        return $_SESSION['user'][self::$champ_id];
    }
    static function getGPN(): string
    {
        return $_SESSION['user'][self::$champ_gpn];
    }
    static function getBDD(): string
    {
        return htmlentities($_SESSION['user'][self::$champ_bdd]);
    }
    static function getMessagerie(): string
    {
        return $_SESSION['user'][self::$champ_messagerie];
    }
    static function getRole(): int
    {
        return $_SESSION['user'][self::$champ_role];
    }

    static function requireRole(int $nbRole)
    {
        if (self::getRole() !== $nbRole) {
            die('Problème de role');
        }
        return;
    }
    static function getLibelleRole(): string
    {
        $userlevelid = UserLevels::getChamps('champ_id');
        $userlevelname = UserLevels::getChamps('champ_name');
        
        $req = "SELECT $userlevelname FROM userlevels WHERE $userlevelid = :id";
        $p = self::$pdo->prepare($req);
        $p->execute(['id' => self::getRole()]);
        return $p->fetch()[$userlevelname];
    }

    static function getTheme(): string
    {
        return self::getInfoFromDatabase(self::$champ_theme);
    }
    static function setTheme(): bool
    {
        if (self::getTheme() === 'clair') {
            $theme = "dark";
        } else {
            $theme = "clair";
        }
        $req = "UPDATE profil SET theme = :theme WHERE id = :id";
        $p = self::$pdo->prepare($req);
        return $p->execute(['id' => self::getMonID(), 'theme' => $theme]);
    }


    static function verifierMDP($proposed_password): bool
    {
        $req = "SELECT mdp FROM profil WHERE `id_profil` = :id AND `Courriel` = :courriel";
        $p = self::$pdo->prepare($req);
        $p->execute([
            'id' => self::getMonID(),
            'courriel' => self::getMessagerie()
        ]);
        return ($proposed_password === $p->fetch()['mdp']);
        // $mdp_actuel = $p->fetch()[$champ_mdp];
        // return password_verify($proposed_password, $mdp_actuel);
    }

    static function changerMDP($mdp): bool
    {
        $champ_mdp = self::getChamp('champ_mdp');
        $champ_id = self::getChamp('champ_id');
        $champ_messagerie = self::getChamp('champ_messagerie');

        $req = "UPDATE profil SET $champ_mdp = :mdp WHERE $champ_id = :id AND $champ_messagerie = :messagerie";
        $p = self::$pdo->prepare($req);
        return $p->execute([
            // 'mdp' => password_hash($mdp,  PASSWORD_DEFAULT, ['cost' => 12]),
            'mdp' => $mdp,
            'id' => self::getMonID(),
            'messagerie' => self::getMessagerie()
        ]);
    }
    
    static function getChampsUser(): string
    {
        return <<<HTML
        <thead>
            <tr>
                <th>Grade Prénom Nom</th>
                <th>BDD</th>
                <th>Courriel</th>
                <th>Rôle</th>
                <th>Unité</th>
            </tr>
        </thead>
HTML;
    }


    static function getLesRelevesMonPerimetre(): array
    {
        if (self::getRole() === 1 || self::getRole() === 3) {
            $query = "SELECT * FROM compteurs
                      WHERE modif_par IN
                        (SELECT  " . UsersCopieurs::getChamps('champ_id_user') . " FROM users_copieurs
                        WHERE " . UsersCopieurs::getChamps('champ_id_user') . " = :id_profil)";

            $p = self::$pdo->prepare($query);
            $p->execute([
                'id_profil' => self::getMonID()
            ]);

            return $p->fetchAll();

        } else if (self::getRole() === 2) {
            return Compteur::getLesRelevesParBDD();
        }
    }

    static function getLesNumerosMonPerimetre(): array
    {
        $where = '';
        $join = '';
        $query = "SELECT `N° de Série` as num_serie FROM copieurs c";

        if (self::getRole() === 2) {
            $where = " AND BDD = :bdd";
            $options['bdd'] = User::getBDD();
        } else {
            $join = " JOIN users_copieurs uc on uc.`numéro_série` = c.`N° de Série`";
            $where .= " AND `responsable` = :id_profil";
            $options['id_profil'] = User::getMonID();
        }

        $query .= "$join
                WHERE 1
                $where";
        
        $p = self::$pdo->prepare($query);
        $p->execute($options);

        return $p->fetchAll();
    }

    static function copieursPerimetre(): array
    {
        if (self::getRole() === 2) {
            return Imprimante::getImprimantesParBDD(self::getBDD());
        }
        $champ_num_serie_users_copieurs = UsersCopieurs::getChamps('champ_num_serie');
        $champ_id_user_users_copieurs = UsersCopieurs::getChamps('champ_id_user');
        $champ_num_serie_imprimante = Imprimante::getChamps('champ_num_serie');

        $query = "SELECT 
                `N° ORDO` as num_ordo, 
                `N° de Série` as num_serie, 
                `Modele demandé` as modele, 
                `STATUT PROJET` as statut, 
                `BDD` as bdd, 
                `Site d'installation` as site_installation,
                `DATE CDE MINARM` as date_cde_minarm,
                `Config` as config,
                `N° Saisie ORACLE` as num_oracle,
                `N° OPP SFDC` as num_sfdc,
                `HostName` as hostname,
                `réseau` as reseau,
                `MAC@` as adresse_mac,
                `Entité Bénéficiaire` as entite_beneficiaire,
                `credo_unité` as credo_unite,
                `CP INSTA` as cp_insta,
                `DEP INSTA` as dep_insta,
                `adresse` as adresse,
                `localisation` as localisation,
                `ServiceUF` as service_uf,
                `Accessoires` as accessoires
                FROM copieurs c
                JOIN users_copieurs uc on uc.`$champ_num_serie_users_copieurs` = c.`$champ_num_serie_imprimante`
                WHERE $champ_id_user_users_copieurs = :id_profil";
        
        $p = self::$pdo->prepare($query);
        $p->execute([
            'id_profil' => self::getMonID()
        ]);

        return $p->fetchAll();
    }

    static function ajouterReleve($num_serie, $date_releve, $total_112, $total_113, $total_122, $total_123, $type_releve): bool
    {
        $champ_num_serie = Compteur::getChamps('champ_num_serie');
        $champ_bdd = Compteur::getChamps('champ_bdd');
        $champ_date_releve = Compteur::getChamps('champ_date_releve');
        $champ_type_releve = Compteur::getChamps('champ_type_releve');

        $query = "INSERT INTO compteurs
        (`$champ_num_serie`, $champ_bdd, $champ_date_releve, 112_total, 113_total, 122_total, 123_total, modif_par, $champ_type_releve)
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

    static function editReleve($num_serie, $date_releve, $total_112, $total_113, $total_122, $total_123, $type_releve): bool
    {
        $query = "UPDATE compteurs SET 112_total = :112_total, 113_total = :113_total,
                122_total = :122_total, 123_total = :123_total, modif_par = :modif_par, `type_relevé` = :type_releve
                WHERE `Numéro_série` = :num_serie AND `Date` = :date_releve";

        $p = self::$pdo->prepare($query);
        return $p->execute([
            'num_serie' => $num_serie,
            'date_releve' => $date_releve,
            '112_total' => $total_112,
            '113_total' => $total_113,
            '122_total' => $total_122,
            '123_total' => $total_123,
            'modif_par' => self::getMonID(),
            'type_releve' => $type_releve
        ]);
    }

    static function supprimerReleve($num_serie, $date_releve): bool
    {
        $champ_num_serie = Compteur::getChamps('champ_num_serie');
        $champ_date_releve = Compteur::getChamps('champ_date_releve');

        $query = "DELETE FROM compteurs WHERE `$champ_num_serie` = :num AND `$champ_date_releve` = :dr AND BDD = :bdd";

        $p = self::$pdo->prepare($query);
        return $p->execute([
            'num' => $num_serie,
            'dr' => $date_releve,
            'bdd' => User::getBDD()
        ]);
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

    static function getUtilisateursPerimetre(array $params, array $limits = []): array
    {
        $where = '';
        $options = [];
        $ordering = '';
        foreach ($params as $nom_input => $props) {
            $value = $props['value'];
            if (trim($value) !== '') {
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

        $limit = (!empty($limits)) ? "LIMIT {$limits[0]}, {$limits[1]}" : '';
        $sql = "SELECT p.`grade-prenom-nom` as gpn, p.`Courriel` as courriel, ul.userlevelname as role
                FROM profil p
                JOIN userlevels ul on ul.userlevelid = p.role
                WHERE BDD = :bdd
                $where
                $ordering
                $limit";

        $options['bdd'] = User::getBDD();
        $p = self::$pdo->prepare($sql);
        $p->execute($options);
        return $p->fetchAll();
    }

    static function ChampsGestionUtilisateurs(): array
    {
        $headers = [
            "gpn" => ['nom_input' => "gpn", 'nom_db' => "grade-prenom-nom", 'libelle' => "Grade Prénom Nom"],
            "courriel" => ['nom_input' => "courriel", 'nom_db' => "Courriel", 'libelle' => "Courriel"],
            "role" => ['nom_input' => "role", 'nom_db' => "rôle", 'libelle' => "Role"]
        ];
        return $headers;
    }

    static function setHistory()
    {
        debug($_SERVER['REQUEST_URI']);
    }

    static function deconnexion(): void
    {
        unset($_SESSION['user']);
        header('Location:/');
        exit();
    }
}