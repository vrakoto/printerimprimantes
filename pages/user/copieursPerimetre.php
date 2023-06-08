<?php

use App\Corsic;
use App\Imprimante;
use App\User;

$title = "Copieurs du périmètre";
$jsfile = 'listeCopieurs';
$url = 'copieurs_perimetre';
$perimetre = true;
$laTable = Imprimante::testChamps($perimetre);

if (isset($_POST['add_num_serie'])) {
    $num_serie_to_add = htmlentities($_POST['add_num_serie']);

    try {
        User::ajouterDansPerimetre($num_serie_to_add);
        header('Location:' . $url);
        exit();
    } catch (PDOException $th) {
        die("Une erreur interne a été rencontrée.");
    }
}

if (isset($_POST['remove_num_serie'])) {
    $num_serie_to_remove = htmlentities($_POST['remove_num_serie']);

    try {
        User::retirerDansPerimetre($num_serie_to_remove);
        header('Location:' . $url);
        exit();
    } catch (PDOException $th) {
        die("Une erreur interne a été rencontrée.");
    }
}

$order = getValeurInput('order', 'num_serie');
$ordertype = getValeurInput('ordertype', 'ASC');

foreach ($laTable as $key => $value) {
    $laTable[$key] = array_merge($value, [
        'value' => getValeurInput($value['nom_input']),
        'valuePosition' => getValeurInput($value['nom_input']) . '%'
    ]);
}
$laTable['order'] = ['nom_db' => $order, 'value' => $ordertype];

// l'utilisateur a fait une recherche
$laTable_query = [];
foreach ($laTable as $nom_input => $props) {
    if ($nom_input === 'order') {
        $laTable_query['order'] = $props['nom_db'];
        $laTable_query['ordertype'] = $ordertype;
    } else if (!empty($props['value'])) {
        $laTable_query[$nom_input] = $props['value'];
    }
}
$fullURL = http_build_query($laTable_query);


$nb_results_par_page = 10;
$page = 1;
if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
}
if ($page <= 0) {
    header('Location:/' . $url);
    exit();
}

$debut = ($page - 1) * $nb_results_par_page;

try {
    $lesResultats = Imprimante::copieursPerimetre($params, [$debut, $nb_results_par_page]);
    $lesResultatsSansPagination = Imprimante::copieursPerimetre($params);
    require_once 'templates' . DIRECTORY_SEPARATOR . 'copieurs.php';
} catch (\Throwable $th) {
    newException($th->getMessage());
}

?>

<div class="modal fade" id="modal_add_machine_area" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="post">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Ajouter un copieur dans mon périmètre</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row mb-3">
                    <label for="add_num_serie" class="col-auto">Sélectionnez un N° de Série</label>
                    <select class="selectize w-100" name="add_num_serie" id="add_num_serie">
                        <?php foreach (Corsic::copieursPerimetrePasDansMaListe() as $numero) : $num = htmlentities($numero['num_serie']) ?>
                            <option value="<?= $num ?>"><?= $num ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="modal_remove_machine_area" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="post">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Retirer un copieur de mon périmètre</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row mb-3">
                    <label for="remove_num_serie" class="col-auto">Sélectionnez un N° de Série</label>
                    <select class="selectize w-100" name="remove_num_serie" id="remove_num_serie">
                        <?php foreach (Corsic::copieursPerimetre() as $numero) : $num = htmlentities($numero['num_serie']) ?>
                            <option value="<?= $num ?>"><?= $num ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
    </div>
</div>