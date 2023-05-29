<?php

use App\Compteur;
use App\Imprimante;

$total = count($lesResultatsSansPagination);
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

    <?php if ($page <= $nb_pages) : ?>
        <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
            <div id="pagination">
                <a class="<?= $page != 1 ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=1&<?= $fullURL ?>">1</a>
                <a <?php if ($page <= 1) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page - 1 ?>&<?= $fullURL ?>"><i class="fa-solid fa-arrow-left"></i></a>

                <button class="btn btn-secondary" style="cursor: unset;"><?= $page ?></button>

                <a <?php if ($page >= $nb_pages) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page + 1 ?>&<?= $fullURL ?>"><i class="fa-solid fa-arrow-right"></i></a>
                <a class="<?= $page != $nb_pages ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=<?= $nb_pages ?>&<?= $fullURL ?>"><?= $nb_pages ?></a>
            </div>

            <h3 class="mt-5">Nombre total de compteurs : <?= $total ?></h3>
        </div>

        <table id="table_imprimantes" class="table table-striped table-bordered personalTable triggerDT">
            <?= Compteur::ChampsCompteur() ?>
            <tbody>
                <?php foreach ($lesResultats as $data) :
                    $num_serie = htmlentities($data['num_serie']);
                    $bdd = htmlentities($data['bdd']);
                    $date = htmlentities(convertDate($data['Date']));
                    $total_101 = (int)$data['total_101'];
                    $total_112 = (int)$data['total_112'];
                    $total_113 = (int)$data['total_113'];
                    $total_122 = (int)$data['total_122'];
                    $total_123 = (int)$data['total_123'];
                    $modif_par = htmlentities($data['gpn']);
                    $date_maj = htmlentities(convertDate($data['date_maj'], true));
                    $type_releve = htmlentities($data['type_releve']);
                ?>
                    <tr>
                        <td class="num_serie"><?= $num_serie ?></td>
                        <td class="bdd"><?= $bdd ?></td>
                        <td class="date"><?= $date ?></td>
                        <td class="total_101"><?= $total_101 ?></td>
                        <td class="total_112"><?= $total_112 ?></td>
                        <td class="total_113"><?= $total_113 ?></td>
                        <td class="total_122"><?= $total_122 ?></td>
                        <td class="total_123"><?= $total_123 ?></td>
                        <td class="modif_par"><?= $modif_par ?></td>
                        <td class="date_maj"><?= $date_maj ?></td>
                        <td class="type_releve"><?= $type_releve ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else : ?>
        <h3 class="mt-4">Aucun compteur trouvé</h3>
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

                <div class="row mb-3">
                    <label for="order" class="col-sm-4">Trier par</label>
                    <select class="selectize col-sm-4" id="order" name="order">
                        <?php foreach (colonnes(Compteur::ChampsCompteur()) as $key => $s) : ?>
                            <option value="<?= $key ?>" <?php if ($order === $key) : ?>selected<?php endif ?>><?= $s ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <?php foreach ($params as $nom_input => $props) {
                    if ($nom_input !== 'order') {
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