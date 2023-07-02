<?php

use App\Compteur;
use App\Imprimante;
use App\User;

$isURLCompteurs = true;
$total = count($lesResultatsSansPagination);
$nb_pages = ceil($total / $nb_results_par_page);

$lesChamps = Compteur::ChampsCompteur($perimetre);
$lesChamps_Search_Modal = $lesChamps;
unset($lesChamps_Search_Modal['date']);
unset($lesChamps_Search_Modal['total_101']);
unset($lesChamps_Search_Modal['total_112']);
unset($lesChamps_Search_Modal['total_113']);
unset($lesChamps_Search_Modal['total_122']);
unset($lesChamps_Search_Modal['total_123']);
unset($lesChamps_Search_Modal['total_123']);
unset($lesChamps_Search_Modal['date_maj']);

if (isset($_GET['download_csv'])) {
    Compteur::downloadCSV(Compteur::ChampsCompteur(false), 'liste_compteurs', $lesResultatsSansPagination);
}

if (isset($_GET['uniqueCompteurs'])) {
    $_SESSION['uniqueCompteurs'] = ($_SESSION['uniqueCompteurs'] === 'true') ? 'false' : 'true';
    header('Location:' . $url);
    exit();
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

    <?php require_once 'pagination.php' ?>
    
    <?php if ($page <= $nb_pages) : ?>
        <table class="table table-striped table-bordered personalTable">
            <thead>
                <tr>
                    <td class="actions">Actions</td>
                    <?php foreach (Compteur::ChampsCompteur($perimetre) as $nom_input => $props) : ?>
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
                                    <li><a class="dropdown-item" href="/imprimante/<?= $num_serie ?>"><i class="fa-solid fa-eye"></i> Voir l'imprimante</a></li>

                                    <?php if ((Compteur::isMine($num_serie, $date) || User::getRole() === 2) && $url === 'compteurs_perimetre') : ?>
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
    <?php endif ?>
</div>


<!-- Modal Trier/Rechercher -->
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
                    <select class="form-select col-sm-4" id="order" name="order">
                        <?php foreach ($lesChamps as $nom_input => $props) : ?>
                            <option value="<?= $nom_input ?>" <?php if ($order === $nom_input) : ?>selected<?php endif ?>><?= $props['libelle'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <select class="form-select col-sm-3" id="ordertype" name="ordertype">
                        <option value="ASC" <?php if ($ordertype === 'ASC') : ?>selected<?php endif ?>>Croissant</option>
                        <option value="DESC" <?php if ($ordertype === 'DESC') : ?>selected<?php endif ?>>Décroissant</option>
                    </select>
                </div>

                <hr>

                <!-- Les inputs de recherche -->
                <?php foreach ($lesChamps_Search_Modal as $nom_input => $props) : ?>
                    <div class="row mb-3">
                        <label for="<?= $nom_input ?>_id" class="col-sm-4"><?= $props['libelle'] ?></label>
                        <div class="col-sm-3">
                            <input type="text" id="<?= $nom_input ?>_id" name="<?= $nom_input ?>" class="form-control" value="<?= getValeurInput($nom_input) ?>">
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


<!-- Ajouter un relevé de compteur -->
<?php if ($url === 'compteurs_perimetre'): ?>
    <div class="modal fade" id="modal_add_counter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Ajouter un relevé de compteur</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row mb-3">
                        <label for="num_serie" class="col-sm-4 label">N° de Série</label>
                        <div class="col-sm-5">
                            <select class="select" name="num_serie" id="num_serie">
                                <?php foreach (Imprimante::getImprimantes([], true, false) as $numero) :
                                    $num = htmlentities($numero['num_serie']) ?>
                                    <option value="<?= $num ?>"><?= $num ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="date" class="col-sm-4 label">Date de relevé</label>
                        <div class="col-sm-5">
                            <input type="date" name="date" id="date" class="form-control" value="date">
                        </div>
                    </div>

                    <?php foreach ($modalVariables as $nom_input => $props) : ?>
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
<?php endif ?>