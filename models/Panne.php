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
                <th id="modif_par">Déclaré/Modifié par</th>
                <th id="modif_date">Ticket modifié le</th>
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
        $sqlPerimetre = '';
        if ($perimetre) {
            $sqlPerimetre = " LEFT JOIN copieurs c on c.`N° de Série` = panne.`num_série` ";
        }
        foreach ($params as $column => $value) {
            if (trim($value) !== '') {
                $prefix = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 20);
                $where .= " AND $column LIKE :$prefix";
                $options[$prefix] = $value;
            }
        }
        $limit = (!empty($limits)) ? "LIMIT {$limits[0]}, {$limits[1]}" : '';
        $sql = "SELECT *, p.`grade-prenom-nom` as maj_par FROM panne
                LEFT JOIN profil p on p.id_profil = panne.maj_par
                $sqlPerimetre
                WHERE 1 $where
                ORDER BY ouverture DESC
                $limit";
        $p = self::$pdo->prepare($sql);
        $p->execute($options);
        return $p->fetchAll();
    }
}