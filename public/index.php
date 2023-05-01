<?php
session_start();

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
use App\Driver;
use App\Router;
use App\User;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'helper.php';

$VUES = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR;
$router = new Router($VUES);

if (Driver::estConnecte()) {
    $router->request('/', '/user/accueil.php', 'home');

    $router->request('/menuCopieur', '/user/menuCopieur.php', 'menu_machine');
    $router->request('/menuCompteurs', '/user/menuCompteurs.php', 'menu_machine_counters');
    $router->request('/menuResponsables', '/user/menuResponsables.php', 'menu_machines_owners');
    
    $router->request('/imprimante/[*:num]', '/user/imprimante.php', 'machine_details');
    $router->request('/imprimante/[*:num]', '/user/imprimante.php', 'edit_machine_details', 'POST');
    
    $router->request('/liste_copieurs', '/user/listeCopieurs.php', 'list_machines');
    
    $router->request('/liste_compteurs', '/user/listeCompteurs.php', 'list_counters');
    $router->request('/compteurs_perimetre', '/user/compteursPerimetre.php', 'counters_area');
    
    $router->request('/copieurs_perimetre', '/user/copieursPerimetre.php', 'machines_area');

    $router->request('/liste_responsables', '/user/listeResponsables.php', 'list_owners');
    $router->request('/responsablesPerimetre', '/user/responsablesPerimetre.php', 'owners_area');

    if (User::getRole() !== 2) {
        $router->request('/ajouterCopieurPerimetre', '/ajax/ajouterCopieurPerimetre.php', 'add_machine_area', 'POST');
    }

    // $router->request('/copieurSansResponsable', '/user/copieurSansResponsable.php', 'list_machines_without_owner');
    $router->request('/copieurSansReleve3Mois', '/user/copieurSansReleve3Mois.php', 'list_machines_without_counter_3_months');

    $router->request('/retirerCopieurPerimetre', '/ajax/retirerCopieurPerimetre.php', 'ajax_remove_machine_area', 'POST');
    
    $router->request('/inscrireCopieur', '/user/inscrireCopieur.php', 'view_add_machine');
    $router->request('/inscrireCopieur', '/user/inscrireCopieur.php', 'add_machine', 'POST');

    $router->request('/getCompteurs', '/ajax/getCompteurs.php', 'ajax_get_compteurs');
    $router->request('/getCompteursPerimetre', '/ajax/getCompteursPerimetre.php', 'ajax_get_compteurs_perimetre');

    $router->request('/getLesCompteursImprimante[*:num]', '/ajax/getLesCompteursImprimante.php', 'ajax_get_compteurs_imprimante');

    $router->request('/ajouterReleve', '/ajax/ajouterReleve.php', 'add_counter', 'POST');
    $router->request('/editReleve', '/ajax/editReleve.php', 'edit_counter', 'POST');
    $router->request('/supprimerReleve', '/ajax/supprimerReleve.php', 'remove_counter', 'POST');

    $router->request('/getImprimantes', '/ajax/getImprimantes.php', 'ajax_get_imprimantes');
    $router->request('/getImprimantesPerimetre', '/ajax/getImprimantesPerimetre.php', 'ajax_get_imprimantes_perimetre');
    $router->request('/getImprimantesSansResponsable', '/ajax/getImprimantesSansResponsable.php', 'ajax_get_imprimantes_sans_responsables'); // perimetre
    $router->request('/getImprimantesSansReleve3Mois', '/ajax/getImprimantesSansReleve3Mois.php', 'ajax_get_imprimantes_sans_releve_3_mois'); // perimetre

    $router->request('/getListeResponsables', '/ajax/getListeResponsables.php', 'ajax_get_users_copieurs');
    $router->request('/getResponsablesPerimetre', '/ajax/getResponsablesPerimetre.php', 'ajax_get_responsables_perimetre');

    $router->request('/gestion_utilisateurs', '/user/gestionUtilisateurs.php', 'view_users_area');
    $router->request('/creerUtilisateur', '/ajax/creerUtilisateur.php', 'ajax_create_user', 'POST');
    $router->request('/getGestionUtilisateurs', '/ajax/getGestionUtilisateurs.php', 'ajax_get_users_area');
    
    $router->request('/theme', '/user/theme.php', 'theme');
    $router->request('/compte', '/user/compte.php', 'my_account');
    $router->request('/compte', '/user/compte.php', 'edit_my_account', 'POST');
    $router->request('/deconnexion', '/user/deconnexion.php', 'logout', 'POST');

    $router->request('/toCSV[*:num]', '/toCSV.php', 'csv', 'GET');
} else {
    $router->request('/', 'connexion.php', 'home');
    $router->request('/', 'connexion.php', 'login_post', 'POST');
}

$router->run();