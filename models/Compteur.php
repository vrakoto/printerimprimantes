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
                <!-- <th></th> -->
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


    static function ValeursCompteur(array $lesReleves): void
    {
        $num_serie = htmlentities($lesReleves[self::$champ_num_serie]);
        $dateReleveNonConvert = htmlentities($lesReleves[self::$champ_date_releve]);
        $dateReleve = convertDate($dateReleveNonConvert);
        $bdd = htmlentities($lesReleves[self::$champ_bdd]);
        $total_101 = (int)$lesReleves[self::$champ_total_101];
        $total_112 = (int)$lesReleves[self::$champ_total_112];
        $total_113 = (int)$lesReleves[self::$champ_total_113];
        $total_122 = (int)$lesReleves[self::$champ_total_122];
        $total_123 = (int)$lesReleves[self::$champ_total_123];
        $date_maj = DateTime::createFromFormat('Y-m-d H:i:s', ($lesReleves[self::$champ_date_maj]))->format('d/m/Y H:i:s');
        
        $realNameUser = self::releveUserName($num_serie, $dateReleveNonConvert) ?? 'Un administrateur';
        $type_releve = htmlentities($lesReleves[self::$champ_type_releve]);

        echo <<<HTML
            <tr>
                <td class="dt-control">
                    <!-- <a title="Consulter l'imprimante $num_serie (nouvel onglet)" href="imprimante/$num_serie" target="_blank" class="btn btn-primary "><i class="fa-solid fa-list"></i> -->
                </td>
                <td><a href="imprimante/$num_serie">$num_serie</a></td>
                <td>$bdd</td>
                <td>$dateReleve</td>
                <td>$total_101</td>
                <td>$total_112</td>
                <td>$total_113</td>
                <td>$total_122</td>
                <td>$total_123</td>
                <td>$realNameUser</td>
                <td>$date_maj</td>
                <td>$type_releve</td>
            </tr>
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

    static function relevesAddedThisMonth(): array
    {
        $query = "SELECT * FROM compteurs WHERE " . self::$champ_bdd . " = :bdd
                AND YEAR(" . self::$champ_date_maj . ") = YEAR(CURDATE())
                AND MONTH(" . self::$champ_date_maj . ") = MONTH(CURDATE())
                ORDER BY " . self::$champ_date_maj . " DESC";
        $p = self::$pdo->prepare($query);
        $p->execute([
            'bdd' => User::getBDD()
        ]); 

        return $p->fetchAll();
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
        $req = "SELECT * FROM compteurs WHERE " . self::$champ_num_serie . " LIKE :num_serie";
        $p = self::$pdo->prepare($req);
        $p->execute(['num_serie' => '%' . $num_serie . '%']);
        return $p->fetchAll();
    }
}