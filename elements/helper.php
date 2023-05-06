<?php

use App\Imprimante;
use App\User;

function nav_link(string $form_fieldsien, string $icon, string $titre): string
{
    $active = '';
    $currentLinkController = str_replace('p=', '', $_SERVER['QUERY_STRING']);
    if (str_contains($currentLinkController, $form_fieldsien)) {
        $active = " linkHover";
    }
    return <<<HTML
    <a href="$form_fieldsien" class="link $active"><i class="$icon"></i> <span class="mx-2">$titre</span></a>
HTML;
}

function convertDate(string $date, bool $heure = FALSE): string
{
    if ($heure === TRUE) {
        $heure = " à H:i";
    }
    $date = new DateTime($date);
    return $date->format('d/m/Y' . $heure);
}

function link_machinesInMyArea($urlRouter): string
{
    return <<<HTML
    <a href="$urlRouter" class="home_action text-center">
        <i class="fa-solid fa-print mx-2"></i>
        <i class="fa-solid fa-location-dot"></i>
        <h3>Copieurs de mon périmètre</h3>
    </a>
HTML;
}

function link_list_machines($urlRouter): string
{
    return <<<HTML
    <a href="$urlRouter" class="home_action text-center">
        <i class="fa-solid fa-print mx-2"></i>
        <i class="fa-solid fa-list"></i>
        <h3>Liste des copieurs</h3>
    </a>
HTML;
}

function link_add_machine($urlRouter): string
{
    if (User::getRole() === 2 || User::getRole() === 4) {
        return <<<HTML
        <a href="$urlRouter" class="home_action text-center">
            <i class="fa-solid fa-print mx-2"></i>
            <i class="fa-solid fa-pen"></i>
            <h3>Inscrire une machine inexistante</h3>
        </a>
HTML;
    }
    return '';
}

function link_counters_area($urlRouter): string
{
    return <<<HTML
    <a href="$urlRouter" class="home_action text-center">
        <i class="fa-solid fa-book mx-2"></i>
        <i class="fa-solid fa-location-dot"></i>
        <h3>Compteurs de mon périmètre</h3>
    </a>
HTML;
}

function link_list_counters($urlRouter): string
{
    return <<<HTML
    <a href="$urlRouter" class="home_action text-center">
        <i class="fa-solid fa-book mx-2"></i>
        <i class="fa-solid fa-list"></i>
        <h3>Liste des compteurs</h3>
    </a>
HTML;
}

function link_ownersInMyArea($urlRouter): string
{
    return <<<HTML
    <a href="$urlRouter" class="home_action text-center">
        <i class="fa-solid fa-users mx-2"></i>
        <i class="fa-solid fa-location-dot"></i>
        <h3>Responsables de mon périmètre</h3>
    </a>
HTML;
}

function link_list_owners($urlRouter): string
{
    return <<<HTML
    <a href="$urlRouter" class="home_action text-center">
        <i class="fa-solid fa-list mx-2"></i>
        <i class="fa-solid fa-users"></i>
        <h3>Liste des responsables</h3>
    </a>
HTML;
}

function link_machines_without_owner($urlRouter): string
{
    return <<<HTML
    <a href="$urlRouter" class="home_action text-center">
        <i class="fa-solid fa-print mx-2"></i>
        <i class="fa-solid fa-user-slash"></i>
        <h3>Copieurs Sans Responsable</h3>
    </a>
HTML;
}

function link_machines_without_counter_3_months($urlRouter): string
{
    return <<<HTML
    <a href="$urlRouter" class="home_action text-center">
        <i class="fa-solid fa-book mx-2"></i>
        <i class="fa-sharp fa-solid fa-circle-exclamation"></i>
        <h3>Copieurs Sans Relevé depuis 3 Mois</h3>
    </a>
HTML;
}

function link_users_area($urlRouter): string
{
    return <<<HTML
    <a href="$urlRouter" class="home_action text-center">
        <i class="fa-solid fa-file"></i>
        <i class="fa-solid fa-user"></i>
        <h3>Gestion des utilisateurs</h3>
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