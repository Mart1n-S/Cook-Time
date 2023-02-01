<?php
require '../source/functions.php';

// Requête sql qui modifie la visibilité de la recette en bdd 

if (isset($_POST['recette_visibilite']) && isset($_POST['recette_id']) && !empty($_POST['recette_id'])) {

    $newValeurVisibilite = $_POST["recette_visibilite"];
    $recetteId = $_POST["recette_id"];

    $requeteSQLUpdateVisibilite = SGBDConnect()->prepare('UPDATE recettes SET RECETTE_VISIBILITE = :valeurVisibilite WHERE RECETTE_ID = :idRecette');
    $requeteSQLUpdateVisibilite->bindParam(":valeurVisibilite", $newValeurVisibilite);
    $requeteSQLUpdateVisibilite->bindParam(":idRecette", $recetteId);
    $requeteSQLUpdateVisibilite->execute();
}
