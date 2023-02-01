<?php
require 'source/functions.php';
initialisationSEssion();
$navigationEnCours = "profil";
$modifierRecette = "modifierRecette";
if (isset($_SESSION['modificationRecetteEnCours']) && $_SESSION['modificationRecetteEnCours'] !== '') {
    $resultatExistanceRecette = existanceRecette($_SESSION['modificationRecetteEnCours']);
} else {
    $resultatExistanceRecette = existanceRecette($_GET['id']);
}

if (!utilisateurConnecte()) {

    header('Location: index.php?action=accueil');
    exit();
} elseif ($resultatExistanceRecette['RECETTE_ID'] == isset($_GET['id']) && $resultatExistanceRecette['USER_ID_RECETTE'] == $_SESSION["id"] || isset($_SESSION['modificationRecetteEnCours']) && $_SESSION['modificationRecetteEnCours'] !== '') {
    $resultatUniteMesure = getUniteMesure();
    $idRecette = $resultatExistanceRecette['RECETTE_ID'];
    $titreRecette = $resultatExistanceRecette['RECETTE_TITRE'];
    $typeRecette = $resultatExistanceRecette['RECETTE_TYPE'];
    $photoRecette = $resultatExistanceRecette['RECETTE_PHOTO'];

    $_SESSION['modificationRecetteEnCours'] = $idRecette;
    if (isset($_POST['value']) && $_POST['value'] === 'supprimerOldImage') {
        $_SESSION['oldImage'] =  $_POST['value'];
    }


    if ($photoRecette !== NULL) {
        $image_path = "./uploads/imagesRecettes/" . $photoRecette;
        $photoSize = filesize($image_path);
        $_SESSION['photoName'] = $photoRecette;
    }

    $etapes = getEtapes($idRecette);
    if (count($etapes) > 3) {
        $countEtapes = count($etapes);
    } else {
        $countEtapes = 3;
    }

    $ingredients = ingredientMaRecette($idRecette);
    $countIngredients = count($ingredients);

    $_SESSION['idRecette'] = $idRecette;

?>

    <?php include_once('./elements/header.php'); ?>

    <main>
        <section>
            <div class="cardModifierRecette">
                <h2>Modifier la recette</h2>
                <div class="containerModifierRecette">
                    <form method="POST" action="./traitements/traitementModifierRecette.php" id="formulaireModifierRecette" enctype="multipart/form-data">
                        <div class="logoSite">
                            <img src="./images/logoSiteRecettes.png" alt="logo site de recettes de cuisine">
                        </div>
                        <div class="entreeTitreRecette">
                            <label for="titreRecette2"><span class="material-symbols-rounded edit">edit_square</span>Titre de la recette</label>
                            <input type="text" name="titreRecette" id="titreRecette2" maxlength="128" value="<?php echo strip_tags($titreRecette); ?>" required>
                            <?php if (isset($_GET['erreurTitre'])) {
                                echo '<p id="error" class="erreur">' . $_GET['erreurTitre'] . '</p>';
                            } ?>
                        </div>
                        <div class="entreeTypeRecette">
                            <label for="typeRecette2"><span class="material-symbols-rounded edit">edit_square</span>Type de la recette</label>
                            <select name="typeRecette" id="typeRecette2">
                                <?php modifierChoixTypeRecette($typeRecette); ?>
                            </select>
                            <?php if (isset($_GET['erreurType'])) {
                                echo '<p id="error" class="erreur">' . $_GET['erreurType'] . '</p>';
                            } ?>
                        </div>
                        <div class="entreeImageRecette">
                            <label class="labelSolide"><span class="material-symbols-rounded">image</span>Ajouter une image de la recette</label>
                            <input type="file" name="imageRecette" id="imageRecette2" accept=".jpg, .jpeg, .png" class="inputInvisible">
                            <input type="hidden" id="hiddenValue" name="oldimage" <?php if (isset($photoRecette)) {
                                                                                        echo 'value="' . strip_tags($photoRecette) . '"';
                                                                                    }; ?>>
                            <input type="hidden" id="hiddenValue2" <?php if (isset($photoSize)) {
                                                                        echo 'value="' . $photoSize . '"';
                                                                    } ?>>
                            <div class="containerFauxInput">
                                <label for="imageRecette2" class="fauxBouton">Choisir une image (PNG, JPG)</label>
                                <div class="previewImage">
                                    <?php if ($photoRecette == NULL || $photoRecette == '') {
                                        echo '<p>Aucune image sélectionnée</p>';
                                    }; ?>
                                </div>
                            </div>
                            <span class="supprimerImage" onclick="supprimerPhoto('supprimerOldImage')"><img src="./images/style/delete.svg"></span>
                            <?php if (isset($_GET['erreurTailleImage'])) {
                                echo '<p id="error" class="erreur">' . $_GET['erreurTailleImage'] . '</p>';
                            } ?>
                            <?php if (isset($_GET['erreurImage'])) {
                                echo '<p id="error" class="erreur">' . $_GET['erreurImage'] . '</p>';
                            } ?>
                            <?php if (isset($_GET['erreurUploadImage'])) {
                                echo '<p id="error" class="erreur">' . $_GET['erreurUploadImage'] . '</p>';
                            } ?>
                            <?php if (isset($_GET['erreurFormatImage'])) {
                                echo '<p id="error" class="erreur">' . $_GET['erreurFormatImage'] . '</p>';
                            } ?>
                        </div>

                        <div class="containerQuantiteMesureIngredient" id="containerIngredient2">
                            <div class="containerQuantite">
                                <label for="modifierQuantite1"><span class="material-symbols-rounded">kettle</span>Quantité</label>
                                <input type="text" maxlength="6" name="quantite1" id="modifierQuantite1" class="quantite" value="<?php echo strip_tags($ingredients[0]['RECETTE_INGREDIENT_QUANTITE']); ?>" required>
                            </div>
                            <div class="containerUniteMesure">
                                <label for="modifierUniteMesure1"><span class="material-symbols-rounded">grouped_bar_chart</span>Mesure</label>
                                <select name="uniteMesure1" id="modifierUniteMesure1" class="unite" required><?php modifierChoixUniteMesure($resultatUniteMesure, $ingredients[0]['UNITE_MESURE_NOM']); ?></select>
                            </div>
                            <div class="containerIngredient">
                                <label for="modifierIngredient1"><span class="material-symbols-rounded">scatter_plot</span>Ingrédient</label>
                                <input type="text" maxlength="35" name="ingredient1" id="modifierIngredient1" class="ingredient" value="<?php echo strip_tags($ingredients[0]['INGREDIENT_NOM']); ?>" required>
                            </div>
                            <div class="containerQuantite">
                                <input type="text" maxlength="6" name="quantite2" id="modifierQuantite2" class="quantite" value="<?php echo strip_tags($ingredients[1]['RECETTE_INGREDIENT_QUANTITE']); ?>" required>
                            </div>
                            <div class="containerUniteMesure">
                                <select name="uniteMesure2" id="modifierUniteMesure2" class="unite" required><?php modifierChoixUniteMesure($resultatUniteMesure, $ingredients[1]['UNITE_MESURE_NOM']); ?></select>
                            </div>
                            <div class="containerIngredient">
                                <input type="text" maxlength="35" name="ingredient2" id="modifierIngredient2" class="ingredient" value="<?php echo strip_tags($ingredients[1]['INGREDIENT_NOM']); ?>" required>
                            </div>
                            <div class="containerQuantite">
                                <input type="text" maxlength="6" name="quantite3" id="modifierQuantite3" class="quantite" value="<?php echo strip_tags($ingredients[2]['RECETTE_INGREDIENT_QUANTITE']); ?>" required>
                            </div>
                            <div class="containerUniteMesure">
                                <select name="uniteMesure3" id="modifierUniteMesure3" class="unite" required><?php modifierChoixUniteMesure($resultatUniteMesure, $ingredients[2]['UNITE_MESURE_NOM']); ?></select>
                            </div>
                            <div class="containerIngredient">
                                <input type="text" maxlength="35" name="ingredient3" id="modifierIngredient3" class="ingredient" value="<?php echo strip_tags($ingredients[2]['INGREDIENT_NOM']); ?>" required>
                            </div>
                            <?php
                            if ($countIngredients > 3) {
                                for ($indexTableauIngredient = 0; $indexTableauIngredient < 3; $indexTableauIngredient++) {

                                    unset($ingredients[$indexTableauIngredient]);
                                }
                                afficherIngredients($ingredients,  $resultatUniteMesure);
                            } ?>
                        </div>
                        <div id="inputContainerModifier"></div>
                        <div class="containerAjoutIngredient">
                            <span id="ajoutIngredient2" class="ajoutIngredient" onclick="ajoutIngredient()"><span class="material-symbols-rounded">add_box</span>Ajouter un ingredient</span>
                        </div>
                        <?php if (isset($_GET['erreurTailleTableau'])) {
                            echo '<p id="error" class="erreur">' . $_GET['erreurTailleTableau'] . '</p>';
                        } ?>
                        <?php if (isset($_GET['erreurQuantite'])) {
                            echo '<p id="error" class="erreur">' . $_GET['erreurQuantite'] . '</p>';
                        } ?>
                        <?php if (isset($_GET['erreurUniteMesure'])) {
                            echo '<p id="error" class="erreur">' . $_GET['erreurUniteMesure'] . '</p>';
                        } ?>
                        <?php if (isset($_GET['erreurIngredient'])) {
                            echo '<p id="error" class="erreur">' . $_GET['erreurIngredient'] . '</p>';
                        } ?>

                        <div class="entreeEtapeRecette">
                            <label for="modifierEtapeRecette1"><span class="material-symbols-rounded edit">edit_square</span>Étape 1</label>
                            <textarea name="etapeRecette1" id="modifierEtapeRecette1" rows="5" minlength="5" maxlength="1000" required><?php echo strip_tags($etapes[0]['ETAPE_INSTRUCTION']); ?></textarea>
                        </div>
                        <div class="entreeEtapeRecette">
                            <label for="modifierEtapeRecette2"><span class="material-symbols-rounded edit">edit_square</span>Étape 2</label>
                            <textarea name="etapeRecette2" id="modifierEtapeRecette2" rows="5" minlength="5" maxlength="1000" required><?php echo strip_tags($etapes[1]['ETAPE_INSTRUCTION']); ?></textarea>
                        </div>
                        <div class="entreeEtapeRecette">
                            <label for="modifierEtapeRecette3"><span class="material-symbols-rounded edit">edit_square</span>Étape 3</label>
                            <textarea name="etapeRecette3" id="modifierEtapeRecette3" rows="5" minlength="5" maxlength="1000" required><?php echo strip_tags($etapes[2]['ETAPE_INSTRUCTION']); ?></textarea>
                        </div>
                        <div class="entreeEtapeRecette">
                            <label for="modifierEtapeRecette4"><span class="material-symbols-rounded edit">edit_square</span>Étape 4</label>
                            <textarea name="etapeRecette4" id="modifierEtapeRecette4" rows="5" maxlength="1000"><?php if ($countEtapes > 3) {
                                                                                                                    echo strip_tags($etapes[3]['ETAPE_INSTRUCTION']);
                                                                                                                } ?></textarea>
                        </div>
                        <?php if ($countEtapes > 4) {
                            for ($indexTableauEtapes = 0; $indexTableauEtapes <= 3; $indexTableauEtapes++) {
                                unset($etapes[$indexTableauEtapes]);
                            }
                            afficherEtapes($etapes, $countEtapes);
                        } ?>
                        <div id="textarea-container2">
                        </div>
                        <div class="containerAjoutEtape">
                            <span id="ajoutEtape2" class="ajoutEtape" onclick="ajoutEtape()"><span class="material-symbols-rounded">add_box</span>Ajouter une étape</span>
                        </div>
                        <?php if (isset($_GET['erreurEtapes'])) {
                            echo '<p id="error" class="erreur">' . $_GET['erreurEtapes'] . '</p>';
                        } ?>
                        <div class="containerBoutonModifierRecette">
                            <input type="submit" name="validationModifierRecette" value="MODIFIER" class="boutonModifier">
                            <input type="reset" value="ANNULER" class="boutonAnnuler">
                        </div>
                        <div id="error-message2" class="erreur"></div>
                    </form>
                </div>
            </div>
        </section>
    </main>
<?php include_once('./elements/footer.php');
} else {
    header('Location: index.php?action=Mon-profil');
    exit();
};  ?>