<?php
namespace App;
use App\User;

class Connexion extends Driver {
    private $courriel;
    private $mdp;

    function __construct(string $courriel, string $mdp)
    {
        $this->courriel = $courriel;
        $this->mdp = $mdp;
    }

    // Sans chiffrement
    function verifierAuth(): bool
    {
        $champ_id_profil = User::getChamp('champ_id');
        $champ_courriel = User::getChamp('champ_messagerie');
        $champ_mdp = User::getChamp('champ_mdp');
        $req = "SELECT $champ_id_profil FROM profil WHERE $champ_courriel = :courriel AND $champ_mdp = :mdp";
        $p = self::$pdo->prepare($req);
        $p->execute([
            'courriel' => $this->courriel,
            'mdp' => $this->mdp
        ]);
        return !empty($p->fetchAll());
    }

    /* function getPasswordToVerify(): string
    {
        $req = "SELECT mdp FROM profil WHERE Courriel = :courriel";
        $p = self::$pdo->prepare($req);
        $p->execute([
            'courriel' => $this->courriel
        ]);

        return $p->fetch()['mdp'];
    }

    function verifierAuth(): bool
    {
        $req = "SELECT * FROM profil WHERE Courriel = :courriel";
        $p = self::$pdo->prepare($req);
        $p->execute([
            'courriel' => $this->courriel
        ]);
        return !empty($p->fetchAll()) && password_verify($this->mdp, $this->getPasswordToVerify());
    } */

    private function getInformations(): array
    {
        $champ_courriel = User::getChamp('champ_messagerie');
        $req = "SELECT * FROM profil WHERE $champ_courriel = :courriel";
        $p = self::$pdo->prepare($req);
        $p->execute([
            'courriel' => $this->courriel
        ]);

        return $p->fetch();
    }

    function etablirConnexion(): void
    {
        $_SESSION['user'] = $this->getInformations();
        $_SESSION['showColumns'] = 'few';
        header('Location:/');
        exit();
    }
}