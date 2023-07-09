<?php

use App\Coordsic;
use App\Imprimante;
use App\User;
use App\UsersCopieurs;

$total = count($lesResultatsSansPagination);
$nb_pages = ceil($total / $nb_results_par_page);

if (isset($_GET['download_csv'])) {
    UsersCopieurs::downloadCSV(UsersCopieurs::ChampUsersCopieurs(), 'liste_responsables', $lesResultatsSansPagination);
}

$erreur = false;
if (isset($_POST['id_profil'], $_POST['num_serie'])) {
    $id_profil = (int)$_POST['id_profil'];
    $num_serie = htmlentities(strtoupper($_POST['num_serie']));

    if ($id_profil === 0 || empty($num_serie)) {
        $erreur = "Veuillez remplir tous les champs";
    } else if (empty(Imprimante::getImprimante($num_serie))) {
        $erreur = "Le numéro de série : $num_serie est inexistant";
    }

    if (!$erreur) {
        try {
            Coordsic::ajouterDansPerimetre($num_serie, $id_profil);
            header('Location:' . $url . '?s=1');
            exit();
        } catch (PDOException $th) {
            $msg = "Erreur interne";
            if ($th->getCode() === "23000") {
                $msg = "Cet utilisateur est déjà responsable de ce copieur.";
            }
            newFormError($msg);
        }
    } else {
        newFormError($erreur);
    }
}


if (isset($_POST['remove_machine_area_users'])) {
    $id_profil = (int)$_POST['id_profil'];
    $num_serie_to_remove = htmlentities($_POST['num_serie']);

    try {
        User::retirerDansPerimetre($num_serie_to_remove, $id_profil);
        header('Location:' . $url);
        exit();
    } catch (PDOException $th) {
        newFormError("Une erreur interne a été rencontrée.");
    }
}
?>

<div class="p-4">

    <?php if (isset($_GET['s'])) : ?>
        <div class="alert alert-success text-center">
            Le copieur a bien été affecté à l'utilisateur.
        </div>
    <?php endif ?>

    <?php require_once 'header.php' ?>
    <?php require_once 'pagination.php' ?>

    <?php if ($page <= $nb_pages) : ?>
        <table class="table table-striped table-bordered personalTable">
            <thead>
                <tr>
                    <th>Actions</th>
                    <?php foreach (UsersCopieurs::ChampUsersCopieurs() as $nom_input => $props) : ?>
                        <?php if ($nom_input !== 'id_profil'): ?>
                        <th id="<?= $nom_input ?>"><?= $props['libelle'] ?></th>
                        <?php endif ?>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lesResultats as $resultat) :
                    $id_profil = (int)$resultat['id_profil'];
                    $gpn = htmlentities($resultat['gpn']);
                    $num_serie = htmlentities($resultat['num_serie']);
                ?>
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-list"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (User::getRole() === 2 && $url === 'responsables-perimetre') : ?>
                                        <li>
                                            <form action="" method="post">
                                                <input type="hidden" name="remove_machine_area_users">
                                                <input type="hidden" name="id_profil" value="<?= $id_profil ?>">
                                                <input type="hidden" name="num_serie" value="<?= $num_serie ?>">
                                                <button type="submit" class="dropdown-item" onclick="return confirm('Voulez-vous supprimer la responsabilité de ce copieur pour cet utilisateur ?');"><i class="fa-solid fa-trash"></i> Supprimer la responsabilitée</button>
                                            </form>
                                        </li>
                                    <?php endif ?>
                                </ul>
                            </div>
                        </td>
                        <td><?= $gpn ?></td>
                        <td><a href="/imprimante/<?= $num_serie ?>"><?= $num_serie ?></a></td>
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
                    <select class="form-select col-sm-4" id="order" name="order">
                        <?php foreach (UsersCopieurs::ChampUsersCopieurs() as $key => $s) : ?>
                            <option value="<?= $key ?>" <?php if ($order === $key) : ?>selected<?php endif ?>><?= $s['libelle'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <select class="form-select col-sm-4" id="ordertype" name="ordertype">
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

<?php if ($highPrivilege) : ?>
    <div class="modal fade" id="modal_assign_machine" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Affecter un copieur à un utilisateur</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row mb-3">
                        <label for="select_id" class="col-sm-6">Sélectionnez l'utilisateur</label>
                        <select class="select col-sm-5" name="id_profil" id="select_id" placeholder="Sélectionnez un utilisateur...">
                            <?php foreach (Coordsic::getUsers() as $props) : ?>
                                <option value="<?= (int)$props['id_profil'] ?>"><?= htmlentities($props['gpn']) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="row">
                        <label for="num_serie_id" class="col-sm-6">Saisir le n° de série</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="num_serie" id="num_serie_id">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Affecter</button>
                </div>
            </form>
        </div>
    </div>
<?php endif ?>