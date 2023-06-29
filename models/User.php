<?php
namespace App;
use App\Driver;
use App\Compteur;

class User extends Driver {
    static function getMonID(): int
    {
        return $_SESSION['user']['id_profil'];
    }
    static function getGPN(): string
    {
        return $_SESSION['user']['grade-prenom-nom'];
    }
    static function getBDD(): string
    {
        return htmlentities($_SESSION['user']['BDD']);
    }
    static function getMessagerie(): string
    {
        return $_SESSION['user']['Courriel'];
    }
    static function getRole(): int
    {
        return $_SESSION['user']['role'];
    }

    static function getLibelleRole(): string
    {
        $req = "SELECT `userlevelname` FROM userlevels WHERE `userlevelid` = :id";
        $p = self::$pdo->prepare($req);
        $p->execute(['id' => self::getRole()]);
        return $p->fetch()['userlevelname'];
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
        // $mdp_actuel = $p->fetch()['mdp'];
        // return password_verify($proposed_password, $mdp_actuel);
    }

    static function changerMDP($mdp): bool
    {
        $req = "UPDATE profil SET `mdp` = :mdp WHERE `id_profil` = :id AND `Courriel` = :messagerie";
        $p = self::$pdo->prepare($req);
        return $p->execute([
            // 'mdp' => password_hash($mdp,  PASSWORD_DEFAULT, ['cost' => 12]),
            'mdp' => $mdp,
            'id' => self::getMonID(),
            'messagerie' => self::getMessagerie()
        ]);
    }

    static function ajouterReleve($num_serie, $date_releve, $total_112, $total_113, $total_122, $total_123, $type_releve): bool
    {
        $query = "INSERT INTO compteurs
        (`Numéro_série`, `BDD`, `Date`, 112_total, 113_total, 122_total, 123_total, modif_par, `type_relevé`)
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

    static function supprimerReleve($num_serie, $date_releve): bool
    {
        $query = "DELETE FROM compteurs WHERE `Numéro_série` = :num AND `Date` = :dr AND BDD = :bdd";

        $p = self::$pdo->prepare($query);
        return $p->execute([
            'num' => $num_serie,
            'dr' => $date_releve,
            'bdd' => User::getBDD()
        ]);
    }

    static function ajouterDansPerimetre($num_serie, $id_profil = NULL): bool
    {
        $query = "INSERT INTO users_copieurs
        (`responsable`, `numéro_série`)
        VALUES
        (:id_profil, :num_serie)";

        $p = self::$pdo->prepare($query);
        return $p->execute([
            'id_profil' => ($id_profil === NULL) ? User::getMonID() : $id_profil,
            'num_serie' => $num_serie
        ]);
    }

    static function retirerDansPerimetre($num_serie): bool
    {
        $query = "DELETE FROM users_copieurs WHERE `responsable` = :id_user AND `numéro_série` = :num_serie";
        $p = self::$pdo->prepare($query);
        return $p->execute([
            'id_user' => self::getMonID(),
            'num_serie' => $num_serie
        ]);
    }

    static function getUtilisateursPerimetre(array $params, bool $enableLimit = true): array
    {
        $where = '';
        $options = [];
        $ordering = '';

        $sql = "SELECT ";
        foreach (self::ChampsGestionUtilisateurs() as $nom_input => $props) {
            $nom_db = $props['nom_db'];
            $sql .= " `$nom_db` as $nom_input,";
        }
        $sql = rtrim($sql, ','); // Suppression de la virgule pour la dernière ligne

        foreach ($params as $nom_input => $props) {
            $value = $props['value'];
            if (trim($value) !== '' && isset($props['nom_db'])) {
                $nom_db = $props['nom_db'];
                
                if ($nom_input !== 'order') {
                    $where .= " AND `$nom_db` LIKE :$nom_input";
                    if ($props['valuePosition'] === 'tout') {
                        $value = '%' . $value . '%';
                    } else {
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

        $sql .= " FROM profil p
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

    static function ChampsGestionUtilisateurs(bool $showMDP = false): array
    {
        $headers = [
            "gpn" => ['nom_db' => "grade-prenom-nom", 'libelle' => "Grade Prénom Nom", "valuePosition" => "tout"],
            "courriel" => ['nom_db' => "Courriel", 'libelle' => "Courriel"],
            "role" => ['nom_db' => "userlevelname", 'libelle' => "Role"],
            "mdp" => ['nom_db' => "mdp", 'libelle' => "Mot de passe"],
            "unite" => ['nom_db' => "Unité", 'libelle' => "Unité"]
        ];

        if (!$showMDP) {
            unset($headers['mdp']);
        }

        foreach ($headers as $nom_input => $props) {
            if (!isset($headers[$nom_input]['valuePosition'])) {
                $headers[$nom_input]['valuePosition'] = 'right';
            }
        }

        return $headers;
    }

    static function setHistory()
    {
        debug($_SERVER['REQUEST_URI']);
    }

    static function deconnexion(): void
    {
        session_destroy();
        header('Location:/');
        exit();
    }
}