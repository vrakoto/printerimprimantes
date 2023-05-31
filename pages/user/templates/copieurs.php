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

function checkboxColonnes($var, $titre): string
{
    return <<<HTML
    <div class="row mb-3">
        <div class="col-sm-1">
            <input class="form-check-input" type="checkbox" id="checked_$var" name="checked_$var">
        </div>
        <label for="checked_$var" class="col-sm-4 label">$titre</label>
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
        <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-filter"></i> Trier / Rechercher</button>
        <a class="mx-1 btn btn-secondary" href="/<?= $url ?>"><i class="fa-solid fa-arrow-rotate-left"></i> Réinitialiser la recherche</a>
        <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_colonnes"><i class="fa-solid fa-filter"></i> Affichage des colonnes</button>
    </div>

    <?php if ($url === 'copieurs_perimetre' && User::getRole() !== 2) : ?>
        <div class="mt-5">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_add_machine_area">Ajouter un copieur dans mon périmètre</button>
            <?php if ($total > 0) : ?>
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
            <?php //Imprimante::ChampsCopieur() 
            ?>
            <thead>
                <tr>
                    <?php
                    foreach (Imprimante::testChamps() as $nom_input => $nom_bdd) : ?>
                        <?php if (isset($_GET["checked_$nom_input"])) : ?>
                            <th id="<?= $nom_input ?>"><?= $nom_bdd ?></th>
                        <?php endif ?>
                    <?php endforeach ?>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($lesResultats as $data) : ?>
                    <tr>
                    <?php foreach ($data as $t => $value): ?>
                        <?php if (isset($_GET["checked_$t"])) : ?>
                            <td class="<?= $t ?>"><?= $value ?></td>
                        <?php endif ?>
                    <?php endforeach ?>
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
                <h1 class="modal-title fs-5">Effectuer une recherche et/ou un tri</h1>
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
                        <select class="selectize col-sm-4" id="ordertype" name="ordertype">
                            <option value="ASC" <?php if ($ordertype === 'ASC') : ?>selected<?php endif ?>>Croissant</option>
                            <option value="DESC" <?php if ($ordertype === 'DESC') : ?>selected<?php endif ?>>Décroissant</option>
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

<div class="modal fade" id="modal_colonnes" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Affichage des colonnes</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <?php foreach (colonnes(Imprimante::ChampsCopieur()) as $nom_input => $props) {
                    if ($nom_input !== 'order') {
                        echo checkboxColonnes($nom_input, $props);
                    }
                } ?>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>
    </div>
</div>