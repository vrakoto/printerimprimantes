<?php

use App\Imprimante;
use App\User;

$isURLCopieurs = true;
$total = count($lesResultatsSansPagination);
$atLeastOneResult = (count($lesResultats)) > 0 ? true : false;
$nb_pages = ceil($total / $nb_results_par_page);

if (isset($_GET['download_csv'])) {
    Imprimante::downloadCSV(Imprimante::ChampsCopieur(false, 'all'), 'liste_machines', $lesResultatsSansPagination);
}

if (isset($_GET['switchColumns'])) {
    $_SESSION['showColumns'] = ($_SESSION['showColumns'] === 'few') ? 'all' : 'few';
    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>

<div class="p-4">
    <?php require_once 'header.php' ?>
    <?php require_once 'pagination.php' ?>
    
    <?php if ($atLeastOneResult) : ?>
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
                <?php foreach ($lesResultats as $data): ?>
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-list"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/imprimante/<?= $data['num_serie'] ?>"><i class="fa-solid fa-eye"></i> Voir l'imprimante</a></li>
                                    <li><a class="dropdown-item" href="/liste_compteurs?order=date_maj&num_serie=<?= htmlentities($data['num_serie']) ?>&ordertype=desc"><i class="fa-solid fa-book"></i> Relevés de compteurs</a></li>
                                    <?php if ($url === 'copieurs_perimetre' && $lessPrivilege): ?>
                                        <li>
                                            <form action="" method="post">
                                                <input type="hidden" name="remove_num_serie" value="<?= htmlentities($data['num_serie']) ?>">
                                                <button type="submit" class="dropdown-item"><i class="fa-solid fa-xmark"></i> Retirer ce copieur de mon périmètre</button>
                                            </form>
                                        </li>
                                    <?php endif ?>
                                    <li><a class="dropdown-item" href="/liste-responsables?num_serie=<?= htmlentities($data['num_serie']) ?>"><i class="fa-solid fa-user-doctor"></i> Voir responsable(s)</a></li>
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
                    <select class="form-select col-sm-4" id="order" name="order">
                        <?php foreach (Imprimante::ChampsCopieur($perimetre, $showColumns) as $nom_input => $props) : ?>
                            <option value="<?= $nom_input ?>" <?php if ($order === $nom_input) : ?>selected<?php endif ?>><?= $props['libelle'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <select class="form-select col-sm-4" id="ordertype" name="ordertype">
                        <option value="ASC" <?php if ($ordertype === 'ASC') : ?>selected<?php endif ?>>Croissant</option>
                        <option value="DESC" <?php if ($ordertype === 'DESC') : ?>selected<?php endif ?>>Décroissant</option>
                    </select>
                </div>
                
                <hr>

                <?php if ($url !== 'copieurs-sans-releve-trimestre'): ?>
                    <div class="row mb-3">
                        <label for="statut_projet_id" class="col-sm-4">Statut</label>
                        <div class="col-sm-4">
                            <select class="form-select" name="statut_projet" id="statut_projet_id">
                                <option value="%">0 - N'importe</option>
                                <?php foreach (Imprimante::getLesStatuts() as $s) : $s = htmlentities($s['statut']); ?>
                                    <option value="<?= $s ?>" <?php if (getValeurInput('statut_projet') === $s) : ?>selected<?php endif ?>><?= $s ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
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


<?php if ($url === 'copieurs_perimetre' && $lessPrivilege): ?>
    <div class="modal fade" id="modal_add_machine_area" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Ajouter un copieur dans mon périmètre</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="add_num_serie" class="col-auto">N° de série</label>
                        <div class="col-auto">
                            <input type="text" id="add_num_serie" name="add_num_serie" class="form-control" placeholder="N° de Série">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
<?php endif ?>