<?php
use App\User;
$highPrivilege = (User::getRole() === 2 || User::getRole() === 4); // COORDSIC AND ADMIN
$lessPrivilege = (User::getRole() !== 2 && User::getRole() !== 4); // NO COORDSIC AND ADMIN
?>
<div class="mt-5 mb-3">
    <div id="pagination">
        <!-- Revenir page 1 -->
        <a class="<?= $page != 1 ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=1&<?= $fullURL ?>">1</a>

        <!-- Reculer d'une page -->
        <a <?php if ($page <= 1) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page - 1 . $fullURL ?>"><i class="fa-solid fa-arrow-left"></i></a>

        <!-- N° Page courante -->
        <button class="btn btn-secondary" style="cursor: unset;"><?= $page ?></button>

        <!-- Avancer d'une page -->
        <a <?php if ($page >= $nb_pages) : ?>style="pointer-events: none;" <?php endif ?> class="btn" href="?page=<?= $page + 1 . $fullURL ?>"><i class="fa-solid fa-arrow-right"></i></a>

        <!-- Nombre total de pages (FIN) -->
        <a class="<?= $page != $nb_pages ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=<?= $nb_pages . '&' . $fullURL ?>"><?= $nb_pages ?></a>

        <span class="h4 mx-4 mt-3"><?= number_format($total, 0, ',', ' '); ?> résultat<?= ($total > 1) ? 's' : '' ?></span>

        <?php if ($url === 'transfert-copieur' && $highPrivilege): ?>
            <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_transfert_machine"><i class="fa-solid fa-plus"></i> Transférer un copieur</button>
        <?php endif ?>

        <?php if ($url === 'copieurs_perimetre' && $lessPrivilege): ?>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_add_machine_area">Ajouter un copieur dans mon périmètre</button>
        <?php endif ?>

        <?php if ($url === 'compteurs_perimetre'): ?>
            <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_counter"><i class="fa-solid fa-plus"></i> Ajouter un compteur</button>
        <?php endif ?>

        <?php if ($url === 'gestion-utilisateurs' && $highPrivilege): ?>
            <button class="mx-3 btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_create_user"><i class="fa-solid fa-plus"></i> Créer un utilisateur</button>
        <?php endif ?>

        <?php if ($url === 'responsables-perimetre' && $highPrivilege): ?>
            <button class="mx-3 btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_assign_machine"><i class="fa-solid fa-plus"></i> Affecter un copieur à un utilisateur</button>
        <?php endif ?>
    </div>
</div>