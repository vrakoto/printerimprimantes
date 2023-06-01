<?php
use App\Compteur;
use App\Imprimante;
use App\User;

$num = htmlentities($params['num']);

if (isset($_POST['csv'])) {
    $compteurs = Compteur::searchCompteurByNumSerie($num);
    $filename = $num . "_compteurs";
    $champs = '';
    foreach (colonnes(Compteur::ChampsCompteur()) as $id => $nom) {
        $champs .= $nom . ";";
    }
    Compteur::downloadCSV($champs, $filename, $compteurs);
    exit();
}

$imprimante = Imprimante::getImprimante($num);
$releves = Compteur::searchCompteurByNumSerie($num);
$responsabilites = Imprimante::getSesResponsables($num);

$isEligible = (User::getRole() === 2 || User::getRole() === 4);

$champs = [];
if (!empty($_POST)) {
    $oracle = htmlentities($_POST['num_oracle']);
    $config = htmlentities($_POST['config']);
    $modele = htmlentities($_POST['modele']);
    $hostname = htmlentities($_POST['hostname']);
    $reseau = htmlentities($_POST['reseau']);
    $mac = htmlentities($_POST['adresse_mac']);
    $entite_beneficiaire = htmlentities($_POST['entite_beneficiaire']);
    $credo_unite = htmlentities($_POST['credo_unite']);
    $cp = (int)$_POST['cp'];
    $dep = (int)$_POST['dep'];
    $adresse = htmlentities($_POST['adresse']);
    $site_installation = htmlentities($_POST['site_installation']);
    $localisation = htmlentities($_POST['localisation']);
    $accessoires = htmlentities($_POST['accessoires']);

    try {
        Imprimante::editImprimante($num, $oracle, $config, $modele, $hostname, $reseau, $mac, $entite_beneficiaire, $credo_unite, $cp, $dep, $adresse, $site_installation, $localisation, $accessoires);
    } catch (\Throwable $th) {
        die('Erreur interne' . $th->getMessage());
    }
}

function addInformationForm($allowEdit, $var, $titre, $value, $type = 'text'): string
{
    $allowInput = !$allowEdit ? "disabled" : "";
    $styleForbiddenInput = !$allowEdit ? "cursor: no-drop;" : "";
    $nameAllowedInput = $allowEdit ? "name=" . $var : "";
    return <<<HTML
    <div class="row mb-3">
        <label for="$var" class="col-sm-1 label">$titre :</label>
        <div class="col-sm-2">
            <input type="$type" id="$var" $nameAllowedInput class="form-control" value="$value" style="$styleForbiddenInput" $allowInput>
        </div>
    </div>
HTML;
}

$jsfile = 'imprimante';
?>

<div class="mt-5"></div>

<?php if (count($imprimante) > 0) : ?>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Information</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Responsabilité</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
            <?php if ($isEligible) : ?>
                <form action="" method="post" class="mt-3 p-3">
                <?php else : ?>
                    <div class="mt-5 p-3">
                    <?php endif ?>
                    <?= addInformationForm(false, 'num_ordo', 'N° ORDO', htmlentities($imprimante[Imprimante::getChamps('champ_num_ordo')])) ?>
                    <?= addInformationForm(false, 'num_serie', 'N° de Série', htmlentities($imprimante[Imprimante::getChamps('champ_num_serie')])) ?>
                    <?= addInformationForm(false, 'bdd', 'BDD', htmlentities($imprimante[Imprimante::getChamps('champ_bdd')])) ?>
                    <?= addInformationForm(false, 'statut', 'Statut Projet', htmlentities($imprimante[Imprimante::getChamps('champ_statut')]), 'text') ?>
                    <?= addInformationForm(false, 'date_cde_minarm', 'Date Commande MINARM', htmlentities($imprimante[Imprimante::getChamps('champ_date_cde_minarm')])) ?>
                    <?= addInformationForm(true, 'modele', 'Modèle', htmlentities($imprimante[Imprimante::getChamps('champ_modele')])) ?>
                    <?= addInformationForm(true, 'config', 'Config', htmlentities($imprimante[Imprimante::getChamps('champ_config')])) ?>
                    <?= addInformationForm(true, 'num_oracle', 'N° Oracle', htmlentities($imprimante[Imprimante::getChamps('champ_num_oracle')])) ?>
                    <?= addInformationForm(true, 'hostname', 'HostName', htmlentities($imprimante[Imprimante::getChamps('champ_hostname')])) ?>
                    <?= addInformationForm(true, 'reseau', 'Réseau', htmlentities($imprimante[Imprimante::getChamps('champ_reseau')])) ?>
                    <?= addInformationForm(true, 'adresse_mac', 'Adresse MAC@', htmlentities($imprimante[Imprimante::getChamps('champ_mac')])) ?>
                    <?= addInformationForm(true, 'entite_beneficiaire', 'Entité bénéficiaire', htmlentities($imprimante[Imprimante::getChamps('champ_entite_beneficiaire')])) ?>
                    <?= addInformationForm(true, 'credo_unite', 'Credo unité', htmlentities($imprimante[Imprimante::getChamps('champ_credo_unite')])) ?>
                    <?= addInformationForm(true, 'cp', 'CP Insta', htmlentities($imprimante[Imprimante::getChamps('champ_cp')])) ?>
                    <?= addInformationForm(true, 'dep', 'DEP Insta', htmlentities($imprimante[Imprimante::getChamps('champ_dep')])) ?>
                    <?= addInformationForm(true, 'adresse', 'Adresse', htmlentities($imprimante[Imprimante::getChamps('champ_adresse')])) ?>
                    <?= addInformationForm(true, 'site_installation', 'Site installation', htmlentities($imprimante[Imprimante::getChamps('champ_site_installation')])) ?>
                    <?= addInformationForm(true, 'localisation', 'Localisation', htmlentities($imprimante[Imprimante::getChamps('champ_localisation')])) ?>
                    <?= addInformationForm(true, 'accessoires', 'Accessoires', htmlentities($imprimante[Imprimante::getChamps('champ_accessoires')])) ?>

                    <?php if ($isEligible) : ?>
                        <button type="submit" class="btn btn-success">Sauvegarder</button>
                </form>
            <?php else : ?>
        </div>
    <?php endif ?>
    </div>
    <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
        <div class="container mt-3">
            <?php if (count($responsabilites) > 0): ?>
            <table id="table_users_copieurs" class="table table-striped table-bordered personalTable table-responsive">
                <thead>
                    <tr>
                        <th id="num_serie">Responsable</th>
                        <th id="bdd">Numéro de Série</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($responsabilites as $responsable): ?>
                    <tr>
                        <td><?= htmlentities($responsable['responsable']) ?></td>
                        <td><?= htmlentities($responsable['num_serie']) ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?php else: ?>
                <h3 class="text-center">Aucun responsable</h3>
            <?php endif ?>
        </div>
    </div>

<?php else : ?>
    <div class="container alert alert-danger text-center">
        Cette machine n'existe pas
        <div class="mt-3"></div>
        <a class="btn btn-primary" href="<?= $router->url('list_machines') ?>">Retour</a>
    </div>
<?php endif ?>