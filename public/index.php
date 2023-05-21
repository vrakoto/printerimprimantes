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
    $router->request('/', '/user/accueil.php', 'home');

    $router->request('/menuCopieur', '/user/menuCopieur.php', 'menu_machine');
    $router->request('/menuCompteurs', '/user/menuCompteurs.php', 'menu_machine_counters');
    $router->request('/menuPannes', '/user/menuPannes.php', 'menu_pannes');
    $router->request('/menuAdministration', '/user/menuAdministration.php', 'menu_administration');
    
    $router->request('/imprimante/[*:num]', '/user/imprimante.php', 'machine_details');
    $router->request('/imprimante/[*:num]', '/user/imprimante.php', 'edit_machine_details', 'POST');
    
    $router->request('/liste_copieurs', '/user/listeCopieurs.php', 'list_machines');
    $router->request('/copieurs_perimetre', '/user/copieursPerimetre.php', 'machines_area');
    $router->request('/machines_transfert', '/user/copieursTransfert.php', 'machines_transfert');
    $router->request('/copieurSansReleve3Mois', '/user/copieurSansReleve3Mois.php', 'list_machines_without_counter_3_months');
    
    $router->request('/liste_compteurs', '/user/listeCompteurs.php', 'list_counters');
    $router->request('/compteurs_perimetre', '/user/compteursPerimetre.php', 'counters_area');

    $router->request('/liste_pannes', '/user/listePannes.php', 'list_pannes');
    $router->request('/pannes_perimetre', '/user/pannesPerimetre.php', 'pannes_area');
    $router->request('/ajout_panne', '/user/ajoutPanne.php', 'add_panne');
    
    $router->request('/liste_responsables', '/user/listeResponsables.php', 'list_owners');
    $router->request('/responsablesPerimetre', '/user/responsablesPerimetre.php', 'owners_area');
    $router->request('/gestion_utilisateurs', '/user/gestionUtilisateurs.php', 'view_users_area');

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

    $router->request('/test', '/user/test.php', 'test');
    
    $router->request('/faq', '/user/faq.php', 'faq');
    $router->request('/compte', '/user/compte.php', 'my_account');
    $router->request('/compte', '/user/compte.php', 'edit_account', 'POST'); // changer mdp
    $router->request('/deconnexion', '/user/deconnexion.php', 'logout', 'POST');


} else {
    $router->request('/', 'connexion.php', 'home');
    $router->request('/', 'connexion.php', 'login_post', 'POST');
}

$router->run();