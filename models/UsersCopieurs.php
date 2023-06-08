<?php
namespace App;

use App\User;

class UsersCopieurs extends Driver {
    private static $champ_id_user = 'responsable';
    private static $champ_num_serie = 'numéro_série';

    static function getChamps($champ): string
    {
        return self::$$champ;
    }

    static function ChampUsersCopieurs(): string
    {
        return <<<HTML
        <thead>
            <tr>
                <th id="gpn">Grade Nom Prénom</th>
                <th id="num_serie">N° de Série</th>
            </tr>
        </thead>
HTML;
    }

    static function testChamps(): array
    {
        $headers = [
            "gpn" => ['nom_db' => "grade-prenom-nom", 'libelle' => "Grade Prénom Nom"],
            "num_serie" => ['nom_db' => "Numéro_série", 'libelle' => "N° de Série"],
        ];

        return $headers;
    }

    static function getResponsables(array $params, bool $perimetre, array $limits = []): array
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

        if ($perimetre) {
            $where .= " AND p.BDD = :bdd";
            $options['bdd'] = User::getBDD();
        }

        $limit = (!empty($limits)) ? "LIMIT {$limits[0]}, {$limits[1]}" : '';
        $sql = "SELECT p.`grade-prenom-nom` as gpn, `numéro_série` as num_serie
                FROM users_copieurs uc
                JOIN profil p on uc.`responsable` = p.`id_profil`
                WHERE 1 $where
                $ordering
                $limit";

        $p = self::$pdo->prepare($sql);
        $p->execute($options);
        return $p->fetchAll();
    }
}