<?php

use App\Imprimante;
use App\Panne;

$total = count($lesResultatsSansPagination);
$nb_pages = ceil($total / $nb_results_par_page);

if (isset($_GET['csv']) && $_GET['csv'] === "yes") {
    $champs = '';
    foreach (colonnes(Panne::ChampPannes()) as $id => $nom) {
        $champs .= $nom . ";";
    }
    Imprimante::downloadCSV($champs, 'liste_pannes', $lesResultatsSansPagination);
}
?>

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
            <h3 class="mt-5">Nombre total de pannes : <?= $total ?></h3>
        </div>

        <table id="table_imprimantes" class="table table-striped table-bordered personalTable">
            <?= Panne::ChampPannes() ?>
            <tbody>
                <?php foreach ($lesResultats as $panne) :
                    $id_event = htmlentities($panne['id_event']);
                    $num_serie = htmlentities($panne['num_serie']);
                    $contexte = htmlentities($panne['contexte']);
                    $type_panne = htmlentities($panne['type_panne']);
                    $statut_intervention = htmlentities($panne['statut_intervention']);
                    $commentaires = htmlentities($panne['commentaires']);
                    $date_evolution = htmlentities(convertDate($panne['date_evolution']));
                    $heure_evolution = htmlentities($panne['heure_evolution']);
                    $modif_par = htmlentities($panne['maj_par']);
                    $modif_date = htmlentities($panne['maj_date']);
                    $fichier = htmlentities($panne['fichier']);
                    $ouverture = convertDate(htmlentities($panne['ouverture']));
                    $fermeture = (htmlentities($panne['fermeture']));
                ?>
                    <tr>
                        <td><a href="imprimante/<?= $num_serie ?>"><?= $num_serie ?></a></td>
                        <td><?= $id_event ?></td>
                        <td><?= $contexte ?></td>
                        <td><?= $type_panne ?></td>
                        <td><?= $statut_intervention ?></td>
                        <td><?= $commentaires ?></td>
                        <td><?= $date_evolution ?></td>
                        <td><?= $heure_evolution ?></td>
                        <td><?= $modif_par ?></td>
                        <td><?= $modif_date ?></td>
                        <td><?= $fichier ?></td>
                        <td><?= $ouverture ?></td>
                        <td><?= $fermeture ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else : ?>
        <h3 class="mt-5">Aucune panne trouvée</h3>
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

                <div class="row mb-3">
                    <label for="order" class="col-sm-4">Trier par</label>
                    <select class="selectize col-sm-4" id="order" name="order">
                        <?php foreach (Imprimante::ChampsCopieur($perimetre) as $key => $s) : ?>
                            <option value="<?= $key ?>" <?php if ($order === $key) : ?>selected<?php endif ?>><?= $s['libelle'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <?php foreach ($laTable as $nom_input => $props) : ?>
                    <?php if ($nom_input !== 'order') :  ?>
                        <div class="row mb-3">
                            <label for="<?= $nom_input ?>" class="col-sm-4"><?= $props['libelle'] ?> :</label>
                            <div class="col-sm-3">
                                <input type="text" id="<?= $nom_input ?>" name="<?= $nom_input ?>" class="form-control" value="<?= getValeurInput($nom_input) ?>">
                            </div>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>
    </div>
</div>