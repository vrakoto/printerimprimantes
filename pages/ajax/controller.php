<?php

use App\AjaxController;
use App\Compteur;
use App\Coordsic;
use App\Corsic;
use App\User;

$id_profil = User::getMonID();
$role = User::getRole();
$bdd = User::getBDD();

switch ($match['name']) {
    case 'ajouter_machine_perimetre':
        $msg = '';
        if (!empty($_POST)) {
            $num_serie = htmlentities($_POST['num_serie']);

            if (empty($num_serie)) {
                $msg = "Veuillez saisir ou sélectionner un numéro de série";
            }

            if ($msg === '') {
                try {
                    Corsic::ajouterDansPerimetre($num_serie);
                } catch (Throwable $th) {
                    if ($th->getCode() === "23000") {
                        $msg = "Le copieur " . $num_serie . " figure déjà dans votre périmètre";
                    } else {
                        $msg = "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur du site";
                    }
                }
            }
        }
        die($msg);
    break;

    case 'retirer_machine_perimetre':
        $num_serie = htmlentities($_POST['num_serie']);
        $msg = '';
        try {
            User::retirerDansPerimetre($num_serie);
        } catch (\Throwable $th) {
            $msg = "Une erreur interne a été rencontrée. Veuillez contacter un administrateur";
        }
        die($msg);
    break;

    case 'get_compteurs':
        $ajax = new AjaxController(2);

        $query_total_records = "SELECT count(*) as allcount FROM compteurs";
        $query_total_filtered = "SELECT count(*) as allcount FROM compteurs WHERE `Numéro_série` LIKE :search_value";

        $query_results = "SELECT Numéro_série as num_serie,
        c.BDD as bdd,
        Date as date_releve,
        `101_Total_1` as total_101,
        `112_Total` as total_112,
        `113_Total` as total_113,
        `122_Total` as total_122,
        `123_Total` as total_123,
        `grade-prenom-nom` as modif_par,
        date_maj,
        type_relevé as type_releve
        FROM compteurs c
        LEFT JOIN profil p on p.id_profil = c.modif_par
        WHERE `Numéro_série` LIKE :search_value";

        if (isset($_GET['csv'], $_GET['search_value'])) {
            $search_value = htmlentities($_GET['search_value']);
            $csv = $ajax->test($query_results, $search_value, 'compteurs');
            die(json_encode($csv));
        } else {
            $total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);
            $total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);
            $results = $ajax->getRecords($query_results);
            die($ajax->output($total_records, $total_filtered, $results));
        }
    break;

    case 'get_compteurs_perimetre':
        $ajax = new AjaxController(2);

        if ($role === 2) { // Si COORDSIC
            $query_total_records = "SELECT COUNT(*) as allcount FROM compteurs c
                        WHERE c.BDD = '$bdd'";

            $query_total_filtered = "SELECT COUNT(*) as allcount FROM compteurs c
                        WHERE c.BDD = '$bdd'
                        AND c.`Numéro_série` LIKE :search_value";

            $query_results = "SELECT c.Numéro_série as num_serie,
                        c.BDD as bdd,
                        `Date` as date_releve,
                        `101_Total_1` as total_101,
                        `112_Total` as total_112,
                        `113_Total` as total_113,
                        `122_Total` as total_122,
                        `123_Total` as total_123,
                        `grade-prenom-nom` as modif_par,
                        date_maj,
                        type_relevé as type_releve
                        FROM compteurs c
                        LEFT JOIN profil p on p.id_profil = c.modif_par
                        WHERE c.BDD = '$bdd'
                        AND c.`Numéro_série` LIKE :search_value";
        } else {
            $query_total_records = "SELECT COUNT(*) as allcount FROM compteurs c
                        WHERE c.BDD = '$bdd'
                        AND c.`Numéro_série` IN (SELECT numéro_série FROM users_copieurs WHERE responsable = $id_profil)";

            $query_total_filtered = "SELECT COUNT(*) as allcount FROM compteurs c
                            WHERE c.BDD = '$bdd'
                            AND `Numéro_série` IN (SELECT numéro_série FROM users_copieurs WHERE responsable = $id_profil)
                            AND `Numéro_série` LIKE :search_value";

            $query_results = "SELECT c.Numéro_série as num_serie,
                        c.BDD as bdd,
                        `Date` as date_releve,
                        `101_Total_1` as total_101,
                        `112_Total` as total_112,
                        `113_Total` as total_113,
                        `122_Total` as total_122,
                        `123_Total` as total_123,
                        `grade-prenom-nom` as modif_par,
                        date_maj,
                        type_relevé as type_releve
                        FROM compteurs c
                        LEFT JOIN profil p on p.id_profil = c.modif_par
                        WHERE c.BDD = '$bdd'
                        AND c.`Numéro_série` IN (SELECT numéro_série FROM users_copieurs WHERE responsable = $id_profil)
                        AND c.`Numéro_série` LIKE :search_value";
        }


        if (isset($_GET['csv'], $_GET['search_value'])) {
            $search_value = htmlentities($_GET['search_value']);
            $csv = $ajax->test($query_results, $search_value, 'compteurs');
            die(json_encode($csv));
        } else {
            $total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);
            $total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);
            $results = $ajax->getRecords($query_results);
            die($ajax->output($total_records, $total_filtered, $results));
        }
    break;

    case 'get_compteurs_imprimante':
        $num_serie = htmlentities($params['num']);
        $num_serie = str_replace('/', '', $num_serie);

        $msg = '';
        try {
            $results = Compteur::searchCompteurByNumSerie($num_serie);
        } catch (\Throwable $th) {
            $msg = "Une erreur interne a été rencontrée. Veuillez contacter un administrateur";
        }
        $output = [
            "recordsTotal" => count($results),
            "recordsFiltered" => count($results),
            "data" => []
        ];

        foreach ($results as $row) {
            $output['data'][] = $row;
        }

        die(json_encode($output));
    break;

    case 'ajouter_compteur':
        $msg = '';
        if (!empty($_POST)) {
            $num_serie = htmlentities($_POST['num_serie']);
            $date_input_user = str_replace("/", "-", $_POST['date_releve']); // Ici au format universel
            $total_112 = (int)$_POST['total_112'];
            $total_122 = (int)$_POST['total_122'];
            $total_113 = (int)$_POST['total_113'];
            $total_123 = (int)$_POST['total_123'];
            $type_releve = htmlentities($_POST['type_releve']);
            $currentDay = time();
            
            if (empty($num_serie)) {
                $msg = "Veuillez saisir ou sélectionner un numéro de série";
            }

            if (!validateDate($date_input_user)) {
                $msg = "Le format de la date ou la date est invalide. <br>
                Veuillez saisir une date existante et utiliser le format JJ-MM-AAAA OU avec des slashs JJ/MM/AAAA";
            } else if (strtotime($date_input_user) > $currentDay) {
                $msg = "Veuillez saisir une date de relevé inférieur ou égal à la date actuelle";
            }

            if ($msg === '') {
                try {
                    $date_releve = toAmericanDate($date_input_user);
                    Coordsic::ajouterReleve($num_serie, $date_releve, $total_112, $total_113, $total_122, $total_123, $type_releve);
                } catch (Throwable $th) {
                    if ($th->getCode() === "23000") {
                        $msg = "L'imprimante : " . $num_serie . " possède déjà un relevé de compteur à la date : " . $date_input_user . "
                        <br>Veuillez modifier ou supprimer son compteur déjà existant à la date : " . $date_input_user;
                    } else {
                        $msg = "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur du site.";
                    }
                }
            }
        }
        die($msg);
    break;

    case 'modifier_compteur':
        $msg = '';
        if (!empty($_POST)) {
            $num_serie = htmlentities($_POST['num_serie']);
            $date_releve = htmlentities($_POST['date_releve']);
            $total_112 = (int)$_POST['total_112'];
            $total_122 = (int)$_POST['total_122'];
            $total_113 = (int)$_POST['total_113'];
            $total_123 = (int)$_POST['total_123'];
            $type_releve = htmlentities($_POST['type_releve']);
            
            if (empty($num_serie)) {
                $msg = "Veuillez saisir ou sélectionner un numéro de série";
            }

            if ($msg === '') {
                try {
                    Coordsic::editReleve($num_serie, $date_releve, $total_112, $total_113, $total_122, $total_123, $type_releve);
                } catch (Throwable $th) {
                    $msg = "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur du site.";
                }
            }
        }
        die($msg);
    break;

    case 'supprimer_compteur':
        $msg = '';
        if (!empty($_POST)) {
            $num_serie = htmlentities($_POST['num_serie']);
            $date_releve = htmlentities($_POST['date_releve']);
            
            if (empty($num_serie)) {
                $msg = "Veuillez sélectionner un numéro de série";
            }

            if ($msg === '') {
                try {
                    User::supprimerReleve($num_serie, $date_releve);
                } catch (Throwable $th) {
                    $msg = "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur du site.";
                    $msg = $th;
                }
            }
        }
        die($msg);
    break;

    case 'get_imprimantes':
        $ajax = new AjaxController(2);
        $query_total_records = "SELECT count(*) as allcount FROM copieurs";

        $query_total_filtered = "SELECT COUNT(*) as allcount FROM copieurs WHERE `N° de Série` LIKE :search_value";

        $query_results = "SELECT `N° ORDO` as num_ordo, 
                `N° de Série` as num_serie, 
                `Modele demandé` as modele, 
                `STATUT PROJET` as statut, 
                `BDD` as bdd, 
                `Site d'installation` as site_installation,
                `DATE CDE MINARM` as date_cde_minarm,
                `Config` as config,
                `N° Saisie ORACLE` as num_oracle,
                `N° OPP SFDC` as num_sfdc,
                `HostName` as hostname,
                `réseau` as reseau,
                `MAC@` as adresse_mac,
                `Entité Bénéficiaire` as entite_beneficiaire,
                `credo_unité` as credo_unite,
                `CP INSTA` as cp_insta,
                `DEP INSTA` as dep_insta,
                `adresse` as adresse,
                `localisation` as localisation,
                `ServiceUF` as service_uf,
                `Accessoires` as accessoires
                FROM copieurs
                WHERE `N° de Série` LIKE :search_value";

        if (isset($_GET['csv'])) {
            $search_value = htmlentities($_GET['search_value']);
            $csv = $ajax->test($query_results, $search_value, 'copieurs');
            die(json_encode($csv));
        } else {
            $total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);
            $total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);
            $results = $ajax->getRecords($query_results);
            die($ajax->output($total_records, $total_filtered, $results));
        }
        
    break;

    case 'get_imprimantes_perimetre':
        $ajax = new AjaxController(2);

        if ($role === 2) { // Si COORDSIC
            $query_total_records = "SELECT COUNT(*) as allcount FROM copieurs
                                    WHERE BDD = '$bdd'
                                    AND `STATUT PROJET` LIKE '1 - LIVRE'"; 

            $query_total_filtered = "SELECT COUNT(*) as allcount FROM copieurs c
                                    WHERE BDD = '$bdd'
                                    AND `STATUT PROJET` LIKE '1 - LIVRE'
                                    AND `N° de Série` LIKE :search_value";

            $query_results = "SELECT `N° ORDO` as num_ordo, 
                            `N° de Série` as num_serie, 
                            `Modele demandé` as modele, 
                            `STATUT PROJET` as statut, 
                            `BDD` as bdd, 
                            `Site d'installation` as site_installation,
                            `DATE CDE MINARM` as date_cde_minarm,
                            `Config` as config,
                            `N° Saisie ORACLE` as num_oracle,
                            `N° OPP SFDC` as num_sfdc,
                            `HostName` as hostname,
                            `réseau` as reseau,
                            `MAC@` as adresse_mac,
                            `Entité Bénéficiaire` as entite_beneficiaire,
                            `credo_unité` as credo_unite,
                            `CP INSTA` as cp_insta,
                            `DEP INSTA` as dep_insta,
                            `adresse` as adresse,
                            `localisation` as localisation,
                            `ServiceUF` as service_uf,
                            `Accessoires` as accessoires
                            FROM copieurs
                            WHERE BDD = '$bdd'
                            AND `STATUT PROJET` LIKE '1 - LIVRE'
                            AND `N° de Série` LIKE :search_value";
        } else {
            $query_total_records = "SELECT COUNT(*) as allcount FROM copieurs
                                    WHERE `N° de Série` IN
                                        (SELECT `numéro_série` FROM users_copieurs
                                        WHERE responsable = $id_profil)
                                    -- AND `STATUT PROJET` LIKE '1 - LIVRE'
                                    ";

            $query_total_filtered = "SELECT COUNT(*) as allcount
                                    FROM copieurs c
                                    WHERE `N° de Série` IN
                                        (SELECT `numéro_série` FROM users_copieurs
                                        WHERE responsable = $id_profil)
                                    AND `N° de Série` LIKE :search_value";
            
            
            $query_results = "SELECT `N° ORDO` as num_ordo, 
                            `N° de Série` as num_serie, 
                            `Modele demandé` as modele, 
                            `STATUT PROJET` as statut, 
                            `BDD` as bdd, 
                            `Site d'installation` as site_installation,
                            `DATE CDE MINARM` as date_cde_minarm,
                            `Config` as config,
                            `N° Saisie ORACLE` as num_oracle,
                            `N° OPP SFDC` as num_sfdc,
                            `HostName` as hostname,
                            `réseau` as reseau,
                            `MAC@` as adresse_mac,
                            `Entité Bénéficiaire` as entite_beneficiaire,
                            `credo_unité` as credo_unite,
                            `CP INSTA` as cp_insta,
                            `DEP INSTA` as dep_insta,
                            `adresse` as adresse,
                            `localisation` as localisation,
                            `ServiceUF` as service_uf,
                            `Accessoires` as accessoires
                            FROM copieurs
                            WHERE `N° de Série` IN
                                (SELECT `numéro_série` FROM users_copieurs
                                WHERE responsable = $id_profil)
                            AND `N° de Série` LIKE :search_value";
        }

        if (isset($_GET['csv'], $_GET['search_value'])) {
            $search_value = htmlentities($_GET['search_value']);
            $csv = $ajax->test($query_results, $search_value, 'copieurs');
            die(json_encode($csv));
        } else {
            $total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);
            $total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);
            $results = $ajax->getRecords($query_results);
            die($ajax->output($total_records, $total_filtered, $results));
        }
    break;

    
    case 'get_imprimantes_sans_releve_trimestre':
        $ajax = new AjaxController(2);

        $query_total_records = "SELECT COUNT(DISTINCT `N° de Série`) as allcount FROM copieurs c
                                WHERE BDD = '$bdd'
                                AND `STATUT PROJET` LIKE '1 - LIVRE'
                                AND `N° de Série` NOT IN
                                (
                                        SELECT Numéro_série FROM compteurs_trimestre ct
                                        WHERE BDD = '$bdd'
                                        GROUP BY ct.Numéro_série
                                )";
        
        $query_total_filtered = "SELECT COUNT(DISTINCT `N° de Série`) as allcount FROM copieurs c
                                WHERE c.BDD = '$bdd'
                                AND c.`STATUT PROJET` LIKE '1 - LIVRE'
                                AND c.`N° de Série` NOT IN
                                (
                                        SELECT Numéro_série FROM compteurs_trimestre ct
                                        WHERE ct.BDD = '$bdd'
                                        GROUP BY ct.Numéro_série
                                )
                                AND c.`N° de Série` LIKE :search_value";
        
        $query_results = "SELECT
                `N° ORDO` as num_ordo, 
                `N° de Série` as num_serie, 
                `Modele demandé` as modele, 
                `STATUT PROJET` as statut, 
                `BDD` as bdd, 
                `Site d'installation` as site_installation,
                `DATE CDE MINARM` as date_cde_minarm,
                `Config` as config,
                `N° Saisie ORACLE` as num_oracle,
                `N° OPP SFDC` as num_sfdc,
                `HostName` as hostname,
                `réseau` as reseau,
                `MAC@` as adresse_mac,
                `Entité Bénéficiaire` as entite_beneficiaire,
                `credo_unité` as credo_unite,
                `CP INSTA` as cp_insta,
                `DEP INSTA` as dep_insta,
                `adresse` as adresse,
                `localisation` as localisation,
                `ServiceUF` as service_uf,
                `Accessoires` as accessoires
                FROM copieurs c
                WHERE c.BDD = '$bdd'
                AND c.`STATUT PROJET` LIKE '1 - LIVRE'
                AND c.`N° de Série` NOT IN
                (
                        SELECT Numéro_série FROM compteurs_trimestre ct
                        WHERE ct.BDD = '$bdd'
                        GROUP BY ct.Numéro_série
                )
                AND c.`N° de Série` LIKE :search_value";
        
        if (isset($_GET['csv'], $_GET['search_value'])) {
            $search_value = htmlentities($_GET['search_value']);
            $csv = $ajax->test($query_results, $search_value, 'copieurs');
            die(json_encode($csv));
        } else {
            $total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);
            $total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);
            $results = $ajax->getRecords($query_results);
            die($ajax->output($total_records, $total_filtered, $results));
        }
    break;

    
    case 'get_responsables':
        $ajax = new AjaxController(3);

        $query_total_records = "SELECT count(*) as allcount FROM users_copieurs uc
                                JOIN profil p on uc.responsable = p.id_profil";
        $total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);
        
        $query_total_filtered = "SELECT count(*) as allcount FROM users_copieurs uc
                                JOIN profil p on uc.responsable = p.id_profil
                                WHERE (`grade-prenom-nom` LIKE :search_value) OR (`numéro_série` LIKE :search_value)";
        $total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);
        
        
        $query = "SELECT `grade-prenom-nom` as gpn, numéro_série as num_serie
                FROM users_copieurs uc
                JOIN profil p on uc.responsable = p.id_profil
                WHERE (`grade-prenom-nom` LIKE :search_value) OR (`numéro_série` LIKE :search_value)";
        $results = $ajax->getRecords($query);
        
        die($ajax->output($total_records, $total_filtered, $results));
    break;

    
    case 'get_responsables_perimetre':
        $ajax = new AjaxController(3);

        $query_total_records = "SELECT count(*) as allcount FROM users_copieurs uc
                                JOIN profil p on uc.responsable = p.id_profil WHERE p.BDD = '$bdd'";
        
        $query_total_filtered = "SELECT count(*) as allcount FROM users_copieurs uc
                                JOIN profil p on uc.responsable = p.id_profil
                                WHERE p.BDD = '$bdd'  AND (`grade-prenom-nom` LIKE :search_value OR `numéro_série` LIKE :search_value)";
        
        
        $query_results = "SELECT `grade-prenom-nom` as gpn, numéro_série as num_serie
                FROM users_copieurs uc
                JOIN profil p on uc.responsable = p.id_profil
                WHERE 1 AND p.BDD = '$bdd'
                AND (`grade-prenom-nom` LIKE :search_value OR `numéro_série` LIKE :search_value)";
        
        if (isset($_GET['csv'], $_GET['search_value'])) {
            $search_value = htmlentities($_GET['search_value']);
            $csv = $ajax->test($query_results, $search_value, 'users_copieurs');
            die(json_encode($csv));
        } else {
            $total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);
            $total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);
            $results = $ajax->getRecords($query_results);
            die($ajax->output($total_records, $total_filtered, $results));
        }
    break;


    case 'creer_utilisateur':
        $msg = '';
        if (!empty($_POST)) {
            $gpn = htmlentities($_POST['gpn']);
            $courriel = htmlentities($_POST['courriel']);
            $role = (int)$_POST['role'];
            $mdp = htmlentities($_POST['mdp']);
            $unite = htmlentities($_POST['unite']);
        
            if (empty($gpn)) {
                $msg = "Le grade prenom nom ne doit pas être vide";
            }
        
            if (empty($courriel)) {
                $msg = "Le courriel ne doit pas être vide";
            }
        
            if (empty($role)) {
                $msg = "Le role ne doit pas être vide";
            }
        
            if ($role <= 0 && $role > 3) {
                $msg = "Le role n'est pas correct";
            }
        
            if (empty($mdp)) {
                $msg = "Le mot de passe ne doit pas être vide";
            }
        
            if (empty($msg)) {
                try {
                    Coordsic::creerUtilisateur(User::getBDD(), $gpn, $courriel, $role, $mdp, $unite);
                } catch (\Throwable $th) {
                    if ($th->getCode() === "23000") {
                        $msg = "Un utilisateur possède déjà le courriel '" . $courriel . "'";
                    } else {
                        $msg = "Une erreur interne a été rencontrée. Veuillez contacter un administrateur";
                    }
                }
            }
        }
        
        die($msg);
    break;

    
    case 'get_utilisateurs_perimetre':
        $ajax = new AjaxController(3);

        $query_total_records = "SELECT COUNT(*) as allcount FROM profil WHERE BDD = '$bdd'";
        
        $query_total_filtered = "SELECT COUNT(*) as allcount FROM profil WHERE BDD = '$bdd' AND `grade-prenom-nom` LIKE :search_value";
        
        $query_results = "SELECT p.`grade-prenom-nom` as gpn, p.`BDD` as bdd, p.`Courriel` as courriel, ul.userlevelname as role, p.`Unité` as unite
                FROM profil p
                JOIN userlevels ul on ul.userlevelid = p.role
                WHERE BDD = '$bdd'
                AND `grade-prenom-nom` LIKE :search_value";
        
        if (isset($_GET['csv'], $_GET['search_value'])) {
            $search_value = htmlentities($_GET['search_value']);
            $csv = $ajax->test($query_results, $search_value, 'profil');
            die(json_encode($csv));
        } else {
            $total_records = $ajax->getNbRecordsWithoutFiltering($query_total_records);
            $total_filtered = $ajax->getNbRecordsFiltered($query_total_filtered);
            $results = $ajax->getRecords($query_results);
            die($ajax->output($total_records, $total_filtered, $results));
        }
    break;
}