<?php
namespace App;
use App\User;

class Compteur extends Driver
{
    static function ChampsCompteur($perimetre): array
    {
        $headers = [
            "num_serie" => ['nom_db' => "Numéro_série", "libelle" => "N° de série", "anti_ambiguous" => 'c', "valuePosition" => "right"],
            "bdd" => ['nom_db' => "BDD", "libelle" => "BDD", "anti_ambiguous" => 'c'],
            "date" => ['nom_db' => "Date", "libelle" => "Date de relevé"],
            "total_101" => ['nom_db' => "101_Total_1", "libelle" => "101 Total"],
            "total_112" => ['nom_db' => "112_Total", "libelle" => "112 Total"],
            "total_113" => ['nom_db' => "123_Total", "libelle" => "113 Total"],
            "total_122" => ['nom_db' => "122_Total", "libelle" => "122 Total"],
            "total_123" => ['nom_db' => "123_Total", "libelle" => "123 Total"],
            "modif_par" => ['nom_db' => "grade-prenom-nom", "libelle" => "Ajouté/Modifié par", "valuePosition" => "tout"],
            "date_maj" => ['nom_db' => "date_maj", "libelle" => "Mise à jour le"],
            "type_releve" => ['nom_db' => "type_relevé", "libelle" => "Type de relevé"]
        ];

        if ($perimetre) {
            unset($headers['bdd']);
        }

        foreach ($headers as $key => $value) {
            if (!isset($headers[$key]['valuePosition'])) {
                $headers[$key]['valuePosition'] = 'exact';
            }
        }
        
        return $headers;
    }

    static function getLesRelevesParBDD($bdd = NULL): array
    {
        if ($bdd === NULL) {
            $bdd = User::getBDD();
        }
        $query = "SELECT * FROM compteurs WHERE `BDD` = :bdd
                ORDER BY `date_maj` DESC";
        $p = self::$pdo->prepare($query);
        $p->execute([
            'bdd' => $bdd
        ]);

        return $p->fetchAll();
    }

    static function getLesReleves(array $params, bool $perimetre, bool $enableLimit = true): array
    {
        $where = ' WHERE 1';
        $options = [];
        $ordering = '';

        $sql = "SELECT ";
        foreach (self::ChampsCompteur($perimetre) as $nom_input => $props) {
            $anti_ambiguous = (isset($props['anti_ambiguous'])) ? $props['anti_ambiguous'] . '.' : '';

            $nom_db = $props['nom_db'];
            $sql .= " $anti_ambiguous`$nom_db` as $nom_input,";
        }
        $sql = rtrim($sql, ',');


        foreach ($params as $nom_input => $props) {
            $value = $props['value'];
            $anti_ambiguous = (isset($props['anti_ambiguous'])) ? $props['anti_ambiguous'] . '.' : '';
            
            if (trim($value) !== '' && isset($props['nom_db'])) {
                $nom_db = $props['nom_db'];
                
                if ($nom_input !== 'order') {
                    $where .= " AND $anti_ambiguous`$nom_db` LIKE :$nom_input";
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


        if ($perimetre) {
            if (User::getRole() === 2) {
                $where .= " AND c.`BDD` = :bdd";
                $options['bdd'] = User::getBDD();
            } else {
                $where .= " AND p.id_profil IN (SELECT responsable FROM users_copieurs WHERE responsable = :id_profil)";
                $options['id_profil'] = User::getMonID();
            }
        }

        if ($_SESSION['uniqueCompteurs'] === "true") {
            $where .= " AND c.date_maj =
                        (SELECT MAX(date_maj) FROM compteurs
                        WHERE Numéro_série = c.Numéro_série)";
        }

        $sql .= " FROM compteurs c
                LEFT JOIN profil p on p.id_profil = c.modif_par
                $where
                $ordering
                $limit";

        $p = self::$pdo->prepare($sql);
        $p->execute($options);
        return $p->fetchAll();
    }

    static function isMine($num_serie, $date_releve): bool
    {
        $query = "SELECT modif_par FROM compteurs
                WHERE `modif_par` = :id_profil
                AND `Numéro_série` = :num_serie
                AND `Date` = :dr";
        $p = self::$pdo->prepare($query);
        $p->execute([
            'id_profil' => User::getMonID(),
            'num_serie' => $num_serie,
            'dr' => $date_releve
        ]);

        return !empty($p->fetch());
    }
}
