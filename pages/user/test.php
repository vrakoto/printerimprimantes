<?php
use App\User;
// Connexion à la base de données MySQL avec PDO
$host = 'localhost';
$dbname = 'sapollonv2';
$user = 'root';
$pass = '';
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);


$id_profil = User::getMonId();
// Requête SQL pour récupérer les compteurs d'une imprimante
$query = "SELECT c.`N° ORDO` as num_ordo, c.`N° de Série` as num_serie, c.`Modele demandé` as modele, c.`STATUT PROJET` as statut, c.`BDD` as bdd, c.`Site d'installation` as site_installation
        FROM copieurs c
        JOIN users_copieurs uc on `numéro_série` = `N° de Série`
        WHERE responsable = $id_profil";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Création du fichier CSV
$file = fopen('php://temp', 'w');
fputcsv($file, ["Numero ORDO", 'Numero de Serie', 'Modele', 'STATUT', 'BDD', "Site dinstallation"]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($file, $row);
}

// Positionnement du curseur de lecture au début du fichier CSV
rewind($file);

// Envoi des entêtes HTTP pour déclencher le téléchargement
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename=compteurs_imprimante.csv;');
header('Content-Length: ' . fstat($file)['size']);

// Envoi du contenu du fichier CSV à la sortie standard
fpassthru($file);

// Fermeture du fichier CSV
fclose($file);
die();


// $file = fopen('compteurs_imprimante.csv', 'w');
// fputcsv($file, array('ID', 'Nom de l\'imprimante', 'Compteur'));

// while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//     fputcsv($file, $row);
// }

// // Fermeture du fichier CSV
// fclose($file);

// // Téléchargement du fichier CSV
// header('Content-Type: application/csv');
// header('Content-Disposition: attachment; filename=compteurs_imprimante.csv;');
// header('Content-Length: ' . filesize('compteurs_imprimante.csv'));
// readfile('compteurs_imprimante.csv');
// unlink('compteurs_imprimante.csv');
// exit();
?>