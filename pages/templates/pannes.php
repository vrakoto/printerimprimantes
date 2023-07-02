<?php

use App\Imprimante;
use App\Panne;

$total = count($lesResultatsSansPagination);
$nb_pages = ceil($total / $nb_results_par_page);

if (isset($_GET['download_csv'])) {
    Imprimante::downloadCSV(Panne::ChampPannes(), 'liste_pannes', $lesResultatsSansPagination);
}
?>

<div class="p-4">
    <?php require_once 'header.php' ?>

    <?php if ($page <= $nb_pages) : ?>
        <?php require_once 'pagination.php' ?>

        <table id="table_imprimantes" class="table table-striped table-bordered personalTable">
            <thead>
                <tr>
                    <td class="actions">Actions</td>
                    <?php foreach (Panne::ChampPannes() as $nom_input => $props) : ?>
                        <th id="<?= $nom_input ?>"><?= $props['libelle'] ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lesResultats as $data): ?>
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-list"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/imprimante/<?= $data['num_serie'] ?>"><i class="fa-solid fa-eye"></i> Voir le copieur</a></li>
                                </ul>
                            </div>
                        </td>
                        <?php foreach ($data as $nom_input => $value):
                            switch ($nom_input) {
                                case 'maj_date':
                                    if (!empty($value)) {
                                        $value = convertDate($value, true);
                                    }
                                break;

                                case 'ouverture':
                                    $value = convertDate($value, true);
                                break;

                                case 'fermeture':
                                    if (!empty($value)) {
                                        $value = convertDate($value, true);
                                    }
                                break;

                                case 'date_evolution':
                                    $value = convertDate($value);
                                break;
                            }
                        ?>
                            <td class="<?= htmlentities($nom_input) ?>"><?= htmlentities($value) ?></td>
                        <?php endforeach ?>
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
                    <select class="form-select col-sm-4" id="order" name="order">
                        <?php foreach (Panne::ChampPannes() as $key => $s) : ?>
                            <option value="<?= $key ?>" <?php if ($order === $key) : ?>selected<?php endif ?>><?= $s['libelle'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <select class="form-select col-sm-4" id="ordertype" name="ordertype">
                        <option value="ASC" <?php if ($ordertype === 'ASC') : ?>selected<?php endif ?>>Croissant</option>
                        <option value="DESC" <?php if ($ordertype === 'DESC') : ?>selected<?php endif ?>>Décroissant</option>
                    </select>
                </div>

                <?php foreach (Panne::ChampPannes() as $nom_input => $props) : ?>
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