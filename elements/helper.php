<?php

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

function mandatoryFieldMessage(): string
{
    return <<<HTML
    <div class="mt-3">
        <span class="obligatoire">*</span><i> Champ à remplir obligatoirement</i>
    </div>
HTML;
}

function messageForm(array $fields = []): string
{
    if (!empty($_SESSION['message'])) {
        if (!empty($fields)) {
            $form_fields = "<div class='alert alert-danger'>";
            $form_fields .= "Formulaire invalide :";
            $form_fields .= "<ul>";
            foreach ($fields as $field) {
                $form_fields .= '<li>' . $field . '</li>';
            }
            $form_fields .= "</ul>";
            $form_fields .= "</div>";
            return $form_fields;
        }
        
        // Message normal
        $sm = $_SESSION['message'];
        $typeMessage = (array_key_first($sm) === "error") ? "danger" : "success";
        $message = ($sm['error']) ?? $sm['success'];

        $simpleMessageForm = "<div class='alert alert-$typeMessage'>";
        $simpleMessageForm .= $message;
        $simpleMessageForm .= "</div>";
        return $simpleMessageForm;
    }
    return '';
}

function link_machinesInMyArea($urlRouter): string
{
    return <<<HTML
    <a href="$urlRouter" class="home_action text-center">
        <i class="fa-solid fa-print mx-2"></i>
        <i class="fa-solid fa-location-dot"></i>
        <h3>Copieurs du périmètre</h3>
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
    return <<<HTML
    <a href="$urlRouter" class="home_action text-center">
        <i class="fa-solid fa-print mx-2"></i>
        <i class="fa-solid fa-pen"></i>
        <h3>Inscrire une machine</h3>
    </a>
HTML;
}

function link_counters_area($urlRouter): string
{
    return <<<HTML
    <a href="$urlRouter" class="home_action text-center">
        <i class="fa-solid fa-book mx-2"></i>
        <i class="fa-solid fa-location-dot"></i>
        <h3>Compteurs du perimètre</h3>
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

function link_add_counter($urlRouter): string
{
    return <<<HTML
    <a href="$urlRouter" class="home_action text-center">
        <i class="fa-solid fa-book mx-2"></i>
        <i class="fa-solid fa-pen"></i>
        <h3>Ajouter un relevé</h3>
    </a>
HTML;
}