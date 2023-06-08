<div class="d-flex justify-content-between align-items-center mt-5 mb-3">
    <div id="pagination">
        <a class="<?= $page != 1 ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=1&<?= $fullURL ?>">1</a>
        <a <?php if ($page <= 1) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page - 1 ?>&<?= $fullURL ?>"><i class="fa-solid fa-arrow-left"></i></a>

        <button class="btn btn-secondary" style="cursor: unset;"><?= $page ?></button>

        <a <?php if ($page >= $nb_pages) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page + 1 ?>&<?= $fullURL ?>"><i class="fa-solid fa-arrow-right"></i></a>
        <a class="<?= $page != $nb_pages ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=<?= $nb_pages ?>&<?= $fullURL ?>"><?= $nb_pages ?></a>

        <?php if ($url === 'compteurs_perimetre'): ?>
            <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_counter"><i class="fa-solid fa-plus"></i> Ajouter un compteur</button>
        <?php endif ?>
    </div>

    <h3>Total : <?= $total ?></h3>
</div>