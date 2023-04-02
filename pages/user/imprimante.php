<?php
use App\Imprimante;

$imprimante = Imprimante::getImprimante($params['num']);

if (!empty($_POST)) {
    print_r($_POST);
}

function addInformationForm($allowEdit, $var, $titre, $value, $type = 'text'): string
{
    $allowInput = !$allowEdit ? "disabled" : "";
    $styleForbiddenInput = !$allowEdit ? "cursor: no-drop;" : "";
    $nameAllowedInput = !$allowEdit ? "name=" . $var : "";
    return <<<HTML
    <div class="row g-3 align-items-center mb-3">
        <div class="col-auto">
            <label for="$var" class="col-form-label">$titre</label>
        </div>
        <div class="col-auto">
            <input type="$type" id="$var" $nameAllowedInput class="form-control" value="$value" style="$styleForbiddenInput" $allowInput>
        </div>
    </div>
HTML;
}
?>

<?php if (count($imprimante) > 0) : ?>
    <form action="" method="post" class="mt-5 p-3">
        <?= addInformationForm(false, 'num_ordo', 'N° ORDO', htmlentities($imprimante[Imprimante::getChamps('champ_num_ordo')])) ?>
        <?= addInformationForm(false, 'num_serie', 'N° de Série', htmlentities($imprimante[Imprimante::getChamps('champ_num_serie')])) ?>
        <?= addInformationForm(false, 'bdd', 'BDD', htmlentities($imprimante[Imprimante::getChamps('champ_bdd')])) ?>
        <?= addInformationForm(true, 'statut', 'Statut Projet', htmlentities($imprimante[Imprimante::getChamps('champ_statut')])) ?>
        <?= addInformationForm(true, 'date_cde_minarm', 'Date Commande MINARM', htmlentities($imprimante[Imprimante::getChamps('champ_date_cde_minarm')])) ?>
        <?= addInformationForm(true, 'modele', 'Modèle', htmlentities($imprimante[Imprimante::getChamps('champ_modele')])) ?>
        <?= addInformationForm(true, 'config', 'Config', htmlentities($imprimante[Imprimante::getChamps('champ_config')])) ?>
        <?= addInformationForm(true, 'num_oracle', 'N° Oracle', htmlentities($imprimante[Imprimante::getChamps('champ_num_oracle')])) ?>
        <?= addInformationForm(true, 'hostname', 'HostName', htmlentities($imprimante[Imprimante::getChamps('champ_hostname')])) ?>
        <?= addInformationForm(true, 'reseau', 'Réseau', htmlentities($imprimante[Imprimante::getChamps('champ_reseau')])) ?>
        <?= addInformationForm(true, 'entite_beneficiaire', 'Entité Bénéficiaire', htmlentities($imprimante[Imprimante::getChamps('champ_entite_beneficiaire')])) ?>
        <?= addInformationForm(true, 'credo_unite', 'Credo unité', htmlentities($imprimante[Imprimante::getChamps('champ_credo_unite')])) ?>
        <?= addInformationForm(true, 'cp', 'CP Insta', htmlentities($imprimante[Imprimante::getChamps('champ_cp')])) ?>
        <?= addInformationForm(true, 'dep', 'DEP Insta', htmlentities($imprimante[Imprimante::getChamps('champ_dep')])) ?>
        <?= addInformationForm(true, 'site_installation', 'Site installation', htmlentities($imprimante[Imprimante::getChamps('champ_site_installation')])) ?>
        <?= addInformationForm(true, 'localisation', 'Localisation', htmlentities($imprimante[Imprimante::getChamps('champ_localisation')])) ?>
        <?= addInformationForm(true, 'accessoires', 'Accessoires', htmlentities($imprimante[Imprimante::getChamps('champ_accessoires')])) ?>

        <button class="btn btn-success">Sauvegarder</button>
    </form>
<?php else : ?>
    <div class="container alert alert-danger text-center">
        Cette imprimante n'existe pas
        <div class="mt-3"></div>
        <a class="btn btn-primary" href="<?= $router->url('list_machines') ?>">Retour</a>
    </div>
<?php endif ?>