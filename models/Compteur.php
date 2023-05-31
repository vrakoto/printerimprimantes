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

    static function releveUserName($num_serie, $date_releve): string
    {
        $champ_gpn_user = User::getChamp('champ_gpn');
        $champ_modif_par_compteur = self::$champ_modif_par;
        $champ_id_user = User::getChamp('champ_id');
        $champ_num_serie_compteur = self::$champ_num_serie;
        $champ_date_releve_compteur = self::$champ_date_releve;

        $req = "SELECT `$champ_gpn_user` as gpn FROM profil u
                JOIN compteurs c on c.$champ_modif_par_compteur = u.$champ_id_user
                AND $champ_num_serie_compteur = :num_serie
                AND $champ_date_releve_compteur = :dr";
        $p = self::$pdo->prepare($req);
        $p->execute([
            'num_serie' => $num_serie,
            'dr' => $date_releve
        ]);
        $name = $p->fetch()['gpn'] ?? 'Un administrateur';
        return $name;
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
        foreach ($params as $nom_input => $props) {
            $value = $props['value'];
            if (trim($value) !== '') {
                $nom_db = $props['nom_db'];
                
                if ($nom_input !== 'order') {
                    $where .= " AND c.`$nom_db` LIKE :$nom_input";
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
        $sql = "SELECT
                `Numéro_série` as num_serie,
                c.`BDD` as bdd,
                `Date`,
                `101_Total_1` as total_101,
                `112_Total` as total_112,
                `113_Total` as total_113,
                `122_Total` as total_122,
                `123_Total` as total_123,
                p.`grade-prenom-nom` as gpn,
                `date_maj`,
                `type_relevé` as type_releve
                FROM compteurs c
                LEFT JOIN profil p on p.id_profil = c.modif_par
                WHERE 1
                $where
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
