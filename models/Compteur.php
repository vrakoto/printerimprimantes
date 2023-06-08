<?php

namespace App;

use App\User;

class Compteur extends Driver
{
    private static $champ_num_serie = 'Numéro_série';
    private static $champ_bdd = 'BDD';
    private static $champ_date_releve = 'Date';
    private static $champ_total_101 = '101_Total_1';
    private static $champ_total_112 = '112_Total';
    private static $champ_total_113 = '113_Total';
    private static $champ_total_122 = '122_Total';
    private static $champ_total_123 = '123_Total';
    private static $champ_date_maj = 'date_maj';
    private static $champ_modif_par = 'modif_par';
    private static $champ_type_releve = 'type_relevé';

    static function getChamps($champ): string
    {
        return self::$$champ;
    }

    static function ChampsCompteur(): string
    {
        return <<<HTML
        <thead>
            <tr>
                <th id="num_serie">Numéro Série</th>
                <th id="bdd">BDD</th>
                <th id="date_releve">Date de relevé</th>
                <th id="101_total_1">101 Total</th>
                <th id="112_total">112 Total</th>
                <th id="113_total">113 Total</th>
                <th id="122_total">122 Total</th>
                <th id="123_total">123 Total</th>
                <th id="modif_par">Ajouté par</th>
                <th id="date_maj">Mise à jour le</th>
                <th id="type_releve">Type de relevé</th>
            </tr>
        </thead>
HTML;
    }


    static function testChamps($perimetre): array
    {
        $headers = [
            "num_serie" => ['nom_input' => "num_serie", 'nom_db' => "Numéro_série", "libelle" => "N° de série"],
            "bdd" => ['nom_input' => "bdd", 'nom_db' => "BDD", "libelle" => "BDD", "anti_ambiguous" => 'c'],
            "date" => ['nom_input' => "date", 'nom_db' => "Date", "libelle" => "Date de relevé"],
            "total_101" => ['nom_input' => "total_101", 'nom_db' => "101_Total_1", "libelle" => "101 Total"],
            "total_112" => ['nom_input' => "total_112", 'nom_db' => "112_Total", "libelle" => "112 Total"],
            "total_113" => ['nom_input' => "total_113", 'nom_db' => "123_Total", "libelle" => "113 Total"],
            "total_122" => ['nom_input' => "total_122", 'nom_db' => "122_Total", "libelle" => "122 Total"],
            "total_123" => ['nom_input' => "total_123", 'nom_db' => "123_Total", "libelle" => "123 Total"],
            "modif_par" => ['nom_input' => "modif_par", 'nom_db' => "grade-prenom-nom", "libelle" => "Ajouté/Modifié par"],
            "date_maj" => ['nom_input' => "date_maj", 'nom_db' => "date_maj", "libelle" => "Mise à jour le"],
            "type_releve" => ['nom_input' => "type_releve", 'nom_db' => "type_relevé", "libelle" => "Type de relevé"]
        ];

        if ($perimetre) {
            unset($headers['bdd']);
        }
        
        return $headers;
    }

    static function getLesRelevesParBDD($bdd = NULL): array
    {
        if ($bdd === NULL) {
            $bdd = User::getBDD();
        }
        $query = "SELECT * FROM compteurs WHERE " . self::$champ_bdd . " = :bdd
                ORDER BY " . self::$champ_date_maj . " DESC";
        $p = self::$pdo->prepare($query);
        $p->execute([
            'bdd' => $bdd
        ]);

        return $p->fetchAll();
    }

    static function getLesReleves(array $params, bool $perimetre, array $limits = []): array
    {
        $where = '';
        $options = [];
        $ordering = '';

        // Récupérer les paramètres de recherche dynamiquement
        foreach ($params as $nom_input => $props) {
            $value = $props['value'];
            $anti_ambiguous = (isset($props['anti_ambiguous'])) ? $props['anti_ambiguous'] . '.' : '';
            
            if (trim($value) !== '') {
                $nom_db = $props['nom_db'];
                
                if ($nom_input !== 'order') {
                    $where .= " AND $anti_ambiguous`$nom_db` LIKE :$nom_input";
                    $options[$nom_input] = $props['valuePosition'];
                } else {
                    // order
                    $ordering = ' ORDER BY `' . $nom_db . '` ' . $value;
                }
            }
        }

        if ($perimetre) {
            $where .= " AND c.`BDD` = :bdd";
            $options['bdd'] = User::getBDD();
        }

        $limit = (!empty($limits)) ? "LIMIT {$limits[0]}, {$limits[1]}" : '';
        $sql = "SELECT ";
        
        // Récupérer toutes les colonnes dynamiquement
        foreach (self::testChamps($perimetre) as $nom_input => $props) {
            $anti_ambiguous = (isset($props['anti_ambiguous'])) ? $props['anti_ambiguous'] . '.' : '';

            $nom_db = $props['nom_db'];
            $sql .= " $anti_ambiguous`$nom_db` as $nom_input,";
        }

        // Suppression de la virgule pour la dernière ligne
        $sql = rtrim($sql, ',');

        $sql .= " FROM compteurs c
                LEFT JOIN profil p on p.id_profil = c.modif_par
                WHERE 1 $where
                $ordering
                $limit";

        $p = self::$pdo->prepare($sql);
        $p->execute($options);
        return $p->fetchAll();
    }


    static function searchCompteurByNumSerie($num_serie): array
    {
        $req = "SELECT Numéro_série as num_serie,
                c.BDD as bdd,
                Date as date_releve,
                `101_Total_1` as total_101,
                `112_Total` as total_112,
                `113_Total` as total_113,
                `122_Total` as total_122,
                `123_Total` as total_123,
                `grade-prenom-nom` as modif_par,
                date_maj,
                type_relevé as type_releve
                FROM compteurs c
                LEFT JOIN profil p on p.id_profil = c.modif_par
                WHERE c.Numéro_série LIKE :num_serie
                ORDER BY date_maj DESC";
        $p = self::$pdo->prepare($req);
        $p->execute(['num_serie' => '%' . $num_serie . '%']);
        return $p->fetchAll();
    }
}
