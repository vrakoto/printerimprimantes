<?php
namespace App;

class Panne extends Driver {
    static function ChampPannes(): string
    {
        return <<<HTML
        <thead>
            <tr>
                <th id="id_event">N° de ticket</th>
                <th id="num_serie">N° de série</th>
                <th id="contexte">Contexte de la panne</th>
                <th id="type_panne">Nature de la panne</th>
                <th id="statut_intervention">Statut de l'intervention</th>
                <th id="commentaires">Commentaires</th>
                <th id="date_evolution">Date de changement de situation</th>
                <th id="heure_evolution">Heure de changement de situation</th>
                <th id="maj_par">Déclaré/Modifié par</th>
                <th id="maj_date">Ticket modifié le</th>
                <th id="fichier">Fichier(s) complémentaire(s)</th>
                <th id="ouverture">Ouverture du ticket</th>
                <th id="fermeture">Fermeture du ticket</th>
            </tr>
        </thead>
HTML;
    }

    static function getLesPannes(array $params, bool $perimetre, array $limits = []): array
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
            $where .= " AND c.BDD = :bdd";
            $options['bdd'] = User::getBDD();
        }

        $limit = (!empty($limits)) ? "LIMIT {$limits[0]}, {$limits[1]}" : '';
        $sql = "SELECT
                id_event,
                num_série as num_serie,
                contexte,
                type_panne,
                statut_intervention,
                commentaires,
                date_évolution as date_evolution,
                heure_évolution as heure_evolution,
                maj_date,
                fichier,
                ouverture,
                fermeture,
                p.`grade-prenom-nom` as maj_par,
                tap.action as contexte
                FROM panne
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