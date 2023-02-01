<?php

if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'accueil';
}
switch ($_REQUEST['action']) {
    case 'accueil':
        require_once './App/accueil.php';
        break;
    case 'ajouter-une-recette':
        require './App/ajouterRecette.php';
        break;
    case 'validation-suppression':
        require './App/confirmationSuppression.php';
        break;
    case 'authentification':
        require './App/connexion.php';
        break;
    case 'contactez-nous':
        require './App/contact.php';
        break;
    case 'recettes-desserts':
        require './App/desserts.php';
        break;
    case 'recettes-entrees':
        require './App/entrees.php';
        break;
    case 'erreur-contact':
        require './App/erreurContact.php';
        break;
    case 'recettes-plats':
        require './App/plats.php';
        break;
    case 'recettes-gourmandises':
        require './App/gourmandises.php';
        break;
    case 'inscrivez-vous':
        require './App/inscription.php';
        break;
    case 'modifier-mon-profil':
        require './App/modifierProfil.php';
        break;
    case 'modifier-ma-recette':
        require './App/modifierRecette.php';
        break;
    case 'mon-profil':
        require './App/profil.php';
        break;
    case 'recapitulatif-message':
        require './App/recapitulatifContact.php';
        break;
    case 'recettes':
        require './App/recettes.php';
        break;
    case 'validation-commentaire':
        require './App/succesAjoutCommentaire.php';
        break;
    case 'validation-ajout-recette':
        require './App/succesAjoutRecette.php';
        break;
    case 'validation-modification-profil':
        require './App/succesProfil.php';
        break;
    case 'suppression-recette':
        require './App/supprimerRecette.php';
        break;
    case 'deconnexion':
        require './fonctionnalites/deconnexion.php';
        break;
}
