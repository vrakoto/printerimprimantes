<?php

use App\Imprimante;
use App\User;

$total = count($lesResultatsSansPagination);
$atLeastOneResult = (count($lesResultats)) > 0 ? true : false;
$nb_pages = ceil($total / $nb_results_par_page);

if (isset($_GET['csv']) && $_GET['csv'] === "yes") {
    Imprimante::downloadCSV(Imprimante::ChampsCopieur(false, 'all'), 'liste_machines', $lesResultatsSansPagination);
}
$isURLCopieurs = true;
?>

<div class="p-4">
    <?php require_once 'header.php' ?>

    <?php if ($url === 'copieurs_perimetre' && User::getRole() !== 2) : ?>
        <div class="mt-5">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_add_machine_area">Ajouter un copieur dans mon périmètre</button>
            <?php if ($total > 0) : ?>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal_remove_machine_area">Retirer un copieur de mon périmètre</button>
            <?php endif ?>
        </div>
    <?php endif ?>

    <?php if ($atLeastOneResult) : ?>
        <?php require_once 'pagination.php' ?>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td class="actions">Actions</td>
                    <?php foreach (Imprimante::ChampsCopieur($perimetre, $showColumns) as $nom_input => $props) : ?>
                        <th id="<?= $nom_input ?>"><?= $props['libelle'] ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($lesResultats as $data) : $notEmptyNumSerie = !empty($data['num_serie']); ?>
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-list"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="imprimante/<?= $notEmptyNumSerie ? $data['num_serie'] : $data['num_ordo'] ?>"><i class="fa-solid fa-eye"></i> Voir</a></li>
                                    <?php if ($notEmptyNumSerie): // uniquement les num_serie non vides ?>
                                        <li><a class="dropdown-item" href="liste_compteurs?order=date_maj&num_serie=<?= htmlentities($data['num_serie']) ?>&ordertype=desc"><i class="fa-solid fa-book"></i> Relevés de compteurs</a></li>
                                    <?php endif ?>
                                </ul>
                            </div>
                        </td>
                        <?php foreach ($data as $nom_input => $value): ?>
                            <?php if (isset(Imprimante::ChampsCopieur($perimetre, $showColumns)[$nom_input])): ?>
                                <td class="<?= htmlentities($nom_input) ?>"><?= htmlentities($value) ?></td>
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

                <div class="row mb-3">
                    <label for="order" class="col-sm-4">Trier par</label>
                    <select class="selectize col-sm-4" id="order" name="order">
                        <?php foreach (Imprimante::ChampsCopieur($perimetre, $showColumns) as $nom_input => $props) : ?>
                            <option value="<?= $nom_input ?>" <?php if ($order === $nom_input) : ?>selected<?php endif ?>><?= $props['libelle'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <select class="selectize col-sm-4" id="ordertype" name="ordertype">
                        <option value="ASC" <?php if ($ordertype === 'ASC') : ?>selected<?php endif ?>>Croissant</option>
                        <option value="DESC" <?php if ($ordertype === 'DESC') : ?>selected<?php endif ?>>Décroissant</option>
                    </select>
                </div>
                
                <hr>

                <?php if ($url !== 'copieurs-sans-releve-trimestre'): ?>
                    <div class="row mb-3">
                        <label for="statut_projet" class="col-sm-4 label">Statut</label>
                        <select class="selectize col-sm-4" name="statut_projet" id="statut_projet">
                            <option value="%">0 - N'importe</option>
                            <?php foreach (Imprimante::getLesStatuts() as $s) : $s = htmlentities($s['STATUT PROJET']); ?>
                                <option value="<?= $s ?>" <?php if (getValeurInput('statut_projet') === $s) : ?>selected<?php endif ?>><?= $s ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                <?php endif ?>

                <?php foreach (Imprimante::ChampsCopieur($perimetre, $showColumns) as $nom_input => $props): ?>
                    <?php if ($nom_input !== 'statut_projet'): // statut_projet doit être personnalisé pour les select ?>
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