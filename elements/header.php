<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <?php
    if (isset($accueil) && $accueil == "accueil") {
        echo '<link rel="stylesheet" href="./css/accueil.css">';
    } elseif (isset($ajouterRecette) && $ajouterRecette == "ajouterRecette") {
        echo '<link rel="stylesheet" href="./css/ajouterRecette.css">';
    } elseif (isset($confirmationSuppression) && $confirmationSuppression == "confirmationSuppression") {
        echo '<link rel="stylesheet" href="./css/confirmationSuppression.css">';
    } elseif (isset($connexion) && $connexion == "connexion") {
        echo '<link rel="stylesheet" href="./css/connexion.css">';
    } elseif (isset($contact) && $contact == "contact") {
        echo '<link rel="stylesheet" href="./css/contact.css">';
    } elseif (isset($recettes) && $recettes == "recettes" || isset($desserts) && $desserts == "desserts" || isset($entrees) && $entrees == "entrees" || isset($plats) && $plats == "plats" || isset($gourmandises) && $gourmandises == "gourmandises" || isset($erreurContact) && $erreurContact == "erreurContact") {
        echo '<link rel="stylesheet" href="./css/recettes.css">
            <link rel="stylesheet" href="./css/erreurRecettes.css">';
    } elseif (isset($inscription) && $inscription == "inscription") {
        echo '<link rel="stylesheet" href="./css/inscription.css">';
    } elseif (isset($modifierProfil) && $modifierProfil == "modifierProfil") {
        echo '<link rel="stylesheet" href="./css/modifierProfil.css">';
    } elseif (isset($modifierRecette) && $modifierRecette == "modifierRecette") {
        echo '<link rel="stylesheet" href="./css/modifierRecette.css">';
    } elseif (isset($profil) && $profil == "profil") {
        echo '<link rel="stylesheet" href="./css/profil.css">';
    } elseif (isset($recapitulatifContact) && $recapitulatifContact == "recapitulatifContact") {
        echo '<link rel="stylesheet" href="./css/recapitulatifContact.css">
            <link rel="stylesheet" href="./css/erreurContact.css">';
    } elseif (isset($succesAjoutCommentaire) && $succesAjoutCommentaire == "succesAjoutCommentaire" || isset($succesAjoutRecette) && $succesAjoutRecette == "succesAjoutRecette") {
        echo '<link rel="stylesheet" href="./css/succesAjoutRecette.css">';
    } elseif (isset($succesProfil) && $succesProfil == "succesProfil") {
        echo '<link rel="stylesheet" href="./css/succesProfil.css">';
    } elseif (isset($supprimerRecette) && $supprimerRecette == "supprimerRecette") {
        echo '<link rel="stylesheet" href="./css/supprimerRecette.css">';
    }
    ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope&family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,300,0,0">
    <title>Cook-Time</title>
    <?php
    if (isset($ajouterRecette) && $ajouterRecette == "ajouterRecette") {
        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="elements/javaScript/ajoutRecette.js" async></script>';
    } elseif (isset($connexion) && $connexion == "connexion") {
        echo '<script src="elements/javaScript/connexion.js" async></script>';
    } elseif (isset($desserts) && $desserts == "desserts") {
        echo ' <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
            <script src="elements/javaScript/dessert.js" async></script>';
    } elseif (isset($entrees) && $entrees == "entrees") {
        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
            <script src="elements/javaScript/entrees.js" async></script>';
    } elseif (isset($gourmandises) && $gourmandises == "gourmandises") {
        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
            <script src="elements/javaScript/gourmandise.js" async></script>';
    } elseif (isset($inscription) && $inscription == "inscription") {
        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="elements/javaScript/inscription.js" async></script>';
    } elseif (isset($modifierProfil) && $modifierProfil == "modifierProfil") {
        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="elements/javaScript/modifierProfil.js" async></script>';
    } elseif (isset($modifierRecette) && $modifierRecette == "modifierRecette") {
        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="elements/javaScript/modifierRecette.js" async></script>';
    } elseif (isset($plats) && $plats == "plats") {
        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
            <script src="elements/javaScript/plats.js" async></script>';
    } elseif (isset($profil) && $profil == "profil") {
        echo '<script src="elements/javaScript/profil.js" async></script>';
    }
    ?>

</head>

<body>
    <header>
        <nav>
            <a href="index.php?action=accueil" class="lienIcone">
                <img src="./images/logo.png" alt="logo lien accueil site de recettes cuisine" title="Retour Accueil">
            </a>
            <div class="liensNavigation">
                <a href="index.php?action=accueil" <?php if (isset($navigationEnCours) && $navigationEnCours == 'accueil') {
                                                        echo ' id="navigationEnCours"';
                                                    } ?>>Accueil</a>
                <a href="index.php?action=recettes" <?php if (isset($navigationEnCours) && $navigationEnCours == 'recettes') {
                                                        echo ' id="navigationEnCours"';
                                                    } ?>>Recettes</a>
                <a href="index.php?action=contactez-nous" <?php if (isset($navigationEnCours) && $navigationEnCours == 'contact') {
                                                                echo ' id="navigationEnCours"';
                                                            } ?>>Contact</a>
                <?php if (utilisateurConnecte()) : ?>
                    <a href="index.php?action=mon-profil" <?php if (isset($navigationEnCours) && $navigationEnCours == 'profil') {
                                                                echo ' id="navigationEnCours"';
                                                            } ?>>Mon profil</a>
                    <a href="index.php?action=deconnexion">Déconnexion</a>
                <?php else : ?>
                    <a href="index.php?action=authentification" <?php if (isset($navigationEnCours) && $navigationEnCours == 'connexionInscription') {
                                                                    echo ' id="navigationEnCours"';
                                                                } ?>>Connexion / Inscription</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>