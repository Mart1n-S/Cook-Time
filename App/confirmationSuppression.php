<?php
require './source/functions.php';
initialisationSEssion();
$confirmationSuppression = "confirmationSuppression";
$navigationEnCours = "profil";
if (!utilisateurConnecte()) {

    header('Location: index.php?action=accueil');
    exit();
} elseif ($_SESSION['succesSuppression'] === true) {
    $_SESSION['succesSuppression'] = false;

?>


    <?php include_once('./elements/header.php'); ?>

    <section>
        <div class="cardSuccesSuppression">
            <h2>SUPPRIMÉ AVEC SUCCÉS<span class="material-symbols-rounded">celebration</span></h2>
            <div class="containerInformationSuppresion">
                <div class="logoSite">
                    <img src="./images/logoSiteRecettes.png" alt="logo site de recettes de cuisine">
                </div>

                <p>Votre recette à bien été supprimé.</p>

                <div class="retourProfil">
                    <a href="index.php?action=mon-profil" class="boutonRetourProfil">RETOURNER AU PROFIL</a>
                </div>
            </div>
        </div>
    </section>

<?php include_once('./elements/footer.php');
} else {
    header('Location: index.php?action=mon-profil');
    exit();
}; ?>