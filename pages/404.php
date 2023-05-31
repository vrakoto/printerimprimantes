<?php
$title = "404 Error";
?>

<div class="container text-center alert alert-danger mt-3">
    <p><?= $msg ?? 'Page introuvable' ?></p>
    <a href="/" class="btn btn-primary">Revenir à l'accueil</a>
</div>