<?php

use App\Compteur;

$total = count($lesResultatsSansPagination);
$nb_pages = ceil($total / $nb_results_par_page);

if (isset($_GET['csv']) && $_GET['csv'] === "yes") {
    $headers = '';
    foreach (Compteur::testChamps() as $nom_input => $props) {
        $headers .= $props['libelle'] . ";";
    }
    Compteur::downloadCSV($headers, 'liste_machines', $lesResultatsSansPagination);
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

    <?php if (isset($_GET['d'])): ?>
        <div class="alert alert-success text-center">
            Le compteur a bien été supprimé.
        </div>
    <?php endif ?>
    <?php if (isset($_GET['a'])): ?>
        <div class="alert alert-success text-center">
            Le compteur a bien été ajouté.
        </div>
    <?php endif ?>
    <?php if (isset($_GET['e'])): ?>
        <div class="alert alert-danger text-center">
            Un problème technique a été rencontré.
        </div>
    <?php endif ?>
    
    <div class="mt-2" id="header">
        <h1><?= $title ?></h1>

        <button class="btn btn-success" id="downloadCSV" title="Télécharger les données en CSV"><i class="fa-solid fa-download"></i> Télécharger les données</button>
        <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-filter"></i> Trier / Rechercher</button>
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
                
                <?php if ($url === 'compteurs_perimetre'): ?>
            <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_counter"><i class="fa-solid fa-plus"></i> Ajouter un compteur</button>
        <?php endif ?>
            </div>

            <h3 class="mt-5">Nombre total de compteurs : <?= $total ?></h3>
        </div>

        <table id="table_imprimantes" class="table table-striped table-bordered personalTable triggerDT">
            <thead>
                <tr>
                    <td class="actions">Actions</td>
                    <?php foreach ($laTable as $nom_input => $props) : ?>
                        <th id="<?= $nom_input ?>"><?= $props['libelle'] ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lesResultats as $data): $num_serie = htmlentities($data['num_serie']); $date = htmlentities($data['date']); ?>
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-list"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="imprimante/<?= $num_serie ?>"><i class="fa-solid fa-eye"></i> Voir l'imprimante</a></li>
                                    <li><a class="dropdown-item" href="supprimer-releve/<?= $num_serie ?>/<?= $date ?>" onclick="return confirm('Voulez-vous supprimer ce compteur ?');"><i class="fa-solid fa-trash"></i> Supprimer ce relevé</a></li>
                                </ul>
                            </div>
                        </td>
                        <?php foreach ($data as $nom_input => $value):
                            if ($nom_input === 'date') {
                                $value = convertDate($value);
                            } else if ($nom_input === 'date_maj') {
                                $value = convertDate($value, true);
                            }
                        ?>
                            <td class="<?= htmlentities($nom_input) ?>"><?= htmlentities($value) ?></td>
                        <?php endforeach ?>
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
                <h1 class="modal-title fs-5">Effectuer une recherche et/ou un tri</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row mb-3">
                    <label for="order" class="col-sm-4">Trier par</label>
                    <select class="selectize col-sm-4" id="order" name="order">
                        <?php foreach ($laTable as $nom_input => $props) : ?>
                            <option value="<?= $nom_input ?>" <?php if ($order === $nom_input) : ?>selected<?php endif ?>><?= $props['libelle'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <select class="selectize col-sm-4" id="ordertype" name="ordertype">
                        <option value="ASC" <?php if ($ordertype === 'ASC') : ?>selected<?php endif ?>>Croissant</option>
                        <option value="DESC" <?php if ($ordertype === 'DESC') : ?>selected<?php endif ?>>Décroissant</option>
                    </select>
                </div>

                <?php foreach ($params as $nom_input => $props) {
                    if ($nom_input !== 'order') {
                        echo addInformationForm($nom_input, $laTable[$nom_input]['libelle'], getValeurInput($nom_input), [4, 3]);
                    }
                } ?>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>
    </div>
</div>