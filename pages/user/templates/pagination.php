<div class="d-flex align-items-center mt-5 mb-3">
    <div id="pagination">
        <!-- Revenir page 1 -->
        <a class="<?= $page != 1 ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=1&<?= $fullURL ?>">1</a>

        <!-- Reculer d'une page -->
        <a <?php if ($page <= 1) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page - 1 ?>&<?= $fullURL ?>"><i class="fa-solid fa-arrow-left"></i></a>

        <!-- N° Page courante -->
        <button class="btn btn-secondary" style="cursor: unset;"><?= $page ?></button>

        <!-- Avancer d'une page -->
        <a <?php if ($page >= $nb_pages) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page + 1 ?>&<?= $fullURL ?>"><i class="fa-solid fa-arrow-right"></i></a>

        <!-- Nombre total de pages -->
        <a class="<?= $page != $nb_pages ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=<?= $nb_pages ?>&<?= $fullURL ?>"><?= $nb_pages ?></a>

        <?php if ($url === 'compteurs_perimetre'): ?>
            <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_counter"><i class="fa-solid fa-plus"></i> Ajouter un compteur</button>
            <!-- <a id="btn_exception" class="btn btn-secondary text-white" href="<?= $router->url('view_import_counters') ?>"><i class="fa-solid fa-plus"></i><i class="fa-solid fa-file mx-2"></i> Ajouter massivement des compteurs</a> -->
        <?php endif ?>

        <?php if ($url === 'transfert-copieur'): ?>
            <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_transfert_machine"><i class="fa-solid fa-plus"></i> Transférer un copieur</button>
        <?php endif ?>
    </div>

    <h4 class="mx-4"><?= $total ?> Résultat<?= ($total > 1) ? 's' : '' ?></h4>
</div>