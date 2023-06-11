<div class="mt-2" id="header">
    <h1><?= $title ?></h1>

    <button class="btn btn-success" id="downloadCSV" title="Télécharger les données en CSV"><i class="fa-solid fa-download"></i> Télécharger les données</button>
    <button class="mx-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-filter"></i> Recherche / Trier</button>
    <a class="mx-1 btn btn-secondary" href="/<?= $url ?>"><i class="fa-solid fa-arrow-rotate-left"></i> Réinitialiser la recherche</a>

    <?php if (str_contains($url, 'copieur')): ?>
        <a class="mx-3 btn btn-primary text-white" href="?page=<?= $page ?>&<?= $fullURL ?>&showColumns=<?= ($showColumns === 'few') ? 'all' : 'few' ?>"><?= $showColumns === 'few' ? 'Afficher toutes' : 'Reduire' ?> les informations</a>
    <?php endif ?>
</div>