<?php
require '../source/functions.php';
initialisationSEssion();
$navigationEnCours = "profil";

$_SESSION['traitementModification'] = 'traitementModification';


if (!utilisateurConnecte()) {

    header('Location: ../index.php?action=accueil');
    exit();
} else {

    $modifierRecette = $_POST['validationModifierRecette'];
    if (isset($modifierRecette) && !empty($modifierRecette)) {

        // On récupère les données
        // On utilise la fonction trim() pour s'assurer que la valeur n'est pas vide et isset pour vérifier que la variable existe bien 
        $titreRecette = trim($_POST['titreRecette']);
        // Si la variable n'existe pas on l'initialise à vide/NULL uniquement pour les champs qui accepte la valeur NULL dans la base de données
        $typeRecette = isset($_POST['typeRecette']) ? $_POST['typeRecette'] : '';
        $imageRecette = isset($_FILES['imageRecette']) ? $_FILES['imageRecette'] : '';
        $quantites = array(
            trim(isset($_POST['quantite1']) ? $_POST['quantite1'] : ''), trim(isset($_POST['quantite2']) ? $_POST['quantite2'] : ''), trim(isset($_POST['quantite3']) ? $_POST['quantite3'] : ''), trim(isset($_POST['quantite4']) ? $_POST['quantite4'] : ''), trim(isset($_POST['quantite5']) ? $_POST['quantite5'] : ''), trim(isset($_POST['quantite6']) ? $_POST['quantite6'] : ''), trim(isset($_POST['quantite7']) ? $_POST['quantite7'] : ''), trim(isset($_POST['quantite8']) ? $_POST['quantite8'] : ''),
            trim(isset($_POST['quantite9']) ? $_POST['quantite9'] : ''), trim(isset($_POST['quantite10']) ? $_POST['quantite10'] : ''), trim(isset($_POST['quantite11']) ? $_POST['quantite11'] : ''), trim(isset($_POST['quantite12']) ? $_POST['quantite12'] : ''), trim(isset($_POST['quantite13']) ? $_POST['quantite13'] : ''), trim(isset($_POST['quantite14']) ? $_POST['quantite14'] : ''), trim(isset($_POST['quantite15']) ? $_POST['quantite15'] : '')
        );
        $unites = array(
            trim(isset($_POST['uniteMesure1']) ? $_POST['uniteMesure1'] : ''), trim(isset($_POST['uniteMesure2']) ? $_POST['uniteMesure2'] : ''), trim(isset($_POST['uniteMesure3']) ? $_POST['uniteMesure3'] : ''), trim(isset($_POST['uniteMesure4']) ? $_POST['uniteMesure4'] : ''), trim(isset($_POST['uniteMesure5']) ? $_POST['uniteMesure5'] : ''), trim(isset($_POST['uniteMesure6']) ? $_POST['uniteMesure6'] : ''), trim(isset($_POST['uniteMesure7']) ? $_POST['uniteMesure7'] : ''), trim(isset($_POST['uniteMesure8']) ? $_POST['uniteMesure8'] : ''),
            trim(isset($_POST['uniteMesure9']) ? $_POST['uniteMesure9'] : ''), trim(isset($_POST['uniteMesure10']) ? $_POST['uniteMesure10'] : ''), trim(isset($_POST['uniteMesure11']) ? $_POST['uniteMesure11'] : ''), trim(isset($_POST['uniteMesure12']) ? $_POST['uniteMesure12'] : ''), trim(isset($_POST['uniteMesure13']) ? $_POST['uniteMesure13'] : ''), trim(isset($_POST['uniteMesure14']) ? $_POST['uniteMesure14'] : ''), trim(isset($_POST['uniteMesure15']) ? $_POST['uniteMesure15'] : '')
        );
        $ingredients = array(
            trim(isset($_POST['ingredient1']) ? $_POST['ingredient1'] : ''), trim(isset($_POST['ingredient2']) ? $_POST['ingredient2'] : ''), trim(isset($_POST['ingredient3']) ? $_POST['ingredient3'] : ''), trim(isset($_POST['ingredient4']) ? $_POST['ingredient4'] : ''), trim(isset($_POST['ingredient5']) ? $_POST['ingredient5'] : ''), trim(isset($_POST['ingredient6']) ? $_POST['ingredient6'] : ''), trim(isset($_POST['ingredient7']) ? $_POST['ingredient7'] : ''), trim(isset($_POST['ingredient8']) ? $_POST['ingredient8'] : ''),
            trim(isset($_POST['ingredient9']) ? $_POST['ingredient9'] : ''), trim(isset($_POST['ingredient10']) ? $_POST['ingredient10'] : ''), trim(isset($_POST['ingredient11']) ? $_POST['ingredient11'] : ''), trim(isset($_POST['ingredient12']) ? $_POST['ingredient12'] : ''), trim(isset($_POST['ingredient13']) ? $_POST['ingredient13'] : ''), trim(isset($_POST['ingredient14']) ? $_POST['ingredient14'] : ''), trim(isset($_POST['ingredient15']) ? $_POST['ingredient15'] : '')
        );
        $etapes = array(
            trim(isset($_POST['etapeRecette1']) ? $_POST['etapeRecette1'] : ''), trim(isset($_POST['etapeRecette2']) ? $_POST['etapeRecette2'] : ''), trim(isset($_POST['etapeRecette3']) ? $_POST['etapeRecette3'] : ''), trim(isset($_POST['etapeRecette4']) ? $_POST['etapeRecette4'] : ''), trim(isset($_POST['etapeRecette5']) ? $_POST['etapeRecette5'] : ''), trim(isset($_POST['etapeRecette6']) ? $_POST['etapeRecette6'] : ''), trim(isset($_POST['etapeRecette7']) ? $_POST['etapeRecette7'] : ''), trim(isset($_POST['etapeRecette8']) ? $_POST['etapeRecette8'] : ''),
            trim(isset($_POST['etapeRecette9']) ? $_POST['etapeRecette9'] : ''), trim(isset($_POST['etapeRecette10']) ? $_POST['etapeRecette10'] : '')
        );


        //TITRE
        // Traitement des données avec des fonctions
        $titre = traitementTitre($titreRecette);

        //TYPE

        $type = traitementType($typeRecette);

        //IMAGE

        if ($imageRecette['size'] !== 0) {
            $nomImage = traitementImage2($imageRecette);
        }

        if (isset($_POST['oldimage']) && $imageRecette['size'] !== 0 || isset($_POST['oldimage']) && $_SESSION['oldImage'] === 'supprimerOldImage' || !isset($_POST['previewImageModifier']) && isset($_POST['oldimage']) && $imageRecette['size'] == 0) {

            unlink('../uploads/imagesRecettes/' . $_SESSION['photoName']);
            $value = NULL;
            $updatePhoto = updatePhoto($_SESSION['idRecette'], $value, $_SESSION['id']);
            $_SESSION['photoName'] = '';
            $_SESSION['oldImage'] = '';
        }

        //QUANTITE

        $tableauResultatQuantite = traitementQuantite($quantites);

        $tailleTableauQuantite = count($tableauResultatQuantite);

        //UNITE DE MESURE
        $tableauResultatUniteMesure = traitementUniteMesure($unites);

        $tailleTableauUniteMesure = count($tableauResultatUniteMesure);


        //INGREDIENT
        //On initialise un index pour supprimer les variables inutiles
        $tableauResultatIngredient = traitementIngredient($ingredients);

        $tailleTableauIngredient = count($tableauResultatIngredient);

        //VERIFICATION GLOBAL INGREDIENT
        //Grâce au compte des tableaux
        //On vérifie avec une fonction qu'il y à autant de quantité, de mesure et d'ingrédient et qu'il y a minimum 3 ingrédients 
        traitementGlobalIngredient($tailleTableauQuantite, $tailleTableauUniteMesure, $tailleTableauIngredient);

        //ETAPES

        $tableauResultatEtapes = traitementEtapes($etapes);

        //Ajout en base de données

        if (isset($nomImage)) {
            $resultatModificationRecette = modifierTableRecette($titre, $type, $nomImage, $_SESSION['id'], $_SESSION['idRecette']);
        } else {
            $resultatModificationRecette = modifierTableRecette2($titre, $type, $_SESSION['id'], $_SESSION['idRecette']);
        }


        $etapesExistantes = getEtapes($_SESSION['idRecette']);
        // On boucle sur les étapes de la recette qui sont déjà en base de données
        foreach ($etapesExistantes as $index => $etape) {
            // Si l'étape qui provient de modifierRecette existe à la même position
            if (isset($tableauResultatEtapes[$index])) {
                // On vérifie si elle est différente de l'étape qui provient de la base de données
                if ($etape['ETAPE_INSTRUCTION'] !== $tableauResultatEtapes[$index]) {
                    // Si elle est différente on met à jour l'étape dans la base de données
                    $resultatModifierEtape = modifierTableEtape($_SESSION['idRecette'], $tableauResultatEtapes[$index], $etape['ETAPE_ID']);
                }
            }
            // Si l'étape qui provient de modifierRecette n'existe pas à cette position, cela veut dire que l'étape à été supprimé
            else {
                // Donc on supprime l'étape dans la base de données
                $resultatDeleteEtape = deleteEtape($_SESSION['idRecette'], $etape['ETAPE_ID']);
            }
        }

        // On boucle sur les étapes de la recette qui viennent de modifierRecette
        foreach ($tableauResultatEtapes as $index => $etape) {
            // Si l'index de l'étape qui vient de modifierRecette n'existe pas en base de données
            if (!isset($etapesExistantes[$index])) {
                // On ajout l'étape dans la base de données
                $resultatAjoutEtape = ajoutNouvelleEtape($_SESSION['idRecette'], $etape);
            }
        }


        $ingredientID = ajoutTableIngredient($tableauResultatIngredient);


        //On récupère les informations des ingrediens (quantité, id ingrédient, id unité de mesure) de la recette
        $ingredientGlobale = getIngredientGlobale($_SESSION['idRecette']);

        // On boucle sur les informations de l'ingrédient globale de la recette qui sont déjà en base de données
        foreach ($ingredientGlobale as $index => $ligne) {
            // Si l'ingrédient globale qui provient de modifierRecette existe à la même position
            if (isset($tableauResultatQuantite[$index]) && isset($tableauResultatUniteMesure[$index]) && isset($ingredientID[$index])) {
                // On vérifie s'il y à des différences avec l'ingrédient globale qui provient de la base de données
                if ($ligne['RECETTE_INGREDIENT_QUANTITE'] !== $tableauResultatQuantite[$index] || $ligne['RECETTE_INGREDIENT_ID_MESURE'] !== $tableauResultatUniteMesure[$index] || $ligne['RECETTE_INGREDIENT_ID_INGREDIENT'] !== $ingredientID[$index]) {
                    // S'il y à une différence on met à jour l'ingrédient globale dans la base de données
                    $resultatModifierIngredient = modifierTableIngredientRecette($_SESSION['idRecette'], $ligne['RECETTE_INGREDIENT_ID'], $ingredientID[$index], $tableauResultatQuantite[$index], $tableauResultatUniteMesure[$index]);
                }
            }
            // Si l'ingrédient globale qui provient de modifierRecette n'existe pas à cette position, cela veut dire que l'ingrédient globale à été supprimé
            else {
                // Donc on supprime l'ingredient globale dans la base de données
                $resultatDeleteIngredientGlobale = deleteIngredientGlobale($_SESSION['idRecette'], $ligne['RECETTE_INGREDIENT_ID']);
            }
        }

        // On boucle sur les informations de l'ingrédient globale de modifierRecette
        foreach ($ingredientID as $index => $ingredient) {
            // Si l'index de l'ingrédient qui vient de modifierRecette n'existe pas en base de données
            if (!isset($ingredientGlobale[$index])) {
                // On ajout l'ingrédient globale dans la base de données
                $resultatIngredientGlobale = ajoutNouvelleIngredientGlobale($_SESSION['idRecette'], $ingredient, $tableauResultatQuantite[$index], $tableauResultatUniteMesure[$index]);
            }
        }

        if ($resultatModificationRecette == true || $resultatModifierEtape == true || $resultatModifierIngredient == true || $resultatDeleteEtape == true || $resultatAjoutEtape == true || $resultatDeleteIngredientGlobale == true || $resultatIngredientGlobale == true || $updatePhoto == true) {
            $_SESSION['succesModificationRecette'] = true;
            $_SESSION['traitementModification'] = '';
            $_SESSION['modificationRecetteEnCours'] = '';
            header('Location: ../index.php?action=validation-ajout-recette');
            exit();
        } else {
            header('Location: ../index.php?action=modifier-ma-recette');
            exit();
        }
    } else {
        header('Location: ../index.php?action=accueil');
        exit();
    }
}
