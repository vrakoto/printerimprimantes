<?php
session_start();

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
use App\Driver;
use App\Router;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'helper.php';

$VUES = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR;
define("COMPTEURS_FOLDER", "/compteurs/");
define("COMPTEURS_ARCHIVES_FOLDER", "/compteurs/archives/");
define("COPIEURS_FOLDER", "/copieurs/");
define("PANNES_FOLDER", "/pannes/");
define("USERS_FOLDER", "/utilisateurs/");

$router = new Router($VUES);

if (Driver::estConnecte()) {
    $router->request('/', USERS_FOLDER . 'compte.php', 'home');
    $router->request('/logs', USERS_FOLDER . 'changeLogs.php', 'logs');
    $router->request('/', USERS_FOLDER . 'compte.php', 'edit_account', 'POST'); // changer mdp
    
    $router->request('/imprimante/[*:num]', COPIEURS_FOLDER . 'ficheCopieur.php', 'machine_details');
    $router->request('/imprimante/[*:num]', COPIEURS_FOLDER . 'ficheCopieur.php', 'edit_machine_details', 'POST');
    
    $router->request('/liste_copieurs', COPIEURS_FOLDER .'listeCopieurs.php', 'list_machines');
    $router->request('/copieurs_perimetre', COPIEURS_FOLDER . 'copieursPerimetre.php', 'machines_area');
    $router->request('/copieurs_perimetre', COPIEURS_FOLDER . 'copieursPerimetre.php', 'add_machine_area', 'POST');
    $router->request('/machines_transfert', COPIEURS_FOLDER . 'copieursTransfert.php', 'machines_transfert');
    $router->request('/copieurs-sans-releve-trimestre', COPIEURS_FOLDER . 'copieurSansReleveTrimestre.php', 'list_machines_without_counter_3_months');
    $router->request('/copieurs-sans-responsable', COPIEURS_FOLDER . 'copieursSansResponsable.php', 'list_machines_without_owner');
    $router->request('/transfert-copieur', COPIEURS_FOLDER . 'transfertCopieur.php', 'view_transfert_machine');
    $router->request('/transfert-copieur', COPIEURS_FOLDER . 'transfertCopieur.php', 'transfert_machine', 'POST');
    
    $router->request('/liste_compteurs', COMPTEURS_FOLDER . 'listeCompteurs.php', 'list_counters');
    $router->request('/compteurs_perimetre', COMPTEURS_FOLDER . 'compteursPerimetre.php', 'counters_area');
    $router->request('/compteurs_perimetre', COMPTEURS_FOLDER . 'compteursPerimetre.php', 'add_counters', 'POST');
    // $router->request('/supprimer-releve/[*:num]/[i:year]-[i:month]-[i:day]', COMPTEURS_FOLDER . 'supprimerReleve.php', 'delete_counters');

    // les compteurs par trimestre
    $router->request('/compteurs-perimetre-T2-2023', COMPTEURS_ARCHIVES_FOLDER . 't2_2023.php', 'counters_area_t2_2023');

    $router->request('/liste_pannes', PANNES_FOLDER . 'listePannes.php', 'list_pannes');
    $router->request('/pannes_perimetre', PANNES_FOLDER . 'pannesPerimetre.php', 'pannes_area');
    $router->request('/ajout_panne', PANNES_FOLDER . 'ajoutPanne.php', 'add_panne');
    
    $router->request('/liste-responsables', USERS_FOLDER . 'listeResponsables.php', 'list_owners');
    $router->request('/responsables-perimetre', USERS_FOLDER . 'responsablesPerimetre.php', 'owners_area');
    $router->request('/responsables-perimetre', USERS_FOLDER . 'responsablesPerimetre.php', 'assign_machine_users', 'POST');
    $router->request('/gestion-utilisateurs', USERS_FOLDER . 'gestionUtilisateurs.php', 'view_users_area');
    $router->request('/gestion-utilisateurs', USERS_FOLDER . 'gestionUtilisateurs.php', 'create_users_area', 'POST');

    // $router->request('/importCompteurs', COMPTEURS_FOLDER . 'importerCompteurs.php', 'view_import_counters');
    // $router->request('/importCompteurs', COMPTEURS_FOLDER . 'importerCompteurs.php', 'import_counters', 'POST');
    $router->request('/faq', COMPTEURS_FOLDER . 'faq.php', 'faq');

    $router->request('/deconnexion', USERS_FOLDER . 'deconnexion.php', 'logout', 'POST');
} else {
    $router->request('/', 'connexion.php', 'home');
    $router->request('/', 'connexion.php', 'login_post', 'POST');
}

$router->run();