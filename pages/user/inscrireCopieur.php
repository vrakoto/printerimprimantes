<?php
use App\Coordsic;
$title = "Inscrire une machine";

$lesErreurs = [];
if (!empty($_POST)) {
    $num_serie = htmlentities($_POST['num_serie']);
    $modele = htmlentities($_POST['modele']);
    $bdd = Coordsic::getBDD();
    $site_insta = htmlentities($_POST['site_insta']);

    if (trim($num_serie) === '') {
        $lesErreurs[] = "Le numéro de série n'est pas renseigné";
    }

    if (strlen($num_serie) > 15) {
        $lesErreurs[] = "Le numéro de série est trop long";
    }

    if (trim($modele) === '') {
        $lesErreurs[] = "Le modèle n'est pas renseigné";
    }

    if (trim($site_insta) === '') {
        $lesErreurs[] = "Le site d'installation n'est pas renseigné";
    }

    if (empty($lesErreurs)) {
        try {
            Coordsic::inscrireCopieur($num_serie, $modele, $bdd, $site_insta);
            $_SESSION['message']['success'] = 'Imprimante ajoutée avec succès.';
            header('Location:/inscrireCopieur');
            exit();
        } catch (PDOException $th) {
            if ($th->getCode() === "23000") {
                $_SESSION['message']['error'] = "Une imprimante possède déjà le numéro de série '" . $num_serie . "'. <br> <a target='_blank' href='imprimante/$num_serie'>Cliquez ici pour en savoir plus</a>";
            } else {
                $_SESSION['message']['error'] = "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur du site.";
            }
            var_dump($th);
            
        }
    } else {
        $_SESSION['message']['error'] = $lesErreurs;
    }
}
?>

<div class="container">
    <form class="border p-3" action="/inscrireCopieur" method="post">
        
        <?= messageForm($lesErreurs) ?>

        <h1>Inscrire une machine</h1>
        
        <i>Votre machine n'est pas répertoriée dans Sapollon ? Inscrivez-la dès maintenant.</i>

        <div class="mt-5 mb-3">
            <label for="num_serie" class="form-label">Numéro de Série<span class="obligatoire">*</span></label>
            <input type="text" class="form-control" name="num_serie" id="num_serie" placeholder="Saisir le numéro de série" autofocus>
        </div>

        <div class="mb-3">
            <label for="modele" class="form-label">Modèle<span class="obligatoire">*</span></label>
            <input type="text" class="form-control" name="modele" id="modele" placeholder="Saisir le modèle">
        </div>

        <div class="mb-3">
            <label for="site_insta" class="form-label">Site d'installation<span class="obligatoire">*</span></label>
            <input type="text" class="form-control" name="site_insta" id="site_insta" placeholder="Saisir l'emplacement de la machine">
        </div>

        <button class="btn btn-primary" id="add_copieur">Ajouter</button>

        <?= mandatoryFieldMessage() ?>
    </form>
</div>