<?php

use App\Compteur;
use App\User;

$lesChamps = Compteur::ChampsCompteur(false);
$num_serie = htmlentities($_GET['num_serie']);
$perimetre = htmlentities($_GET['perimetre']);
$imprimante = Compteur::getImprimanteCompteurs($num_serie);
?>

<?php if ($perimetre === 'true'): ?>
    <div class="msg text-center"></div>
    <button class="btn btn-primary mb-3" onclick="toggle_form_add_counter_from_copieurs_page(this)">Ajouter un compteur</button>
<?php endif ?>

<table class="table table-striped table-bordered personalTable" data-num_serie="<?= $num_serie ?>">
    <thead>
        <tr>
            <?php foreach ($lesChamps as $nom_input => $props) : ?>
                <th id="<?= $nom_input ?>"><?= $props['libelle'] ?></th>
            <?php endforeach ?>
        </tr>
    </thead>
    <tbody>
        <tr class="d-none hiddenForm">
            <td><?= $num_serie ?></td>
            <td><?= User::getBDD() ?></td>
            <td><?= date('d/m/Y') ?></td>
            <td>Auto</td>
            <td><input class="form-control total_112" type="number" min="0"></td>
            <td><input class="form-control total_113" type="number" min="0"></td>
            <td><input class="form-control total_122" type="number" min="0"></td>
            <td><input class="form-control total_123" type="number" min="0"></td>
            <td>Moi</td>
            <td>Auto</td>
            <td>MANUEL</td>
            <td>
                <button class="btn btn-success" onclick="add_counter_from_copieurs_page(this)">Ajouter</button>
            </td>
        </tr>
        <?php foreach ($imprimante as $data) : ?>
            <tr>
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

<?php
exit();
?>