<?php
session_start();

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
use App\Driver;
use App\Router;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'helper.php';

$VUES = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR;
$router = new Router($VUES);

if (Driver::estConnecte()) {
    $router->request('/', '/user/accueil.php', 'home');
    $router->request('/menuCopieur', '/user/menuCopieur.php', 'menu_machine');
    $router->request('/menuCompteurs', '/user/menuCompteurs.php', 'menu_machine_counters');
    
    $router->request('/imprimante/[*:num]', '/user/imprimante.php', 'machine_details');
    
    $router->request('/liste_imprimantes', '/user/listeImprimantes.php', 'list_machines');
    $router->request('/ajouterReleve', '/user/ajouterReleve.php', 'view_add_counter');
    $router->request('/ajouterReleve', '/user/ajouterReleve.php', 'add_counter', 'POST');
    
    $router->request('/liste_compteurs', '/user/listeCompteurs.php', 'list_counters');
    $router->request('/compteurs_perimetre', '/user/compteursPerimetre.php', 'counters_area');
    
    
    $router->request('/copieurs_perimetre', '/user/copieursPerimetre.php', 'machines_area');
    $router->request('/ajouterCopieurPerimetre', '/user/ajouterCopieurPerimetre.php', 'view_add_machine_area');
    $router->request('/ajouterCopieurPerimetre', '/user/ajouterCopieurPerimetre.php', 'add_machine_area', 'POST');

    $router->request('/copieurSansResponsable', '/user/copieurSansResponsable.php', 'list_machines_without_owner');
    $router->request('/copieurSansReleve3Mois', '/user/copieurSansReleve3Mois.php', 'list_machines_without_counter_3_months');

    $router->request('/retirerCopieurPerimetre', '/user/retirerCopieurPerimetre.php', 'view_remove_machine_area');
    $router->request('/retirerCopieurPerimetre', '/user/retirerCopieurPerimetre.php', 'remove_machine_area', 'POST');
    
    $router->request('/inscrireCopieur', '/user/inscrireCopieur.php', 'view_add_machine');
    $router->request('/inscrireCopieur', '/user/inscrireCopieur.php', 'add_machine', 'POST');
    
    $router->request('/theme', '/user/theme.php', 'theme');
    $router->request('/compte', '/user/compte.php', 'my_account');
    $router->request('/compte', '/user/compte.php', 'edit_my_account', 'POST');
    $router->request('/deconnexion', '/user/deconnexion.php', 'logout', 'POST');


    $router->request('/test', '/user/test.php', 'test', 'POST');
} else {
    $router->request('/', 'connexion.php', 'home');
    $router->request('/', 'connexion.php', 'login_post', 'POST');
}

$router->run();