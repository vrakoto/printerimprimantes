<?php

use App\Imprimante;
use App\User;

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


function menu(string $urlRouter, array $icons, string $title, bool $requiredRole = false): string
{
    $lesIcones = '';
    foreach ($icons as $icon) {
        $lesIcones .= "<i class='$icon'></i>";
    }
    return <<<HTML
    <a href="$urlRouter" class="home_action text-center">
        $lesIcones
        <h3>$title</h3>
    </a>
HTML;
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
    <div class="border" id="lesCheckbox">
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