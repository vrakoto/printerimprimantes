<?php
namespace App\Users;
use App\Driver;
use App\Compteur;

class User extends Driver {
    protected static $champ_id = 'id';
    protected static $champ_gpn = 'grade_nom_prenom';
    protected static $champ_bdd = 'bdd';
    protected static $champ_messagerie = 'messagerie';
    protected static $champ_role = 'role';
    protected static $champ_theme = 'theme';
    protected static $mdp = 'mdp';

    
    private static function getInfoFromDatabase($champ): string
    {
        $req = "SELECT " . $champ . " FROM users WHERE " . self::$champ_id . " = :id AND " . self::$champ_messagerie . " = :messagerie"; 
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
        $req = "SELECT libelle FROM users_level WHERE id = :id";
        $p = self::$pdo->prepare($req);
        $p->execute(['id' => self::getRole()]);
        return $p->fetch()['libelle'];
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
        $req = "UPDATE users SET theme = :theme WHERE id = :id";
        $p = self::$pdo->prepare($req);
        return $p->execute(['id' => self::getMonID(), 'theme' => $theme]);
    }


    static function verifierMDP($proposed_password): bool
    {
        $req = "SELECT mdp FROM users WHERE id = :id AND messagerie = :messagerie";
        $p = self::$pdo->prepare($req);
        $p->execute([
            'id' => self::getMonID(),
            'messagerie' => self::getMessagerie()
        ]);
        $mdp_actuel = $p->fetch()['mdp'];
        return password_verify($proposed_password, $mdp_actuel);
    }

    static function changerMDP($mdp): bool
    {
        $req = "UPDATE users SET mdp = :mdp WHERE id = :id AND messagerie = :messagerie";
        $p = self::$pdo->prepare($req);
        return $p->execute([
            'mdp' => password_hash($mdp,  PASSWORD_DEFAULT, ['cost' => 12]),
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

    


    static function getLesRelevesMonPerimetre(): array
    {
        /* if (self::getRole() === 1 || self::getRole() === 3) {
            $query = "SELECT * FROM compteurs
                      WHERE modif_par IN
                        (SELECT id_user FROM users_copieurs
                        WHERE id_user = :id_profil)";

            $p = self::$pdo->prepare($query);
            $p->execute([
                'id_profil' => self::getMonID()
            ]);

            return $p->fetchAll();

        } else if (self::getRole() === 2) {
            return Compteur::getLesRelevesParBDD();
        } */

        $query = "SELECT * FROM compteurs
                    WHERE modif_par IN
                    (SELECT id_user FROM users_copieurs
                    WHERE id_user = :id_profil)";

        $p = self::$pdo->prepare($query);
        $p->execute([
            'id_profil' => self::getMonID()
        ]);

        return $p->fetchAll();
    }
    

    /**
     *
     * Uniquement pour la page Ajouter relevé (menu déroulant)
     */
    static function getLesNumerosSeriesNonReleveToday(): array
    {
        $req = "SELECT `num_serie` FROM copieurs
                WHERE `bdd` = :bdd
                AND `num_serie` NOT IN (SELECT `num_serie` FROM compteurs WHERE DATE (`date_maj`) = CURDATE())
                ORDER BY num_serie ASC";
        $p = self::$pdo->prepare($req);
        $p->execute([
            'bdd' => self::getBDD()
        ]);
        return $p->fetchAll();
    }

    static function copieursPerimetre(): array
    {
        $query = "SELECT * FROM copieurs c
            JOIN users_copieurs uc on uc.num_serie = c.num_serie
            WHERE id_user = :id_profil
            ORDER BY c.num_serie ASC";
        
        $p = self::$pdo->prepare($query);
        $p->execute([
            'id_profil' => self::getMonID()
        ]);

        return $p->fetchAll();
    }

    static function deconnexion(): void
    {
        unset($_SESSION['user']);
        header('Location:/');
        exit();
    }
}