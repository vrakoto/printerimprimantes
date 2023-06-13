<?php
use App\Imprimante;
use App\User;

$num = htmlentities($params['num']);

$imprimante = Imprimante::getImprimante($num);
$responsabilites = Imprimante::getSesResponsables($num);

$isEligible = (User::getRole() === 2 || User::getRole() === 4);

if (!empty($_POST)) {
    $post_variables = [];
    foreach (Imprimante::ChampsCopieur(false, 'all') as $nom_input => $props) {
        if (isset($_POST[$nom_input]) && trim($imprimante[$props['nom_db']]) !== trim($_POST[$nom_input])) {
            $post_variables[$nom_input] = ['nom_db' => $props['nom_db'], 'value' => htmlentities($_POST[$nom_input])];
        }
    }

    if (!empty($post_variables)) {
        try {
            Imprimante::modifierImprimante($num, $post_variables);
            header('Location:' . $url . '?s=true');
            exit();
        } catch (\Throwable $th) {
            newFormError('Un problème technique a été rencontré.');
        }
    } else {
        newFormError("Rien n'a été modifié.");
    }
}

function addInformationForm($allowEdit, $var, $titre, $value, $type = 'text'): string
{
    $allowInput = !$allowEdit ? "disabled" : "";
    $styleForbiddenInput = !$allowEdit ? "cursor: no-drop;" : "";
    $nameAllowedInput = $allowEdit ? "name=" . $var : "";
    return <<<HTML
    <div class="row mb-3">
        <label for="$var" class="col-sm-1 label">$titre :</label>
        <div class="col-sm-2">
            <input type="$type" id="$var" $nameAllowedInput class="form-control" value="$value" style="$styleForbiddenInput" $allowInput>
        </div>
    </div>
HTML;
}

?>

<div class="mt-5"></div>

<?php if (count($imprimante) > 0) : ?>
    <?php if (isset($_GET['s'])): ?>
        <div class="text-center alert alert-success">Les informations ont bien été modifiées.</div>
    <?php endif ?>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Information</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Responsabilité</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
            <?php if ($isEligible) : ?>
                <form action="" method="post" class="mt-3 p-3">
                <?php else : ?>
                    <div class="mt-5 p-3">
                    <?php endif ?>

                    <?php foreach (Imprimante::ChampsCopieur(false, 'all') as $nom_input => $props): ?>
                        <?php if ($nom_input === 'num_ordo' || $nom_input === 'num_serie' || $nom_input === 'bdd' || $nom_input === 'statut' || $nom_input === 'date_cde_minarm'): ?>
                            <?= addInformationForm(false, $nom_input, $props['libelle'], $imprimante[$props['nom_db']] ) ?>
                        <?php else: ?>
                            <?= addInformationForm(true, $nom_input, $props['libelle'], $imprimante[$props['nom_db']] ) ?>
                        <?php endif ?>
                    <?php endforeach ?>

                    <?php if ($isEligible) : ?>
                        <button type="submit" class="btn btn-success">Sauvegarder</button>
                </form>
            <?php else : ?>
        </div>
    <?php endif ?>
    </div>
    <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
        <div class="container mt-3">
            <?php if (count($responsabilites) > 0): ?>
            <table id="table_users_copieurs" class="table table-striped table-bordered personalTable table-responsive">
                <thead>
                    <tr>
                        <th id="num_serie">Responsable</th>
                        <th id="bdd">Numéro de Série</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($responsabilites as $responsable): ?>
                    <tr>
                        <td><?= htmlentities($responsable['responsable']) ?></td>
                        <td><?= htmlentities($responsable['num_serie']) ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?php else: ?>
                <h3 class="text-center">Aucun responsable</h3>
            <?php endif ?>
        </div>
    </div>

<?php else : ?>
    <div class="container alert alert-danger text-center">
        Cette machine n'existe pas
        <div class="mt-3"></div>
        <a class="btn btn-primary" href="<?= $router->url('list_machines') ?>">Retour</a>
    </div>
<?php endif ?>