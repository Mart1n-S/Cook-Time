<?php
require 'source/functions.php';
initialisationSEssion();
$navigationEnCours = "profil";
$succesAjoutRecette = "succesAjoutRecette";
if (!utilisateurConnecte()) {

    header('Location: index.php?action=accueil');
    exit();
} elseif (isset($_SESSION['succesAjout']) && $_SESSION['succesAjout'] === true || isset($_SESSION['succesModificationRecette']) && $_SESSION['succesModificationRecette'] === true) {

    if (isset($_SESSION['succesAjout']) === true) {
        $ajout = true;
    }
    $_SESSION['succesAjout'] = false;
    $_SESSION['succesModificationRecette'] = false;
?>

    <?php include_once('./elements/header.php'); ?>
    <main>
        <section>
            <div class="cardSuccesAjout">
                <h2><?php if (isset($ajout) && $ajout == true) {
                        echo 'RECETTE AJOUTÉE AVEC SUCCÈS';
                    } else {
                        echo 'RECETTE MODIFIÉE AVEC SUCCÈS';
                    } ?><span class="material-symbols-rounded">celebration</span></h2>
                <div class="containerSuccesAjout">
                    <div class="logoSite">
                        <img src="./images/logoSiteRecettes.png" alt="logo site de recettes de cuisine">
                    </div>

                    <?php if (isset($ajout) && $ajout == true) {
                        echo '<p>Votre recette a bien été ajoutée.</p>';
                    } else {
                        echo '<p>Votre recette a bien été modifiée.</p>';
                    } ?>

                    <div class="retourProfil">
                        <a href="index.php?action=mon-profil" class="boutonRetourProfil">RETOURNER AU PROFIL</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php include_once('./elements/footer.php');
} else {
    header('Location: index.php?action=accueil');
    exit();
};  ?>