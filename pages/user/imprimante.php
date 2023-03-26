<?php
use App\Compteur;
use App\Imprimante;

$imprimante = Imprimante::getImprimante($params['num']);
$total_101 = 0;
$total_112 = 0;
$total_113 = 0;
$total_123 = 0;
?>

<?php if (count($imprimante) > 0) : ?>
    <?php foreach (Compteur::searchCompteurByNumSerie($params['num']) as $test) {
        $total_101 += $test['101_total'];
        $total_112 += $test['112_total'];
        $total_113 += $test['113_total'];
        $total_123 += $test['123_total'];
    } ?>
    <section>
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-lg-6 mb-4 mb-lg-0">
                    <h1>Détail de l'imprimante '<?= $imprimante[Imprimante::getChamps('champ_num_serie')] ?>'</h1>
                    <div class="card mt-4 mb-3" style="border-radius: .5rem;">
                        <div class="row g-0">
                            <div class="col-md-4 gradient-custom text-center" style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                <img src="/src/icon/print.png" alt="Avatar" class="img-fluid my-5" style="width: 80px;" />
                                <h5>Numéro : <?= htmlentities($imprimante[Imprimante::getChamps('champ_num_serie')]) ?></h5>
                                <p>BDD : <?= htmlentities($imprimante[Imprimante::getChamps('champ_bdd')]) ?></p>
                                <i class="far fa-edit mb-5"></i>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body p-4">
                                    <h6>Information</h6>
                                    <hr class="mt-0 mb-4">
                                    <div class="row pt-1">
                                        <div class="col-6 mb-3">
                                            <h6>Modèle</h6>
                                            <p class="text-muted"><?= htmlentities($imprimante[Imprimante::getChamps('champ_modele')]) ?></p>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <h6>Statut</h6>
                                            <p class="text-muted"><?= htmlentities($imprimante[Imprimante::getChamps('champ_statut')]) ?></p>
                                        </div>
                                    </div>
                                    <h6>Projects</h6>
                                    <hr class="mt-0 mb-4">
                                    <div class="row pt-1">
                                        <div class="col-6 mb-3">
                                            <h6>Site d'installation</h6>
                                            <p class="text-muted"><?= htmlentities($imprimante[Imprimante::getChamps('champ_site_installation')]) ?></p>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <h6>Date d'ajout</h6>
                                            <p class="text-muted"><?= htmlentities($imprimante[Imprimante::getChamps('champ_date_ajout')]) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <canvas id="myChart"></canvas>
            </div>

            <hr>

            <h3>101 Total</h3>

            <div>
                <canvas id="total_101"></canvas>
            </div>
        </div>
    </section>

    <script>
        const relevesImprimantes = [<?= $total_101 ?>, <?= $total_112 ?>, <?= $total_113 ?>, <?= $total_123 ?>];

        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['101 Total', '112 Total', '113 Total', '123 Total'],
                datasets: [{
                    label: 'Relevés total',
                    data: relevesImprimantes,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        });
    </script>

<?php else : ?>
    <div class="container alert alert-danger text-center">
        Cette imprimante n'existe pas
        <div class="mt-3"></div>
        <a class="btn btn-primary" href="<?= $router->url('list_machines') ?>">Retour</a>
    </div>
<?php endif ?>