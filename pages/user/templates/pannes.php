<?php

use App\Panne;

$total = count($lesPannesSansPagination);
$searching = !empty($_GET); // l'utilisateur a fait une recherche
$nb_pages = ceil($total / $nb_results_par_page);

if (isset($_GET['csv']) && $_GET['csv'] === "yes") {
    Panne::downloadCSV('N° de Ticket;N° de Série;Contexte de la panne;Nature de la panne;Statut de l\'intervention;Commentaires;Date de changement de situation;Heure de changement de situation; Déclaré/Modifié par;Ticket modifié le;Fichier(s) complémentaire(s);Ouverture du ticket;Fermeture du ticket', 'liste_pannes', $lesPannesSansPagination);
}

/* $pagination = new Pagination(5);
$pagination->setRedirection('/liste_pannes');
$pagination->setVariablesSearch(['num_serie' => 'fin', 'num_ticket' => 'fin']);
$pagination->setParams(['num_série' => $pagination->getLaValeurVariable('num_serie'), 'id_event' => $pagination->getLaValeurVariable('num_ticket')]);
$pagination->setLesResults(Panne::getLesPannes($pagination->getParams(), false, [$pagination->getDebut(), $pagination->getNbResultsPerPage()]), Panne::getlesPannes($pagination->getParams(), false));
$total = $pagination->getTotal();
$lesPannes = $pagination->getAllResults();
echo '<pre>';
print_r($pagination->getParams());
echo '</pre>'; */


function formSearch($var, $titre, $placeholder, $value): string
{
    return <<<HTML
    <div class="row mb-3">
        <label for="$var" class="col-sm-3 label">$titre :</label>
        <div class="col-sm-2">
            <input type="text" id="$var" name="$var" class="form-control" value="$value" placeholder="$placeholder">
        </div>
    </div>
HTML;
}
?>

<style>
    thead th:hover {
        background-color: orange;
        cursor: pointer;
    }

    #pagination a {
        color: black;
        padding: 8px 16px;
        transition: background-color .3s;
        border: 1px solid #ddd;
    }

    #pagination a:hover {
        background-color: #ddd;
    }
</style>

<div class="container mt-2" id="header">
    <h1><?= $title ?></h1>

    <form class="mt-5 mb-2">
        <?= formSearch('num_ticket', 'Rechercher par N° de Ticket', 'Saisir un N° de Série', $num_ticket) ?>
        <?= formSearch('num_serie', 'Rechercher par N° de Série', 'Saisir un N° de Ticket', $num_serie) ?>
        <button type="submit" id="btnSearch" class="btn btn-primary" title="Effectuer la recherche">Rechercher <i class="fa-solid fa-magnifying-glass"></i></button>
    </form>

    <button class="btn btn-primary" id="downloadCSV">Exporter en CSV</button>

    <div id="pagination" class="mt-4">
        <?php for ($i = 1; $i < $nb_pages + 1; $i++) : ?>
            <a class="<?= $page !== $i ? 'btn' : 'btn btn-primary text-white' ?>" href="?page=<?= $i ?><?= $searching ? '&num_serie=' . $num_serie . '&num_ticket=' . $num_ticket : '' ?>"><?= $i ?></a>
            <!-- <a onclick="loadPage(<?= $i ?>, <?= $nb_results_par_page ?>)" class="<?= $page !== $i ? 'btn' : 'btn btn-primary text-white' ?>"><?= $i ?></a> -->
        <?php endfor ?>
    </div>

</div>

<?php if ($page <= $nb_pages) : ?>
    <h3 class="mt-5">Nombre total de pannes : <?= $total ?></h3>
    <table class="table table-striped table-bordered personalTable">
        <?= Panne::ChampPannes() ?>
        <tbody>
            <?php foreach ($lesPannes as $panne) :
                $id_event = htmlentities($panne['id_event']);
                $num_serie = htmlentities($panne['num_série']);
                $contexte = htmlentities($panne['contexte']);
                $type_panne = htmlentities($panne['type_panne']);
                $statut_intervention = htmlentities($panne['statut_intervention']);
                $commentaires = htmlentities($panne['commentaires']);
                $date_evolution = htmlentities($panne['commentaires']);
                $heure_evolution = htmlentities($panne['commentaires']);
                $modif_par = htmlentities($panne['maj_par']);
                $modif_date = htmlentities($panne['maj_date']);
                $fichier = htmlentities($panne['fichier']);
                $ouverture = convertDate(htmlentities($panne['ouverture']));
                $fermeture = (htmlentities($panne['fermeture']));
            ?>
                <tr>
                    <td><?= $id_event ?></td>
                    <td><a href="imprimante/<?= $num_serie ?>"><?= $num_serie ?></a></td>
                    <td><?= $contexte ?></td>
                    <td><?= $type_panne ?></td>
                    <td><?= $statut_intervention ?></td>
                    <td><?= $commentaires ?></td>
                    <td><?= $date_evolution ?></td>
                    <td><?= $heure_evolution ?></td>
                    <td><?= $modif_par ?></td>
                    <td><?= $modif_date ?></td>
                    <td><?= $fichier ?></td>
                    <td><?= $ouverture ?></td>
                    <td><?= $fermeture ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php else : ?>
    <h3>Aucun résultat trouvé</h3>
<?php endif ?>