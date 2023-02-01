<?php
require './source/functions.php';
initialisationSEssion();

if (isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] === 'deconnexion') {
    fermetureSession();
    header('Location: index.php?action=accueil');
    exit();
} else {
    header('Location: index.php?action=accueil');
    exit();
}
