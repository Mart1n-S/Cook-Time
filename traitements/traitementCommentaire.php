<?php
require '../source/functions.php';
initialisationSEssion();

if (!utilisateurConnecte()) {

    header('Location: ../index.php?action=accueil');
    exit();
} else {

    $ajoutCommentaire = $_POST['validationCommentaire'];
    if (isset($ajoutCommentaire) && !empty($ajoutCommentaire)) {

        $typeRecette = $_POST['typeRecette'];
        $titreRecette = $_POST['recetteTitre'];
        $commentaire = trim(isset($_POST['commentaireContenu']) ? $_POST['commentaireContenu'] : '');
        $recetteCommenter = $_POST['hiddenID'];
        $auteurCommentaire = $_SESSION['id'];
        $date = date("Y-m-d");

        $commentaireTraite = traitementCommentaire($commentaire, $typeRecette, $titreRecette);

        $recetteCommenterTraite = traitementIDRecetteCommenter($recetteCommenter);



        $resultatInsertionCommentaire = insertionCommentaire($auteurCommentaire, $recetteCommenterTraite, $date, $commentaireTraite);

        if ($resultatInsertionCommentaire == true) {
            $_SESSION['succesAjoutCommentaire'] = true;
            header('Location: ../index.php?action=validation-commentaire');
            exit();
        } else {
            header('Location: ../index.php?action=recettes');
            exit();
        }
    } else {
        header('Location: ../index.php?action=accueil');
        exit();
    }
}
