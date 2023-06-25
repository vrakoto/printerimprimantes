<?php
namespace App;

class Panne extends Driver {
    static function ChampPannes(): array
    {
        $headers = [
            "num_serie" => ['nom_db' => "num_série", "libelle" => "N° de Série"],
            "id_event" => ['nom_db' => "id_event", "libelle" => "N° de Ticket"],
            "contexte" => ['nom_db' => "contexte", "libelle" => "Contexte de la panne"],
            "type_panne" => ['nom_db' => "type_panne", "libelle" => "Nature de la panne"],
            "statut_intervention" => ['nom_db' => "statut_intervention", "libelle" => "Statut de l'intervention"],
            "commentaires" => ['nom_db' => "commentaires", "libelle" => "Commentaires", "valuePosition" => "tout"],
            "date_evolution" => ['nom_db' => "date_évolution", "libelle" => "Date de changement de situation"],
            "heure_evolution" => ['nom_db' => "heure_évolution", "libelle" => "Heure de changement de situation"],
            "maj_par" => ['nom_db' => "maj_par", "libelle" => "Déclaré/Modifié par"],
            "maj_date" => ['nom_db' => "maj_date", "libelle" => "Ticket modifié le"],
            "fichier" => ['nom_db' => "fichier", "libelle" => "Fichier(s) complémentaire(s)"],
            "ouverture" => ['nom_db' => "ouverture", "libelle" => "Ouverture du ticket"],
            "fermeture" => ['nom_db' => "fermeture", "libelle" => "Fermeture du ticket"],
        ];

        foreach ($headers as $nom_input => $value) {
            if (!isset($headers[$nom_input]['valuePosition'])) {
                $headers[$nom_input]['valuePosition'] = 'right';
            }
        }

        return $headers;
    }

    static function getLesPannes(array $params, bool $perimetre, bool $enableLimit = true): array
    {
        $where = '';
        $options = [];
        $ordering = '';

        $sql = "SELECT ";
        foreach (self::ChampPannes() as $nom_input => $props) {
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
                    switch ($props['valuePosition']) {
                        case 'left':
                            $value = '%' . $value;
                        break;

                        case 'right':
                            $value = $value . '%';
                        break;

                        case 'tout':
                            $value = '%' . $value . '%';
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
            $where .= " AND c.BDD = :bdd";
            $options['bdd'] = User::getBDD();
        }

        $limit = '';
        if ($enableLimit) {
            $debut = (int)$params['debut']['value'];
            $nb_results_page = (int)$params['nb_results_page']['value'];
            $limit = "LIMIT $debut, $nb_results_page";
        }

        

        $sql .= " FROM panne
                JOIN profil p on p.id_profil = panne.maj_par
                JOIN copieurs c on c.`N° de Série` = panne.`num_série` 
                JOIN type_action_panne tap on tap.id_action = panne.contexte
                WHERE 1 $where
                $ordering
                $limit";

        $p = self::$pdo->prepare($sql);
        $p->execute($options);
        return $p->fetchAll();
    }
}