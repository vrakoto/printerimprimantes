<?php
session_start();

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
use App\Driver;
use App\Router;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'helper.php';

$VUES = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR;
$router = new Router($VUES);

if (Driver::estConnecte()) {
    $router->request('/', '/user/compte.php', 'home');
    $router->request('/logs', '/user/changeLogs.php', 'logs');
    $router->request('/', '/user/compte.php', 'edit_account', 'POST'); // changer mdp
    
    $router->request('/imprimante/[*:num]', '/user/imprimante.php', 'machine_details');
    $router->request('/imprimante/[*:num]', '/user/imprimante.php', 'edit_machine_details', 'POST');
    
    $router->request('/liste_copieurs', '/user/listeCopieurs.php', 'list_machines');
    $router->request('/copieurs_perimetre', '/user/copieursPerimetre.php', 'machines_area');
    $router->request('/copieurs_perimetre', '/user/copieursPerimetre.php', 'add_machine_area', 'POST');
    $router->request('/machines_transfert', '/user/copieursTransfert.php', 'machines_transfert');
    $router->request('/copieurs-sans-releve-trimestre', '/user/copieurSansReleveTrimestre.php', 'list_machines_without_counter_3_months');
    $router->request('/copieurs-sans-responsable', '/user/copieursSansResponsable.php', 'list_machines_without_owner');
    $router->request('/transfert-copieur', '/user/transfertCopieur.php', 'view_transfert_machine');
    $router->request('/transfert-copieur', '/user/transfertCopieur.php', 'transfert_machine', 'POST');
    
    $router->request('/liste_compteurs', '/user/listeCompteurs.php', 'list_counters');
    $router->request('/compteurs_perimetre', '/user/compteursPerimetre.php', 'counters_area');
    $router->request('/compteurs_perimetre', '/user/compteursPerimetre.php', 'add_counters', 'POST');
    $router->request('/supprimer-releve/[*:num]/[i:year]-[i:month]-[i:day]', '/user/supprimerReleve.php', 'delete_counters');

    $router->request('/liste_pannes', '/user/listePannes.php', 'list_pannes');
    $router->request('/pannes_perimetre', '/user/pannesPerimetre.php', 'pannes_area');
    $router->request('/ajout_panne', '/user/ajoutPanne.php', 'add_panne');
    
    $router->request('/liste-responsables', '/user/listeResponsables.php', 'list_owners');
    $router->request('/responsables-perimetre', '/user/responsablesPerimetre.php', 'owners_area');
    $router->request('/responsables-perimetre', '/user/responsablesPerimetre.php', 'assign_machine_users', 'POST');
    $router->request('/gestion-utilisateurs', '/user/gestionUtilisateurs.php', 'view_users_area');
    $router->request('/gestion-utilisateurs', '/user/gestionUtilisateurs.php', 'create_users_area', 'POST');
    $router->request('/gestion-utilisateur', '/user/createEditUser.php', 'view_create_edit_user');

    $router->request('/importCompteurs', '/user/importerCompteurs.php', 'view_import_counters');
    $router->request('/importCompteurs', '/user/importerCompteurs.php', 'import_counters', 'POST');

    // $router->request('/test', '/user/test.php', 'test');
    
    $router->request('/faq', '/user/faq.php', 'faq');
    $router->request('/deconnexion', '/user/deconnexion.php', 'logout', 'POST');


} else {
    $router->request('/', 'connexion.php', 'home');
    $router->request('/', 'connexion.php', 'login_post', 'POST');
}

$router->run();