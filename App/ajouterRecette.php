<?php
require './source/functions.php';
initialisationSEssion();
$ajouterRecette = "ajouterRecette";
$navigationEnCours = "profil";

if (!utilisateurConnecte()) {

    header('Location: index.php?action=accueil');
    exit();
} else {
    $resultatUniteMesure = getUniteMesure();
?>

    <?php include_once('./elements/header.php'); ?>

    <main>
        <section>
            <div class="cardAjoutRecette">
                <h2>Ajouter une recette</h2>
                <div class="containerAjoutRecette">
                    <form method="POST" action="./traitements/traitementAjoutRecette.php" id="formulaireAjoutRecette" enctype="multipart/form-data">
                        <div class="logoSite">
                            <img src="./images/logoSiteRecettes.png" alt="logo site de recettes de cuisine">
                        </div>
                        <div class="entreeTitreRecette">
                            <label for="titreRecette1"><span class="material-symbols-rounded edit">edit_square</span>Titre de la recette</label>
                            <input type="text" name="titreRecette" id="titreRecette1" maxlength="128" required>
                            <?php if (isset($_GET['erreurTitre'])) {
                                echo '<p id="error" class="erreur">' . $_GET['erreurTitre'] . '</p>';
                            } ?>
                        </div>
                        <div class="entreeTypeRecette">
                            <label for="typeRecette1"><span class="material-symbols-rounded edit">edit_square</span>Type de la recette</label>
                            <select name="typeRecette" id="typeRecette1">
                                <?php choixTypeRecette(); ?>
                            </select>
                            <?php if (isset($_GET['erreurType'])) {
                                echo '<p id="error" class="erreur">' . $_GET['erreurType'] . '</p>';
                            } ?>
                        </div>
                        <div class="entreeImageRecette">
                            <label class="labelSolide"><span class="material-symbols-rounded">image</span>Ajouter une image de la recette</label>
                            <input type="file" name="imageRecette" id="imageRecette1" accept=".jpg, .jpeg, .png" class="inputInvisible">
                            <div class="containerFauxInput">
                                <label for="imageRecette1" class="fauxBouton">Choisir une image (PNG, JPG)</label>
                                <div class="previewImage">
                                    <p>Aucune image sélectionnée</p>
                                </div>
                            </div>
                            <span class="supprimerImage" onclick="supprimerPhoto('supprimerImage')"><img src="./images/style/delete.svg"></span>
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

                        <div class="containerQuantiteMesureIngredient" id="containerIngredient1">
                            <div class="containerQuantite">
                                <label for="quantite1"><span class="material-symbols-rounded">kettle</span>Quantité</label>
                                <input type="text" maxlength="6" name="quantite1" id="quantite1" class="quantite" required>
                            </div>
                            <div class="containerUniteMesure">
                                <label for="uniteMesure1"><span class="material-symbols-rounded">grouped_bar_chart</span>Mesure</label>
                                <select name="uniteMesure1" id="uniteMesure1" class="unite" required><?php choixUniteMesure($resultatUniteMesure); ?></select>
                            </div>
                            <div class="containerIngredient">
                                <label for="ingredient1"><span class="material-symbols-rounded">scatter_plot</span>Ingrédient</label>
                                <input type="text" maxlength="35" name="ingredient1" id="ingredient1" class="ingredient" required>
                            </div>
                            <div class="containerQuantite">
                                <input type="text" maxlength="6" name="quantite2" id="quantite2" class="quantite" required>
                            </div>
                            <div class="containerUniteMesure">
                                <select name="uniteMesure2" id="uniteMesure2" class="unite" required><?php choixUniteMesure($resultatUniteMesure); ?></select>
                            </div>
                            <div class="containerIngredient">
                                <input type="text" maxlength="35" name="ingredient2" id="ingredient2" class="ingredient" required>
                            </div>
                            <div class="containerQuantite">
                                <input type="text" maxlength="6" name="quantite3" id="quantite3" class="quantite" required>
                            </div>
                            <div class="containerUniteMesure">
                                <select name="uniteMesure3" id="uniteMesure3" class="unite" required><?php choixUniteMesure($resultatUniteMesure); ?></select>
                            </div>
                            <div class="containerIngredient">
                                <input type="text" maxlength="35" name="ingredient3" id="ingredient3" class="ingredient" required>
                            </div>
                        </div>
                        <div id="inputContainer"></div>
                        <div class="containerAjoutIngredient">
                            <span id="ajoutIngredient1" class="ajoutIngredient" onclick="ajoutIngredient()"><span class="material-symbols-rounded">add_box</span>Ajouter un ingredient</span>
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
                            <label for="etapeRecette1"><span class="material-symbols-rounded edit">edit_square</span>Étape 1</label>
                            <textarea name="etapeRecette1" id="etapeRecette1" rows="5" minlength="5" maxlength="1000" required></textarea>
                        </div>
                        <div class="entreeEtapeRecette">
                            <label for="etapeRecette2"><span class="material-symbols-rounded edit">edit_square</span>Étape 2</label>
                            <textarea name="etapeRecette2" id="etapeRecette2" rows="5" minlength="5" maxlength="1000" required></textarea>
                        </div>
                        <div class="entreeEtapeRecette">
                            <label for="etapeRecette3"><span class="material-symbols-rounded edit">edit_square</span>Étape 3</label>
                            <textarea name="etapeRecette3" id="etapeRecette3" rows="5" minlength="5" maxlength="1000" required></textarea>
                        </div>
                        <div class="entreeEtapeRecette">
                            <label for="etapeRecette4"><span class="material-symbols-rounded edit">edit_square</span>Étape 4</label>
                            <textarea name="etapeRecette4" id="etapeRecette4" rows="5" maxlength="1000"></textarea>
                        </div class="entreeEtapeRecette">
                        <div id="textarea-container">
                        </div>
                        <div class="containerAjoutEtape">
                            <span id="ajoutEtape1" class="ajoutEtape" onclick="ajoutEtape()"><span class="material-symbols-rounded">add_box</span>Ajouter une étape</span>
                        </div>
                        <?php if (isset($_GET['erreurEtapes'])) {
                            echo '<p id="error" class="erreur">' . $_GET['erreurEtapes'] . '</p>';
                        } ?>
                        <div class="containerBoutonAjoutRecette">
                            <input type="submit" name="validationAjoutRecette" value="AJOUTER" class="boutonAjouter">
                            <input type="reset" value="ANNULER" class="boutonAnnuler">
                        </div>
                        <div id="error-message" class="erreur"></div>
                    </form>
                </div>
            </div>
        </section>
    </main>
<?php include_once('./elements/footer.php');
};  ?>