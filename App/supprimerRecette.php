<?php require 'source/functions.php';
initialisationSEssion();
$navigationEnCours = "profil";
$supprimerRecette = "supprimerRecette";
$resultatExistanceRecette = existanceRecette($_GET['id']);
if (utilisateurConnecte()) {
    if ($resultatExistanceRecette['RECETTE_ID'] == $_GET['id'] && $resultatExistanceRecette['USER_ID_RECETTE'] == $_SESSION["id"]) {
        $getData = $_GET; ?>

        <?php include_once('./elements/header.php'); ?>

        <main>
            <section class="sectionSupprimerRecetteProfil">
                <div class="cardSupprimerRecetteProfil">
                    <h2>Supprimer la recette ?</h2>
                    <div class="containerSupprimerRecetteProfil">
                        <h3><?php echo strip_tags($getData['titre']); ?></h3>
                        <div class="photoMaRecette">
                            <?php
                            if (isset($getData['photo']) && $getData['photo'] != NULL && file_exists("./uploads/imagesRecettes/" . strip_tags($getData['photo']))) { ?>
                                <img src="./uploads/imagesRecettes/<?php echo strip_tags($getData['photo']) ?>" class="photoRecette" alt="photo de recette">
                            <?php } else { ?>
                                <img src="./images/photoRecetteDefaut/recetteDefaut.svg" class="photoRecetteDefaut" alt="photo de recette par defaut">
                            <?php }; ?>
                        </div>
                        <div class="confirmerSupprimerRecette">
                            <p>Vous êtes sur le point de supprimer définitivement votre recette.</p>
                        </div>
                        <form action="./traitements/suppressionDefinitive.php" method="POST">
                            <div class="informationsRecetteASupprimer">
                                <input type="hidden" id="idRecette" name="id" value="<?php echo strip_tags($getData['id']); ?>">
                            </div>
                            <button type="submit" name="suppressionDefinitive" class="boutonSupprimerDéfinitivement">SUPPRESSION DÉFINITIVE</button>
                        </form>
                    </div>
                </div>
            </section>


        </main>

<?php include_once('./elements/footer.php');
    } else {
        header('Location: index.php?action=mon-profil');
        exit();
    }
} else {
    header('Location: index.php?action=accueil');
    exit();
}
?>