<?php

use App\Imprimante;
use App\User;
use App\UsersCopieurs;

$total = count($lesResultatsSansPagination);
$nb_pages = ceil($total / $nb_results_par_page);

if (isset($_GET['csv']) && $_GET['csv'] === "yes") {
    UsersCopieurs::downloadCSV(UsersCopieurs::ChampUsersCopieurs(), 'liste_responsables', $lesResultatsSansPagination);
}
?>

<div class="p-4">
    <?php require_once 'header.php' ?>

    <?php if ($page <= $nb_pages) : ?>
        <?php require_once 'pagination.php' ?>

        <table class="table table-striped table-bordered personalTable">
            <thead>
                <tr>
                    <?php foreach (UsersCopieurs::ChampUsersCopieurs() as $nom_input => $props) : ?>
                        <th id="<?= $nom_input ?>"><?= $props['libelle'] ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
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
                <h1 class="modal-title fs-5" id="exampleModalLabel">Effectuer une recherche et/ou un tri</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row mb-3">
                    <label for="order" class="col-sm-4">Trier par</label>
                    <select class="selectize col-sm-4" id="order" name="order">
                        <?php foreach (UsersCopieurs::ChampUsersCopieurs() as $key => $s) : ?>
                            <option value="<?= $key ?>" <?php if ($order === $key) : ?>selected<?php endif ?>><?= $s['libelle'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <select class="selectize col-sm-4" id="ordertype" name="ordertype">
                        <option value="ASC" <?php if ($ordertype === 'ASC') : ?>selected<?php endif ?>>Croissant</option>
                        <option value="DESC" <?php if ($ordertype === 'DESC') : ?>selected<?php endif ?>>Décroissant</option>
                    </select>
                </div>

                <?php foreach (UsersCopieurs::ChampUsersCopieurs() as $nom_input => $props) : ?>
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

<?php if (User::getRole() === 2 || User::getRole() === 4): ?>
    <div class="modal fade" id="modal_assign_machine" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Assigner un copieur à un futur responsable</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row mb-3">
                        <label for="add_num_serie" class="col-sm-4">Sélectionnez l'utilisateur</label>
                        <select name="num_serie" class="selectize col-sm-4" placeholder="Sélectionnez un utilisateur...">
                            <?php foreach (User::ChampsGestionUtilisateurs() as $nom_input => $props): ?>
                                <option value="<?= $num ?>"><?= $num ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="row">
                        <label for="select_num_serie" class="col-sm-4">Sélectionnez un N° de Série</label>
                        <select name="bdd" class="selectize col-sm-4" placeholder="Sélectionnez un N° de Série...">
                            <?php foreach (Imprimante::getImprimantes([], true, false) as $lesNumeros) : $leNumero = htmlentities($lesNumeros['num_serie']) ?>
                                <option value="<?= $leNumero ?>"><?= $leNumero ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Voulez-vous transférer ce copieur ?');">Transférer</button>
                </div>
            </form>
        </div>
    </div>
<?php endif ?>