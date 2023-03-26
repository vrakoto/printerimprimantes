<?php
namespace App;

class Connexion extends Driver {
    private $messagerie;
    private $mdp;

    function __construct(string $messagerie, string $mdp)
    {
        $this->messagerie = $messagerie;
        $this->mdp = $mdp;
    }

    function getPasswordToVerify(): string
    {
        $req = "SELECT mdp FROM users WHERE messagerie = :messagerie";
        $p = self::$pdo->prepare($req);
        $p->execute([
            'messagerie' => $this->messagerie
        ]);

        return $p->fetch()['mdp'];
    }

    function verifierAuth(): bool
    {
        $req = "SELECT * FROM users WHERE messagerie = :messagerie";
        $p = self::$pdo->prepare($req);
        $p->execute([
            'messagerie' => $this->messagerie
        ]);
        return !empty($p->fetchAll()) && password_verify($this->mdp, $this->getPasswordToVerify());
    }

    private function getInformations(): array
    {
        $req = "SELECT * FROM users WHERE messagerie = :messagerie";
        $p = self::$pdo->prepare($req);
        $p->execute([
            'messagerie' => $this->messagerie
        ]);

        return $p->fetch();
    }

    function etablirConnexion(): void
    {
        // $_SESSION[User::getChamp('champ_id')] = $this->getIdProfil();
        $_SESSION['user'] = $this->getInformations();
        header('Location:/');
        exit();
    }
}