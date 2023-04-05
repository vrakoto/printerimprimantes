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
        return $_SESSION['user'][self::$champ_bdd];
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
        $champ_mdp = self::getChamp('champ_mdp');
        $champ_id = self::getChamp('champ_id');
        $champ_messagerie = self::getChamp('champ_messagerie');

        $req = "SELECT $champ_mdp FROM profil WHERE $champ_id = :id AND $champ_messagerie = :messagerie";
        $p = self::$pdo->prepare($req);
        $p->execute([
            'id' => self::getMonID(),
            'messagerie' => self::getMessagerie()
        ]);
        return !empty($p->fetch());
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
    

    function insertLogs(string $page): bool
    {
        $req = "INSERT INTO `actions_users`(`id_user`, `page`) VALUES (:id_user, :page)";
        $p = self::$pdo->prepare($req);
        return $p->execute([
            'id_user' => self::getMonID(),
            'page' => $page
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

    static function copieursPerimetre(): array
    {
        if (self::getRole() === 2) {
            return Imprimante::getImprimantesParBDD(self::getBDD());
        }
        $champ_num_serie_users_copieurs = UsersCopieurs::getChamps('champ_num_serie');
        $champ_id_user_users_copieurs = UsersCopieurs::getChamps('champ_id_user');
        $champ_num_serie_imprimante = Imprimante::getChamps('champ_num_serie');

        $query = "SELECT * FROM copieurs c
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

    static function deconnexion(): void
    {
        unset($_SESSION['user']);
        header('Location:/');
        exit();
    }
}