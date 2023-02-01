<?php
require 'source/functions.php';
initialisationSEssion();
$navigationEnCours = "profil";
$profil = "profil";
if (!utilisateurConnecte()) {

    header('Location: index.php?action=accueil');
    exit();
} else {
    $_SESSION['modificationRecetteEnCours'] = '';
    $_SESSION['modificationProfilEnCours'] = '';
?>

    <?php include_once('./elements/header.php'); ?>

    <main>
        <section class="sectionInformationsProfil">
            <div class="cardProfil">
                <h2>Mon profil</h2>
                <div class="containerInformationsProfil">
                    <div class="photoProfil">
                        <?php
                        if (isset($_SESSION['photo']) && $_SESSION['photo'] != 'NULL' && file_exists("./uploads/photoProfil/" . strip_tags($_SESSION['photo']))) { ?>
                            <img src="./uploads/photoProfil/<?php echo strip_tags($_SESSION['photo']) ?>" class="photoUtilisateur" alt="photo de profil">
                        <?php } else { ?>
                            <img src="./images/photoProfilDefaut/defaut.svg" alt="photo de profil par defaut">
                        <?php }; ?>
                    </div>
                    <h3><?php echo strip_tags($_SESSION['prenom']); ?></h3>
                    <div class="containerInformationsProfil2">
                        <div class="nomProfil">
                            <label><span class="material-symbols-rounded">person</span>Nom</label>
                            <p><?php echo strip_tags($_SESSION['nom']); ?></p>
                        </div>
                        <div class="prenomProfil">
                            <label><span class="material-symbols-rounded">person</span>Prenom</label>
                            <p><?php echo strip_tags($_SESSION['prenom']); ?></p>
                        </div>
                        <div class="ageProfil">
                            <label><span class="material-symbols-rounded">cake</span>Age</label>
                            <p><?php echo strip_tags($_SESSION['age']); ?></p>
                        </div>
                        <div class="emailProfil">
                            <label><span class="material-symbols-rounded"> mail </span>Email</label>
                            <p><?php echo strip_tags($_SESSION['email']); ?></p>
                        </div>
                        <div class="mdpProfil">
                            <label><span class="material-symbols-rounded"> lock </span>Mot de passe</label>
                            <p>• • • • • • • •</p>
                        </div>
                    </div>
                    <form action="index.php?action=modifier-mon-profil" method="post">
                        <input type="submit" name="modifierProfil" value="MODIFIER" class="boutonModifierProfil">
                    </form>
                </div>
            </div>
        </section>
        <section class="sectionMesPublicationsProfil">
            <div class="cardMesPublications">
                <h2>Mes publications</h2>
                <div class="containerInformationsMesPublications">
                    <h3>Compteur recettes</h3>
                    <div class="compteurRecettes">
                        <label><span class="material-symbols-rounded">add_comment</span>Nombre de recettes publiées</label>
                        <p><?php echo nombreRecettes(); ?></p>
                    </div>
                    <form action="index.php?action=ajouter-une-recette" method="post">
                        <input type="submit" name="ajouterRecette" value="AJOUTER UNE RECETTE" class="boutonAjouterRecette">
                    </form>
                </div>
            </div>
        </section>



        <?php $resultatInfosRecette = infosRecettes();
        foreach ($resultatInfosRecette as $recette) {

            $idRecette = strip_tags($recette['RECETTE_ID']);
            $titreMaRecette = strip_tags($recette['RECETTE_TITRE']);
            $typeMaRecette = strip_tags($recette['RECETTE_TYPE']);
            $photoMaRecette = strip_tags($recette['RECETTE_PHOTO']);
            $visibiliteMaRecette = strip_tags($recette['RECETTE_VISIBILITE']);
        ?><h2>Mes recettes</h2>
            <section class="sectionMesRecettesProfil">
                <div class="cardMesRecettes">

                    <div class="containerMesRecettes">
                        <h3><?php echo $titreMaRecette; ?></h3>
                        <div class="photoMesRecettes">
                            <?php
                            if (isset($photoMaRecette) && $photoMaRecette != NULL && file_exists("./uploads/imagesRecettes/" . strip_tags($photoMaRecette))) { ?>
                                <img src="./uploads/imagesRecettes/<?php echo strip_tags($photoMaRecette) ?>" class="photoRecette" alt="photo de recette">
                            <?php } else { ?>
                                <img src="./images/photoRecetteDefaut/recetteDefaut.svg" class="photoRecetteDefaut" alt="photo de recette par defaut">
                            <?php }; ?>
                            <p>Type de la recette : <span><?php if (isset($typeMaRecette) && $typeMaRecette != NULL) {
                                                                echo strip_tags($typeMaRecette);
                                                            } else {
                                                                echo "Non déterminé";
                                                            }  ?></span></p>
                        </div>
                        <h3>INGREDIENTS </h3>
                        <ul>
                            <?php $resultatIngredientMaRecette = ingredientMaRecette($idRecette);
                            foreach ($resultatIngredientMaRecette as $unIngredient) {

                                echo "<li>" . strip_tags($unIngredient['RECETTE_INGREDIENT_QUANTITE']) . ' ' . strip_tags($unIngredient['UNITE_MESURE_NOM']) . ' ' . strip_tags($unIngredient['INGREDIENT_NOM']) . "</li>";
                            }
                            ?>
                        </ul>
                        <h3>ÉTAPES<span class="material-symbols-rounded">restaurant_menu</span></h3>
                        <div class="etapesMesRecettes">
                            <?php $resultatEtapesMaRecette = etapesMaRecette($idRecette);
                            $numeroEtape = 1;
                            $numeroLigneResultatEtape = 1;
                            foreach ($resultatEtapesMaRecette as $uneEtape) {
                                while ($numeroEtape <= $numeroLigneResultatEtape) {
                                    echo "<h4> Etapes " . $numeroEtape . "</h4>";
                                    $numeroEtape++;
                                }
                                $numeroLigneResultatEtape++;
                                echo "<p>" . ucfirst(strip_tags($uneEtape['ETAPE_INSTRUCTION']))  . "</p>";
                            }
                            ?>
                        </div>
                        <div class="boutonModifierSupprimer">
                            <div class="modifierRecetteProfil">
                                <a href="index.php?action=modifier-ma-recette&id=<?php echo $idRecette; ?>" class="boutonModifierRecette">MODIFIER LA RECETTE</a>
                            </div>
                            <div class="supprimerRecetteProfil">
                                <a href="index.php?action=suppression-recette&id=<?php echo $idRecette; ?>&amp;titre=<?php echo $titreMaRecette; ?>&amp;photo=<?php echo $photoMaRecette; ?>" class="boutonSupprimerRecette">SUPPRIMER LA RECETTE</a>
                            </div>

                        </div>
                        <form class="checkboxVisibiliteRecetteProfil">
                            <label for="<?php echo $idRecette; ?>">Visibilité de votre recette : </label>
                            <input type="checkbox" class="toggle" name="visibiliteRecette" id="<?php echo $idRecette; ?>" value="<?php echo $visibiliteMaRecette; ?>" <?php if ($visibiliteMaRecette > 0) {
                                                                                                                                                                            echo 'checked="checked"';
                                                                                                                                                                        }; ?> onclick="changeVisibiliteRecette(<?php echo $idRecette; ?>)">
                        </form>
                    </div>
                </div>
            </section>
        <?php } ?>
    </main>
<?php include_once('./elements/footer.php');
}; ?>