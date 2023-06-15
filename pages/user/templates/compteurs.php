<?php

use App\Compteur;

$total = count($lesResultatsSansPagination);
$nb_pages = ceil($total / $nb_results_par_page);

if (isset($_GET['csv']) && $_GET['csv'] === "yes") {
    $headers = '';
    foreach (Compteur::testChamps(false) as $nom_input => $props) {
        $headers .= $props['libelle'] . ";";
    }
    Compteur::downloadCSV($headers, 'liste_compteurs', $lesResultatsSansPagination);
}
?>

<div class="p-4">

    <?php if (isset($_GET['d'])) : ?>
        <div class="alert alert-success text-center">
            Le compteur a bien été supprimé.
        </div>
    <?php endif ?>
    <?php if (isset($_GET['a'])) : ?>
        <div class="alert alert-success text-center">
            Le compteur a bien été ajouté.
        </div>
    <?php endif ?>
    <?php if (isset($_GET['e'])) : ?>
        <div class="alert alert-danger text-center">
            Un problème technique a été rencontré.
        </div>
    <?php endif ?>

    <?php require_once 'header.php' ?>

    <?php if ($page <= $nb_pages) : ?>
        <?php require_once 'pagination.php' ?>

        <table class="table table-striped table-bordered personalTable">
            <thead>
                <tr>
                    <td class="actions">Actions</td>
                    <?php foreach (Compteur::testChamps($perimetre) as $nom_input => $props) : ?>
                        <th id="<?= $nom_input ?>"><?= $props['libelle'] ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lesResultats as $data) : $num_serie = htmlentities($data['num_serie']);
                    $date = htmlentities($data['date']); ?>
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-list"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="imprimante/<?= $num_serie ?>"><i class="fa-solid fa-eye"></i> Voir l'imprimante</a></li>

                                    <?php if ($url === 'compteurs_perimetre') : ?>
                                        <li><a class="dropdown-item" href="supprimer-releve/<?= $num_serie ?>/<?= $date ?>" onclick="return confirm('Voulez-vous supprimer ce compteur ?');"><i class="fa-solid fa-trash"></i> Supprimer ce relevé</a></li>
                                    <?php endif ?>
                                </ul>
                            </div>
                        </td>
                        <?php foreach ($data as $nom_input => $value) :
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
        <h3 class="mt-5">Aucun compteur trouvé</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_counter"><i class="fa-solid fa-plus"></i> Ajouter un compteur</button>
        <a id="btn_exception" class="btn btn-secondary text-white" href="<?= $router->url('view_import_counters') ?>"><i class="fa-solid fa-plus"></i><i class="fa-solid fa-file mx-2"></i> Ajouter massivement des compteurs</a>
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
                        <?php foreach (Compteur::testChamps($perimetre) as $nom_input => $props) : ?>
                            <option value="<?= $nom_input ?>" <?php if ($order === $nom_input) : ?>selected<?php endif ?>><?= $props['libelle'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <select class="selectize col-sm-4" id="ordertype" name="ordertype">
                        <option value="ASC" <?php if ($ordertype === 'ASC') : ?>selected<?php endif ?>>Croissant</option>
                        <option value="DESC" <?php if ($ordertype === 'DESC') : ?>selected<?php endif ?>>Décroissant</option>
                    </select>
                </div>

                <?php foreach (Compteur::testChamps($perimetre) as $nom_input => $props) : ?>
                    <div class="row mb-3">
                        <label for="<?= $nom_input ?>" class="col-sm-4"><?= $props['libelle'] ?> :</label>
                        <div class="col-sm-3">
                            <input type="text" id="<?= $nom_input ?>" name="<?= $nom_input ?>" class="form-control" value="<?= getValeurInput($nom_input) ?>">
                        </div>
                    </div>
                <?php endforeach ?>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>
    </div>
</div>