<?php
use App\User;
$title = "Importer des compteurs par CSV";

$lesErreursCSV = [];
$lesCopieursAInserer = [];
$datas = [];
$erreurInterne = '';

function validateDate($date, $format = 'd-m-Y'): bool
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
function toAmericanDate(string $date): string
{
    $date = new DateTime($date);
    return $date->format('Y-m-d');
}

if (isset($_FILES['csv_file'])) {
    if ($_FILES['csv_file']['error'] == UPLOAD_ERR_OK && $_FILES['csv_file']['type'] == 'text/csv') {
        $csvData = file_get_contents($_FILES['csv_file']['tmp_name']);
        $rows = explode("\n", $csvData);

        foreach ($rows as $row) {
            if (empty($row)) continue;

            $line = trim($row);

            // Vérifie si le fichier est séparé par des virgules ou points-virgules
            $separated = ';';
            if (strpos($line, ',') !== false) {
                $separated = ',';
            }
            $t = explode($separated, $line);
            //

            // Retire tous les quotes et guillements
            $t = str_replace(['"', "'"], '', $t);

            if (count($t) < 7) continue;

            $num_serie = htmlentities($t[0]);
            $date = str_replace("/", "-", htmlentities($t[1]));
            $total_112 = (int)$t[2];
            $total_113 = (int)$t[3];
            $total_122 = (int)$t[4];
            $total_123 = (int)$t[5];
            $type_releve = htmlentities($t[6]);

            if (strpos($num_serie, ' ') !== false) {
                $lesErreursCSV[] = "Le numéro de série <b>'$num_serie'</b> ne doit pas contenir d'espace";
            }
            if (!validateDate($date)) {
                $lesErreursCSV[] = "La date est incorrecte pour le <b>'$num_serie'</b>, veuillez respecter ce format : jj-mm-aaaa (séparé par des tirets ou slashs)";
            }
            if (strcasecmp($type_releve, 'IWMC') !== 0 && strcasecmp($type_releve, 'MANUEL') !== 0 && strcasecmp($type_releve, 'MANUIA') !== 0) {
                $lesErreursCSV[] = "Le type de relevé est incorrect pour '$num_serie'";
            }

            if (empty($lesErreursCSV)) {
                $date = toAmericanDate($date);
                array_push($datas, compact('num_serie', 'date', 'total_112', 'total_113', 'total_122', 'total_123', 'type_releve'));
            }
        }

        if (empty($lesErreursCSV)) {
            foreach ($datas as $data) {
                try {
                    User::ajouterReleve(...array_values($data));
                    $lesCopieursAInserer[] = ['type' => 'success', 'msg' => "Relevé de compteur ajouté pour la machine <b>" . $data['num_serie'] . "</b> !"];
                } catch (PDOException $th) {
                    if ($th->getCode() === "23000") {
                        $lesCopieursAInserer[] = ['type' => 'danger', 'msg' => "La machine <b>" . $data['num_serie'] . "</b> dispose déjà d'un relevé de compteur à la date " . convertDate($data['date']) . ". Veuillez mettre à jour (modifier) ou supprimer le compteur existant, si besoin."];
                    } else {
                        $erreurInterne = "Une erreur interne a été rencontrée. Veuillez contacter l'administrateur";
                    }
                }
            }
        }
    } else {
        $erreurInterne = "Veuillez sélectionner un fichier CSV.";
    }
}
?>

<style>
    ul li {
        margin-top: 10px;
    }
</style>

<div class="container mt-5">
    <?php if ($erreurInterne !== '') : ?>
        <?= newFormError($erreurInterne) ?>
    <?php endif ?>

    <?php if (!empty($lesErreursCSV)) : ?>
        <div class="alert alert-danger">
            <h3>Le fichier CSV fourni est <u><b>invalide</b></u> pour les raisons suivantes :</h3>
            <ul class="mt-3">
                <?php foreach ($lesErreursCSV as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <?php if (!empty($lesCopieursAInserer)) : ?>
        <?php foreach ($lesCopieursAInserer as $erreur) :
            $type = $erreur['type'];
        ?>
            <p class="text-<?= $type ?>"><?= $type === 'success' ? "<i class='fa-solid fa-check'></i>" : "<i class='fa-solid fa-xmark'></i>" ?> <?= $erreur['msg'] ?></p>
        <?php endforeach ?>

        <hr>
    <?php endif ?>

    <div class="text-center">
        <h3>Importer des relevés de compteurs via un fichier CSV</h3>
        <i class="fs-6">Veuillez lire le guide juste en dessous.</i>
    </div>

    <form action="importCompteurs" class="mt-5 mb-3" method="POST" enctype="multipart/form-data">
        <a class="btn btn-primary" onclick="document.getElementById('csv_file').click()">Insérer un fichier CSV</a>
        <input type="file" id="csv_file" class="form-control d-none" name="csv_file" accept=".csv">
        <p><i id="file_name"></i></p>

        <div class="mb-3"></div>

        <a href="<?= $router->url('counters_area') ?>" class="btn btn-secondary mt-3">Retour</a>
        <button class="btn btn-success mt-3" type="submit">Ajouter</button>
    </form>

    <hr>

    <div class="accordion mt-3" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    Comment fonctionne l'importation des compteurs ?
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    Cette page vous offre la possibilité d'insérer facilement et rapidement un grand nombre de relevés de compteurs à partir d'un fichier Excel converti en CSV.

                    <div class="mt-3"></div>

                    Il vous suffit simplement de saisir les informations suivantes dans votre fichier Excel, dans l'ordre suivant :
                    <ul class="mt-3 mb-5">
                        <li>Dans la colonne A : Le numéro de série de la machine</li>
                        <li>Dans la colonne B : Sa date de relevé au format JJ-MM-AAAA ou JJ/MM/AAAA</li>
                        <li>Dans la colonne C : Son 112 Total (mettez 0 si aucun)</li>
                        <li>Dans la colonne D : Son 113 Total (mettez 0 si aucun)</li>
                        <li>Dans la colonne E : Son 122 Total (mettez 0 si aucun)</li>
                        <li>Dans la colonne F : Son 123 Total (mettez 0 si aucun)</li>
                        <li>Dans la colonne G : Le type de relevé (MANUEL ou IWMC)</li>
                    </ul>

                    <p>Voici un exemple de ce à quoi devrait ressembler votre fichier Excel :</p>

                    <img class="img-fluid" src="/src/img/exemple_csv.JPG" alt="Image Excel CSV">

                    <p><b>Assurez-vous de retirer les en-têtes.</b></p>
                    <p>Puis, <b>exportez en CSV</b> (Cliquez sur l'onglet "Fichier" en haut à gauche puis "Exporter" et sélectionnez le format CSV).</p>
                </div>
            </div>
        </div>
    </div>
</div>