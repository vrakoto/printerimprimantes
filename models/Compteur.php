<?php
namespace App;
use App\User;
use DateTime;

class Compteur extends Driver {
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
                <th>Numéro Série</th>
                <th>BDD</th>
                <th>Date de relevé</th>
                <th>101 Total</th>
                <th>112 Total</th>
                <th>113 Total</th>
                <th>122 Total</th>
                <th>123 Total</th>
                <th>Ajouté par</th>
                <th>Mise à jour le</th>
                <th>Type de relevé</th>
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


    static function searchCompteurByNumSerie($num_serie): array
    {
        $req = "SELECT c.Numéro_série, c.BDD, DATE_FORMAT(`Date`, '%d/%m/%Y') as `Date`, 101_Total_1, 112_Total, 113_Total, 122_Total, 123_Total, p.`grade-prenom-nom` as modif_par, DATE_FORMAT(date_maj, '%d/%m/%Y %H:%i:%s') as date_maj, type_relevé
                FROM compteurs c
                LEFT JOIN profil p on p.id_profil = c.modif_par
                WHERE c.Numéro_série LIKE :num_serie";
        $p = self::$pdo->prepare($req);
        $p->execute(['num_serie' => '%' . $num_serie . '%']);
        return $p->fetchAll();
    }
}