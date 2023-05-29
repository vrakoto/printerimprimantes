<?php
// Test pour implémenter un ajax manuellement sans utiliser DataTable

use App\Panne;

$num_serie = isset($_GET['num_serie']) ? htmlentities($_GET['num_serie']) : '';
$num_ticket = isset($_GET['num_ticket']) ? htmlentities($_GET['num_ticket']) : '';
$params = ['num_série' => $num_serie . '%', 'id_event' => $num_ticket . '%'];

$page = (int)$_GET['page'];
$nbResultsPage = (int)$_GET['nbResultsPage'];
$debut = ($page - 1) * $nbResultsPage;

$lesPannes = Panne::getLesPannes($params, false, [$debut, $nbResultsPage]);

$html = '';
foreach ($lesPannes as $panne) {
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

    $html .= "
    <tr>
        <td>$id_event</td>
        <td><a href='imprimante/$num_serie'>$num_serie</a></td>
        <td>$contexte</td>
        <td>$type_panne</td>
        <td>$statut_intervention</td>
        <td>$commentaires</td>
        <td>$date_evolution</td>
        <td>$heure_evolution</td>
        <td>$modif_par</td>
        <td>$modif_date</td>
        <td>$fichier</td>
        <td>$ouverture</td>
        <td>$fermeture</td>
    </tr>";
}

die($html);