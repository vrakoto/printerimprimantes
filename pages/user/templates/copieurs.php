<?php

use App\Imprimante;
use App\User;

$total = count($lesResultatsSansPagination);
$atLeastOneResult = (count($lesResultats)) > 0 ? true : false;
$nb_pages = ceil($total / $nb_results_par_page);

if (isset($_GET['csv']) && $_GET['csv'] === "yes") {
    $champs = '';
    foreach (colonnes(Imprimante::ChampsCopieur()) as $id => $nom) {
        $champs .= $nom . ";";
    }
    Imprimante::downloadCSV($champs, 'liste_machines', $lesResultatsSansPagination);
}

function addInformationForm($var, $titre, $value, array $size): string
{
    $labelSize = $size[0];
    $inputSize = $size[1];
    return <<<HTML
    <div class="row mb-3">
        <label for="$var" class="col-sm-$labelSize label">$titre :</label>
        <div class="col-sm-$inputSize">
            <input type="text" id="$var" name="$var" class="form-control" value="$value">
        </div>
    </div>
HTML;
}
?>

<style>
    thead th:hover {
        background-color: orange;
        cursor: pointer;
    }

    #pagination a {
        color: black;
        padding: 8px 16px;
        transition: background-color .3s;
        border: 1px solid #ddd;
    }

    #pagination a:hover {
        background-color: #ddd;
    }
</style>

<div class="p-4">
    <div class="mt-2" id="header">
        <h1><?= $title ?></h1>

        <button class="btn btn-success" id="downloadCSV" title="Télécharger les données en CSV"><i class="fa-solid fa-download"></i> Télécharger les données</button>
        <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-filter"></i> Rechercher</button>
        <a class="mx-1 btn btn-secondary" href="/<?= $url ?>"><i class="fa-solid fa-arrow-rotate-left"></i> Réinitialiser la recherche</a>
    </div>

    <?php if ($url === 'copieurs_perimetre' && User::getRole() !== 2) : ?>
        <div class="mt-5">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_add_machine_area">Ajouter un copieur dans mon périmètre</button>
            <?php if ($total > 0): ?>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal_remove_machine_area">Retirer un copieur de mon périmètre</button>
            <?php endif ?>
        </div>
    <?php endif ?>

    <?php if ($atLeastOneResult) : ?>
        <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
            <div id="pagination">
                <a class="<?= $page != 1 ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=1&<?= $fullURL ?>">1</a>
                <a <?php if ($page <= 1) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page - 1 ?>&<?= $fullURL ?>"><i class="fa-solid fa-arrow-left"></i></a>

                <button class="btn btn-secondary" style="cursor: unset;"><?= $page ?></button>

                <a <?php if ($page >= $nb_pages) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page + 1 ?>&<?= $fullURL ?>"><i class="fa-solid fa-arrow-right"></i></a>
                <a class="<?= $page != $nb_pages ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=<?= $nb_pages ?>&<?= $fullURL ?>"><?= $nb_pages ?></a>
            </div>

            <h3 class="mt-5">Nombre total de copieurs : <?= $total ?></h3>
        </div>

        <table id="table_imprimantes" class="table table-striped table-bordered personalTable triggerDT">
            <?= Imprimante::ChampsCopieur() ?>
            <tbody>
                <?php foreach ($lesResultats as $data) :
                    $num_serie = htmlentities($data['num_serie']);
                    $bdd = htmlentities($data['bdd']);
                    $modele = htmlentities($data['modele']);
                    $statut = htmlentities($data['statut']);
                    $site_installation = htmlentities($data['site_installation']);
                    $num_ordo = htmlentities($data['num_ordo']);
                    $date_cde_minarm = htmlentities($data['date_cde_minarm']);
                    $config = htmlentities($data['config']);
                    $num_oracle = htmlentities($data['num_oracle']);
                    $num_sfdc = htmlentities($data['num_sfdc']);
                    $hostname = htmlentities($data['hostname']);
                    $reseau = htmlentities($data['reseau']);
                    $adresse_mac = htmlentities($data['adresse_mac']);
                    $entite_beneficiaire = htmlentities($data['entite_beneficiaire']);
                    $localisation = htmlentities($data['localisation']);
                    $cp_insta = htmlentities($data['cp_insta']);
                    $dep_insta = htmlentities($data['dep_insta']);
                    $adresse = htmlentities($data['adresse']);
                    $credo_unite = htmlentities($data['credo_unite']);
                    $service_uf = htmlentities($data['service_uf']);
                    $accessoires = htmlentities($data['accessoires']);
                ?>
                    <tr>
                        <td class="num_serie"><?= $num_serie ?></td>
                        <td class="bdd"><?= $bdd ?></td>
                        <td class="modele"><?= $modele ?></td>
                        <td class="statut"><?= $statut ?></td>
                        <td class="site_installation"><?= $site_installation ?></td>
                        <td class="num_ordo"><?= $num_ordo ?></td>
                        <td class="date_cde_minarm"><?= $date_cde_minarm ?></td>
                        <td class="config"><?= $config ?></td>
                        <td class="num_oracle"><?= $num_oracle ?></td>
                        <td class="num_sfdc"><?= $num_sfdc ?></td>
                        <td class="hostname"><?= $hostname ?></td>
                        <td class="reseau"><?= $reseau ?></td>
                        <td class="adresse_mac"><?= $adresse_mac ?></td>
                        <td class="entite_beneficiaire"><?= $entite_beneficiaire ?></td>
                        <td class="localisation"><?= $localisation ?></td>
                        <td class="cp_insta"><?= $cp_insta ?></td>
                        <td class="dep_insta"><?= $dep_insta ?></td>
                        <td class="adresse"><?= $adresse ?></td>
                        <td class="credo_unite"><?= $credo_unite ?></td>
                        <td class="service_uf"><?= $service_uf ?></td>
                        <td class="accessoires"><?= $accessoires ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else : ?>
        <h3 class="mt-4">Aucune machine trouvée</h3>
    <?php endif ?>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Effectuer une recherche</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <?php if (isset($params['order'])) : ?>
                    <div class="row mb-3">
                        <label for="order" class="col-sm-4">Trier par</label>
                        <select class="selectize col-sm-4" id="order" name="order">
                            <?php foreach (colonnes(Imprimante::ChampsCopieur()) as $key => $s) : ?>
                                <option value="<?= $key ?>" <?php if ($order === $key) : ?>selected<?php endif ?>><?= $s ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                <?php endif ?>

                <?php if ($url !== 'copieurs_perimetre') : ?>
                    <div class="row mb-3">
                        <label for="statut_projet" class="col-sm-4 label">Statut</label>
                        <select class="selectize col-sm-4" name="statut_projet" id="statut_projet">
                            <option value="%">0 - N'importe</option>
                            <?php foreach (Imprimante::getLesStatuts() as $s) : $s = htmlentities($s['STATUT PROJET']); ?>
                                <option value="<?= $s ?>" <?php if ($searching_statut === $s) : ?>selected<?php endif ?>><?= $s ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                <?php endif ?>

                <?php foreach ($params as $nom_input => $props) {
                    if ($nom_input !== 'statut_projet' && $nom_input !== 'order') { // statut_projet doit être personnalisé pour les select
                        echo addInformationForm($nom_input, $props['nom_db'], getValeurInput($nom_input), [4, 3]);
                    }
                } ?>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>
    </div>
</div>