<?php

use App\Imprimante;
use App\Panne;
use App\UsersCopieurs;

$total = count($lesResultatsSansPagination);
$nb_pages = ceil($total / $nb_results_par_page);

if (isset($_GET['csv']) && $_GET['csv'] === "yes") {
    $champs = '';
    foreach (colonnes(UsersCopieurs::ChampUsersCopieurs()) as $id => $nom) {
        $champs .= $nom . ";";
    }
    Imprimante::downloadCSV($champs, 'liste_responsables', $lesResultatsSansPagination);
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
        <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-filter"></i> Recherche</button>
        <a class="mx-1 btn btn-secondary" href="/<?= $url ?>"><i class="fa-solid fa-arrow-rotate-left"></i> Réinitialiser les recherches</a>
    </div>

    <?php if ($page <= $nb_pages) : ?>
        <div class="d-flex justify-content-between align-items-center">
            <div id="pagination" class="mt-5 mb-3">
                <a class="<?= $page != 1 ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=1&<?= $fullURL ?>">1</a>
                <a <?php if ($page <= 1) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page - 1 ?>&<?= $fullURL ?>"><i class="fa-solid fa-arrow-left"></i></a>

                <button class="btn btn-secondary" style="cursor: unset;"><?= $page ?></button>

                <a <?php if ($page >= $nb_pages) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page + 1 ?>&<?= $fullURL ?>"><i class="fa-solid fa-arrow-right"></i></a>
                <a class="<?= $page != $nb_pages ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=<?= $nb_pages ?>&<?= $fullURL ?>"><?= $nb_pages ?></a>
            </div>
            <h3 class="mt-5">Nombre total de responsabilités : <?= $total ?></h3>
        </div>

        <table id="table_imprimantes" class="table table-striped table-bordered personalTable">
            <?= UsersCopieurs::ChampUsersCopieurs() ?>
            <tbody>
                <?php foreach ($lesResultats as $resultat) :
                    $gpn = htmlentities($resultat['gpn']);
                    $num_serie = htmlentities($resultat['num_serie']);
                ?>
                    <tr>
                        <td><?= $gpn ?></td>
                        <td><a href="imprimante/<?= $num_serie ?>"><?= $num_serie ?></a></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else : ?>
        <h3 class="mt-5">Aucune responsabilitée trouvée</h3>
    <?php endif ?>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Recherche</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <?php if (isset($params['order'])) : ?>
                    <div class="row mb-3">
                        <label for="order" class="col-sm-4">Trier par</label>
                        <select class="selectize col-sm-4" id="order" name="order">
                            <?php foreach (colonnes(UsersCopieurs::ChampUsersCopieurs()) as $key => $s) : ?>
                                <option value="<?= $key ?>" <?php if ($order === $key) : ?>selected<?php endif ?>><?= $s ?></option>
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