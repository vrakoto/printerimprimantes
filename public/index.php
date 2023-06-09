<?php
session_start();

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
use App\Driver;
use App\Router;
use App\User;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'helper.php';

$VUES = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR;
$AJAX = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'ajax' . DIRECTORY_SEPARATOR;
$router = new Router($VUES);
$ajaxRouter = new Router($AJAX, true);

if (Driver::estConnecte()) {
    $router->request('/', '/user/compte.php', 'home');
    $router->request('/', '/user/compte.php', 'edit_account', 'POST'); // changer mdp
    
    $router->request('/imprimante/[*:num]', '/user/imprimante.php', 'machine_details');
    $router->request('/imprimante/[*:num]', '/user/imprimante.php', 'edit_machine_details', 'POST');
    
    $router->request('/liste_copieurs', '/user/listeCopieurs.php', 'list_machines');
    $router->request('/copieurs_perimetre', '/user/copieursPerimetre.php', 'machines_area');
    $router->request('/copieurs_perimetre', '/user/copieursPerimetre.php', 'add_machine_area', 'POST');
    $router->request('/machines_transfert', '/user/copieursTransfert.php', 'machines_transfert');
    $router->request('/copieurs-sans-releve-trimestre', '/user/copieurSansReleveTrimestre.php', 'list_machines_without_counter_3_months');
    $router->request('/copieurs-sans-responsable', '/user/copieursSansResponsable.php', 'list_machines_without_owner');
    
    $router->request('/liste_compteurs', '/user/listeCompteurs.php', 'list_counters');
    $router->request('/compteurs_perimetre', '/user/compteursPerimetre.php', 'counters_area');
    $router->request('/compteurs_perimetre', '/user/compteursPerimetre.php', 'add_counters', 'POST');
    $router->request('/supprimer-releve/[*:num]/[i:year]-[i:month]-[i:day]', '/user/supprimerReleve.php', 'delete_counters');

    $router->request('/liste_pannes', '/user/listePannes.php', 'list_pannes');
    $router->request('/pannes_perimetre', '/user/pannesPerimetre.php', 'pannes_area');
    $router->request('/ajout_panne', '/user/ajoutPanne.php', 'add_panne');
    
    $router->request('/liste-responsables', '/user/listeResponsables.php', 'list_owners');
    $router->request('/responsables-perimetre', '/user/responsablesPerimetre.php', 'owners_area');
    $router->request('/gestion-utilisateurs', '/user/gestionUtilisateurs.php', 'view_users_area');
    $router->request('/historique-actions', '/user/historiqueActions.php', 'history_actions');

    $router->request('/importCompteurs', '/user/importerCompteurs.php', 'importCompteurs');
    $router->request('/importCompteurs', '/user/importerCompteurs.php', 'importerCompteurs', 'POST');


    if (User::getRole() !== 2) {
        $router->requestAjax('/ajouterCopieurPerimetre', 'ajouter_machine_perimetre', 'POST');
        $router->requestAjax('/retirerCopieurPerimetre', 'retirer_machine_perimetre', 'POST');
    }

    $router->requestAjax('/getCompteurs', 'get_compteurs');
    $router->requestAjax('/getCompteursPerimetre', 'get_compteurs_perimetre');

    $router->requestAjax('/getLesCompteursImprimante[*:num]', 'get_compteurs_imprimante');

    $router->requestAjax('/ajouterReleve', 'ajouter_compteur', 'POST');
    $router->requestAjax('/editReleve', 'modifier_compteur', 'POST');
    $router->requestAjax('/supprimerReleve', 'supprimer_compteur', 'POST');

    $router->requestAjax('/getImprimantes', 'get_imprimantes');
    $router->requestAjax('/getImprimantesPerimetre', 'get_imprimantes_perimetre');
    $router->requestAjax('/getImprimantesSansReleve3Mois', 'get_imprimantes_sans_releve_trimestre');

    $router->requestAjax('/getListeResponsables', 'get_responsables');
    $router->requestAjax('/getResponsablesPerimetre', 'get_responsables_perimetre');

    $router->requestAjax('/creerUtilisateur', 'creer_utilisateur', 'POST');
    $router->requestAjax('/getGestionUtilisateurs', 'get_utilisateurs_perimetre');

    // $router->request('/test', '/user/test.php', 'test');
    
    $router->request('/faq', '/user/faq.php', 'faq');
    $router->request('/deconnexion', '/user/deconnexion.php', 'logout', 'POST');


} else {
    $router->request('/', 'connexion.php', 'home');
    $router->request('/', 'connexion.php', 'login_post', 'POST');
}

$router->run();