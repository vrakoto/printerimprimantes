<?php
namespace App;
class Logs extends Driver {
    static function getLesLogs(): array
    {
        $query = "SELECT `grade-prenom-nom` as gpn, `action`, `date_action`
                  FROM `logs`
                  JOIN profil p on p.id_profil = logs.id_profil
                  WHERE BDD = :bdd
                  ORDER BY ";
        $p = self::$pdo->prepare($query);
        $p->execute(['bdd' => User::getBDD()]);

        return $p->fetchAll();
    }
}