<?php
namespace App;
use App\User;

class UsersCopieurs extends Driver {
    static function ChampUsersCopieurs(): array
    {
        $headers = [
            "id_profil" => ['nom_db' => "id_profil", 'libelle' => "ID Profil", "valuePosition" => "exact"],
            "gpn" => ['nom_db' => "grade-prenom-nom", 'libelle' => "Grade Prénom Nom", "valuePosition" => "tout"],
            "num_serie" => ['nom_db' => "Numéro_série", 'libelle' => "N° de Série", "valuePosition" => "right"],
        ];

        return $headers;
    }

    static function getResponsables(array $params, bool $perimetre, bool $enableLimit = true): array
    {
        $where = '';
        $options = [];
        $ordering = '';

        $sql = "SELECT ";
        foreach (self::ChampUsersCopieurs() as $nom_input => $props) {
            $nom_db = $props['nom_db'];
            $sql .= " `$nom_db` as $nom_input,";
        }
        $sql = rtrim($sql, ',');


        // WHERE et ORDER
        foreach ($params as $nom_input => $props) {
            $value = $props['value'];
            if (trim($value) !== '' && isset($props['nom_db'])) {
                $nom_db = $props['nom_db'];
                
                if ($nom_input !== 'order') {
                    $where .= " AND `$nom_db` LIKE :$nom_input";
                    switch ($props['valuePosition']) {
                        case 'tout':
                            $value = '%' . $value . '%';
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

        if ($perimetre) {
            $where .= " AND p.BDD = :bdd";
            $options['bdd'] = User::getBDD();
        }


        // LIMIT
        $limit = '';
        if ($enableLimit) {
            $debut = (int)$params['debut']['value'];
            $nb_results_page = (int)$params['nb_results_page']['value'];
            $limit = "LIMIT $debut, $nb_results_page";
        }
        
        // Assemblage de la requete finale
        $sql .= " FROM users_copieurs uc
                JOIN profil p on uc.`responsable` = p.`id_profil`
                WHERE 1 $where
                $ordering
                $limit";

        $p = self::$pdo->prepare($sql);
        $p->execute($options);
        return $p->fetchAll();
    }
}