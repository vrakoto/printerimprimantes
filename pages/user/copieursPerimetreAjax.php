<?php

use App\Corsic;
use App\Imprimante;
use App\User;

$role = User::getRole();

$lesNumeros = Corsic::copieursPerimetrePasDansMaListe();
$jsfile = 'copieursPerimetre';
$title = "Copieurs Périmètre";
?>

<div class="d-flex justify-content-between container mt-5" id="header">
    <div>
        <h1>Copieurs de mon périmètre</h1>
        <div id="message"></div>

        <form method="post" id="form_add_machine_area" class="d-none">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Sélectionnez N° Série</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <button type="submit" title="Valider la saisie" class="btn btn-primary"><i class="fa-solid fa-check"></i></button>
                        </td>
                        <td><a title="Annuler la saisie" class="btn btn-danger" id="cancel_input"><i class="fa-solid fa-xmark"></i></a></td>
                        <td>
                            <select class="selectize w-100" name="num_serie" id="num_serie" required>
                            <?php foreach ($lesNumeros as $numero) : $num = htmlentities($numero['num_serie']) ?>
                                <option value="<?= $num ?>"><?= $num ?></option>
                            <?php endforeach ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>

        <div class="mt-5 mb-0">
            <?php if (User::getRole() !== 2) : ?>
                <button class="mb-1 btn btn-primary" id="btn_add_machines_area" title="Ajouter un copieur dans mon périmètre">Ajouter un copieur dans mon périmètre</button>
            <?php endif ?>

            <span id="export-csv"></span>
        </div>

        <hr class="mt-5 mb-0">

        <form class="mt-2 row g-3 align-items-center mb-2" id="form_search">
            <div class="col-auto">
                <label for="table_search" class="col-form-label">Rechercher un copieur</label>
            </div>
            <div class="col-auto">
                <input type="text" name="num_serie" class="form-control" id="table_search" placeholder="Insérer son numéro de série">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary" title="Rechercher le copieur saisie"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </form>

        <div class="row g-3 align-items-center mt-1">
            <div class="col-auto">
                <label for="table_select_nb_elements_par_pages">Nombre de résultats par page:</label>
            </div>
            <div class="col-auto">
                <select class="form-select" id="table_select_nb_elements_par_pages">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>
    <?= checkboxColumns() ?>
</div>


<div id="large_table" class="container mt-5">
    <table id="table_imprimantes" class="table table-striped table-bordered personalTable table-responsive table_imprimantes_perimetre" data-table="getImprimantesPerimetre">
        <?= Imprimante::ChampsCopieur() ?>
        <tbody></tbody>
    </table>
</div>

<script defer>
    let allow_delete_machine_area = {}
    <?php if ($role !== 2): ?>
        allow_delete_machine_area = {
            name: "Retirer ce copieur de mon périmètre",
            callback: function (key, options) {
                const row = tableImprimante.row(options.$trigger)
                const { num_serie } = row.data()
                $.ajax({
                    type: "post",
                    url: "/retirerCopieurPerimetre",
                    data: "num_serie=" + num_serie,
                    success: function (e) {
                        $('#message').empty();
                        tableImprimante.ajax.reload();
                        if (e.length <= 0) {
                            $(selector).trigger('contextmenu:hide')
                            $('#message').attr("class", "alert alert-success");
                            $('#message').append(`Le copieur ${num_serie} a bien été retiré de votre périmètre.`)
                        } else {
                            $('#message').attr("class", "alert alert-danger");
                            $('#message').append(e)
                        }
                    },
                    error: function () {
                        $('#message').attr("class", "alert alert-danger");
                        $('#message').append("Impossible de trouver la requete");
                    }
                });
            }
        }
    <?php endif ?>
</script>