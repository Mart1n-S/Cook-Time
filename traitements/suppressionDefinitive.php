<?php require '../source/functions.php';
initialisationSEssion();
$navigationEnCours = "profil";


if (utilisateurConnecte()) {
    if (isset($_POST['suppressionDefinitive'])) {
        $resultatExistanceRecetteDefinitivement = existanceRecette($_POST['id']);
        if (isset($resultatExistanceRecetteDefinitivement['RECETTE_ID']) == isset($_POST['id']) && isset($resultatExistanceRecetteDefinitivement['USER_ID_RECETTE']) == isset($_SESSION["id"])) {
            supprimerRecette($_POST['id']);
            $_SESSION['succesSuppression'] = true;
            header('Location: ../index.php?action=validation-suppression');
        } else {
            header('Location: ../index.php?action=mon-profil');
            exit();
        }
    } else {
        header('Location: ../index.php?action=mon-profil');
        exit();
    }
} else {
    header('Location: ../index.php?action=accueil');
    exit();
}
