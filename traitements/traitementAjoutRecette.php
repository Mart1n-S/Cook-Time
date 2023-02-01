<?php
require '../source/functions.php';
initialisationSEssion();
$navigationEnCours = "profil";
$_SESSION['traitementModification'] = '';
if (!utilisateurConnecte()) {

    header('Location: ../index.php?action=accueil');
    exit();
} else {

    $ajoutRecette = $_POST['validationAjoutRecette'];
    if (isset($ajoutRecette) && !empty($ajoutRecette)) {

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
        // Traitement de l'image avec une fonction
        $nomImage = traitementImage2($imageRecette);

        //QUANTITE

        $tableauResultatQuantite = traitementQuantite($quantites);
        //On compte le nombre de valeur qui on été traité
        $tailleTableauQuantite = count($tableauResultatQuantite);

        //UNITE DE MESURE
        $tableauResultatUniteMesure = traitementUniteMesure($unites);

        $tailleTableauUniteMesure = count($tableauResultatUniteMesure);


        //INGREDIENT

        $tableauResultatIngredient = traitementIngredient($ingredients);

        $tailleTableauIngredient = count($tableauResultatIngredient);

        //VERIFICATION GLOBAL INGREDIENT
        //Grâce au compte des tableaux
        //On vérifie avec une fonction qu'il y à autant de quantité, de mesure et d'ingrédient et qu'il y a minimum 3 ingrédients 
        traitementGlobalIngredient($tailleTableauQuantite, $tailleTableauUniteMesure, $tailleTableauIngredient);

        //ETAPES

        $tableauResultatEtapes = traitementEtapes($etapes);

        //Ajout en base de données
        //Ajout dans la table recettes et on récupère le dernier id de la recette ajouté
        $recetteID = ajoutTableRecette($titre, $type, $nomImage, $_SESSION['id']);

        //Ajout dans la table étapes
        $resultatInsertionEtape = ajoutTableEtape($recetteID, $tableauResultatEtapes);

        //Ajout dans la table ingredients
        $ingredientID = ajoutTableIngredient($tableauResultatIngredient);

        //Ajout dans la tale Ingredient_Recette
        $resultatInsertionRecetteIngredient = ajoutTableRecetteIngredient($recetteID, $ingredientID, $tableauResultatQuantite, $tableauResultatUniteMesure);

        if ($recetteID == true && $resultatInsertionEtape == true && $resultatInsertionRecetteIngredient == true) {
            $_SESSION['succesAjout'] = true;
            header('Location: ../index.php?action=validation-ajout-recette');
            exit();
        } else {
            header('Location: ../index.php?action=ajouter-une-recette');
            exit();
        }
    } else {
        header('Location: ../index.php?action=accueil');
        exit();
    }
}
