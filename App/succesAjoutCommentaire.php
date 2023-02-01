<?php
require 'source/functions.php';
initialisationSEssion();
$navigationEnCours = "recette";
$succesAjoutCommentaire = "succesAjoutCommentaire";
if (!utilisateurConnecte()) {

    header('Location: index.php?action=accueil');
    exit();
} elseif (isset($_SESSION['succesAjoutCommentaire']) && $_SESSION['succesAjoutCommentaire'] === true) {

    $_SESSION['succesAjoutCommentaire'] = false;
?>

    <?php include_once('./elements/header.php'); ?>
    <main>
        <section>
            <div class="cardSuccesAjout">
                <h2>COMMENTAIRE AJOUTÉ AVEC SUCCÉS<span class="material-symbols-rounded">celebration</span></h2>
                <div class="containerSuccesAjout">
                    <div class="logoSite">
                        <img src="./images/logoSiteRecettes.png" alt="logo site de recettes de cuisine">
                    </div>
                    <p>Votre commentaire a bien été ajouté.</p>

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