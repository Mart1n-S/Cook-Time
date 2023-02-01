<?php
require '../source/functions.php';
$search = $_GET['search'];
$search = strip_tags(trim($search));
$search =  '%' . $search . '%';
$typeRecette = 'Dessert';
if (isset($search) && $search !== '%%') {
    $requeteSQLRecettesPublic = SGBDConnect()->prepare('SELECT RECETTE_ID, RECETTE_TYPE, RECETTE_TITRE, RECETTE_PHOTO, RECETTE_VISIBILITE, concat(USER_PRENOM,\' \',USER_NOM) AS AUTEUR 
    FROM recettes
    INNER JOIN users 
    on USER_ID = USER_ID_RECETTE 
    WHERE RECETTE_TYPE = :typeRecette
    AND RECETTE_TITRE IS NOT NULL
    AND RECETTE_PHOTO IS NOT NULL
    AND  RECETTE_VISIBILITE = 1
    AND RECETTE_TITRE LIKE :recherche
    ORDER BY RECETTE_TITRE');
    $requeteSQLRecettesPublic->bindParam(":typeRecette", $typeRecette);
    $requeteSQLRecettesPublic->bindParam(":recherche", $search);
    $requeteSQLRecettesPublic->execute();

    $resultatRequeteSQLRecettesPublic = $requeteSQLRecettesPublic->fetchAll(PDO::FETCH_ASSOC);
    if ($resultatRequeteSQLRecettesPublic == '' || $resultatRequeteSQLRecettesPublic == NULL) {
        echo '<ol>'
            . '<li class="resultatRecherche">AUCUN RÉSULTAT</li>'
            . '</ol>';
    } else {
        echo '<ol>';
        foreach ($resultatRequeteSQLRecettesPublic as $row) {

            echo '<li class="resultatRecherche" onclick="goRecette.call(this)"><a href="index.php?action=recettes-desserts&recette=' . $row['RECETTE_TITRE'] . '">' . $row['RECETTE_TITRE'] . '</a></li>';
        }
        echo '</ol>';
    }
}
