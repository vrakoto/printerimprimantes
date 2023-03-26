<?php

use App\Compteur;
use App\Users\User;
?>

<div class="container">
    <h1 class="mt-5">Compteurs du périmètre</h1>
    <br>

    <?php if (count(User::copieursPerimetre()) > 0) : ?>
        <a href="<?= $router->url('add_counter') ?>" class="mb-4 btn btn-primary">Ajouter un relevé</a>
    <?php else : ?>
        <div class="mb-3">
            <h5>Vous n'avez aucun copieur dans votre périmètre.</h5>
            <a href="<?= $router->url('add_machine_area') ?>" class="mb-4 btn btn-primary">Ajouter un copieur dans mon périmètre</a>
        </div>
    <?php endif ?>

    <hr>

    <div class="row g-3 align-items-center">
        <div class="col-auto">
            <label for="customSearch" class="col-form-label">Rechercher un compteur</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="customSearch" name="num_serie" placeholder="Insérer son numéro de série">
        </div>
    </div>

    <table id="copieurs_new_added" class="table table-striped personalTable">
        <?= Compteur::ChampsCompteur() ?>
        <tbody>
            <?php foreach (User::getLesRelevesMonPerimetre() as $releve) : ?>
                <?= Compteur::ValeursCompteur($releve) ?>
            <?php endforeach ?>
        </tbody>
    </table>
</div>