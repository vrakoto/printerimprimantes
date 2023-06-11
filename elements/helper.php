<?php

use App\Imprimante;

function addLink(string $title, string $var, string $icon, $router, $match, array $sublinks = [])
{
    $matchName = $match['name'];
    $submenu = '';
    if (!empty($sublinks)) {
        foreach ($sublinks as $linkvar => $link) {
            $submenu .= '
            <li>
                <a href="' . $router->url($linkvar) . '" class="submenu' . ($matchName === $linkvar ? ' linkActive' : '') . '">
                    <i class="' . $link['icon'] . '"></i>
                    <span class="mx-2">' . $link['title'] . '</span>
                </a>
            </li>';
        }
    }
    return '<li>
                <a href="' . $router->url($var) . '" class="link' . ($matchName === $var ? ' linkActive' : '') . '">
                    <i class="' . $icon . '"></i>
                    <span class="mx-2">' . $title . '</span>
                </a>
                <ul class="container_submenu">' . $submenu . '</ul>
            </li>';
}

function validateDate($date, $format = 'd-m-Y'): bool
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function convertDate(string $date, bool $heure = FALSE): string
{
    if ($heure === TRUE) {
        $heure = " à H:i";
    }
    $date = new DateTime($date);
    return $date->format('d/m/Y' . $heure);
}

function toAmericanDate(string $date): string
{
    $date = new DateTime($date);
    return $date->format('Y-m-d');
}


function colonnes($html) {
    // Extraction des valeurs des attributs "id"
    $col_ids = [];
    preg_match_all('/id="([^"]+)"/', $html, $matches);
    if (isset($matches[1])) {
        $col_ids = $matches[1];
    }

    // Extraction des valeurs des colonnes
    $col_values = [];
    $html_no_tags = strip_tags($html);
    $col_values = explode("\n", $html_no_tags);
    $col_values = array_map('trim', $col_values);
    $col_values = array_filter($col_values);
    return array_combine($col_ids, $col_values);
}

function checkboxColumns() {
    $i = 0;
    $html = <<<HTML
    <div id="lesCheckbox">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="all" id="all">
        <label class="form-check-label" for="all">Tout Cocher</label>
    </div>
HTML;
    foreach (colonnes(Imprimante::ChampsCopieur()) as $id => $values) {
        $i++;
        $id_protected = htmlentities($id);
        $checked = ($i <= 6) ? "checked" : "";
        $html .= <<<HTML
            <div class="form-check mx-3">
                <input class="form-check-input leChamp" type="checkbox" name="{$id_protected}" id="{$id_protected}" {$checked}>
                <label class="form-check-label" for="{$id_protected}">{$values}</label>
            </div>
HTML;
    }
    $html .= <<<HTML
    </div>
HTML;
    return $html;
}


function debug($render)
{
    echo '<pre>';
    print_r($render);
    echo '</pre>';
}

function getValeurInput($variable_input, string|int $defaultValue = ''): string|int
{
    return isset($_GET[$variable_input]) ? htmlentities($_GET[$variable_input]) : $defaultValue;
}

function newException($msg = "Page introuvable"): void
{
    $msg = $msg;
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR. '404.php';
}

function newFormError($msg = "Le formulaire est invalide"): void
{
    $msg = $msg;
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR. 'erreurForm.php';
}