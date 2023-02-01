<?php
require './source/functions.php';
initialisationSEssion();
$gourmandises = "gourmandises";
$navigationEnCours = "recettes";
$navigationEnCoursRecette = "gourmandises";
?>

<?php include_once('./elements/header.php'); ?>

<main>
    <?php if (utilisateurConnecte()) { ?>
        <div class="menuNavigationRecettes">
            <h2>Recettes</h2>
            <?php include_once('./elements/navigationRecettes.php'); ?>

            <div class="containerRecherche">
                <form method="get" class="formulaireRecherche">
                    <input type="hidden" name="action" value="recettes-gourmandises">
                    <input type="text" class="recherche" id="search_text" name="recette" placeholder="Rechercher...">
                    <input type="submit" class="validationRecherche" value="Rechercher">

                </form>
                <div class="afficherRecherche" id="result">

                </div>
            </div>
        </div>
        <?php
        // Récupération de la recherche s'il y en a une
        $search = isset($_GET['recette']) ? $_GET['recette'] : '';
        $search = strip_tags(trim($search));

        // Requête pour récupérer les recettes
        if (isset($search) && $search !== '' || isset($page) && isset($search) && $search !== '') {

            $typeCount = 'Gourmandise';
            $tableauCount = countRecettes2($typeCount, $search);

            $pageParDefaut = $_GET["page"] ?? 1;
            if (!filter_var($pageParDefaut, FILTER_VALIDATE_INT) || $pageParDefaut <= 0) {
                header("Location: index.php?action=recettes-gourmandises&page=1");
            }

            $page = (int)$pageParDefaut;

            if (empty($page)) {
                $page = 1;
            }
            $recetteParPage = 5;
            $nombrePage = ceil($tableauCount[0]["COUNT"] / $recetteParPage);
            $debut = ($page - 1) * $recetteParPage;


            $resultatRecettesPublic = recettesGourmandisesPublic2($debut, $recetteParPage, $search);



            if ($resultatRecettesPublic == '' || $resultatRecettesPublic == NULL) {

                echo  '<div class="cardErreurRecette">
                                <div class="containerMessageErreurRecette">
                                    <div class="nonConnecteEmote">
                                    <span class="material-symbols-rounded">search_off</span>
                                    </div>
                                        <p>AUCUN RÉSULTAT</p>
                                </div>
                                </div>';
            } else {
                $indexCommentaire = 1;
                foreach ($resultatRecettesPublic as $recette) {
                    $idRecettePublic = $recette['RECETTE_ID'];
                    $typeRecettePublic = $recette['RECETTE_TYPE'];
                    $titreRecettePublic = $recette['RECETTE_TITRE'];
                    $photoRecettePublic = $recette['RECETTE_PHOTO'];
                    $visibiliteRecettePublic = $recette['RECETTE_VISIBILITE'];
                    $auteurRecette = $recette['AUTEUR'];

                    if ($typeRecettePublic === 'Gourmandise' && $visibiliteRecettePublic === 1) { ?>

                        <section class="sectionRecettePublic">
                            <div class="cardRecettePublic">

                                <div class="containerRecettePublic">
                                    <h3><?php echo strip_tags($titreRecettePublic); ?></h3>
                                    <div class="containerPhotoRecettePublic">
                                        <?php
                                        if (isset($photoRecettePublic) && $photoRecettePublic != NULL && file_exists("./uploads/imagesRecettes/" . strip_tags($photoRecettePublic))) { ?>
                                            <img src="./uploads/imagesRecettes/<?php echo strip_tags($photoRecettePublic) ?>" class="photoRecettePublic" alt="photo de recette">
                                        <?php } else { ?>
                                            <img src="./images/photoRecetteDefaut/recetteDefaut.svg" class="photoRecetteDefaut" alt="photo de recette par defaut">
                                        <?php };
                                        echo '<p> Auteur de la recette : ' . strip_tags($auteurRecette) . '</p>';
                                        ?>
                                    </div>
                                    <h3>INGREDIENTS </h3>
                                    <ul>
                                        <?php $resultatIngredientRecettePublic = ingredientRecettePublic($idRecettePublic);
                                        foreach ($resultatIngredientRecettePublic as $unIngredient) {

                                            echo "<li>" . strip_tags($unIngredient['RECETTE_INGREDIENT_QUANTITE']) . ' ' . strip_tags($unIngredient['UNITE_MESURE_NOM']) . ' ' . strip_tags($unIngredient['INGREDIENT_NOM']) . "</li>";
                                        }
                                        ?>
                                    </ul>
                                    <h3>ÉTAPES<span class="material-symbols-rounded">restaurant_menu</span></h3>
                                    <div class="etapesRecettePublic">
                                        <?php $resultatEtapesMaRecette = etapesRecettePublic($idRecettePublic);
                                        $numeroEtape = 1;
                                        $numeroLigneResultatEtape = 1;
                                        foreach ($resultatEtapesMaRecette as $uneEtape) {
                                            while ($numeroEtape <= $numeroLigneResultatEtape) {
                                                echo "<h4> Etapes " . $numeroEtape . "</h4>";
                                                $numeroEtape++;
                                            }
                                            $numeroLigneResultatEtape++;
                                            echo "<p>" . strip_tags($uneEtape['ETAPE_INSTRUCTION'])  . "</p>";
                                        }
                                        ?>
                                    </div>
                                    <?php $resultatCommentaire = commentaireRecette($idRecettePublic);
                                    if (isset($resultatCommentaire) && $resultatCommentaire != '' && isset($resultatCommentaire[0]['COMMENTAIRE_ID']) != '') {
                                    ?>

                                        <h4>COMMENTAIRES</h4>
                                        <p class="nombreCommentaire">Nombre de commentaire(s) : <?php echo count($resultatCommentaire); ?></p>
                                        <div class="containerCarousel" id="containerCarousel<?php echo $indexCommentaire; ?>">
                                            <?php foreach ($resultatCommentaire as $index => $commentaire) { ?>


                                                <div class="commentaire">
                                                    <div class="containerInfosUser">
                                                        <div class="photoProfil">
                                                            <?php
                                                            if (isset($commentaire['USER_PHOTO']) && $commentaire['USER_PHOTO'] != 'NULL' && file_exists("./uploads/photoProfil/" . strip_tags($commentaire['USER_PHOTO']))) { ?>
                                                                <img src="./uploads/photoProfil/<?php echo strip_tags($commentaire['USER_PHOTO']) ?>" class="photoUtilisateur" alt="photo de profil">
                                                            <?php } else { ?>
                                                                <img src="./images/photoProfilDefaut/defaut.svg" alt="photo de profil par defaut">
                                                            <?php }; ?>
                                                        </div>
                                                        <div class="containerNameDate">
                                                            <div class="infoUser"><?php echo $commentaire['AUTEUR'] ?></div>
                                                            <div class="dateCommentaire"><?php echo $commentaire['COMMENTAIRE_DATE'] ?></div>
                                                        </div>
                                                    </div>

                                                    <div class="contenuCommentaire"><?php echo $commentaire['COMMENTAIRE_CONTENU'] ?></div>
                                                </div>

                                            <?php  } ?>
                                        </div>
                                        <div class="inputCommentaire" id="inputCommentaire<?php echo $indexCommentaire; ?>">
                                            <form action="./traitements/traitementCommentaire.php" method="post" id="formCommentaire">
                                                <label for="entreCommentaire">Votre commentaire : <span class="material-symbols-rounded">chat</span></label>
                                                <textarea id="entreCommentaire" name="commentaireContenu" rows="5" maxlength="1000" class="commentaireContenu"></textarea>
                                                <input type="hidden" name="hiddenID" value="<?php echo $idRecettePublic; ?>">
                                                <input type="hidden" name="typeRecette" value="<?php echo $navigationEnCoursRecette; ?>">
                                                <input type="hidden" name="recetteTitre" value="<?php echo $titreRecettePublic; ?>">
                                                <div class="containerValidationCommentaire">
                                                    <input type="submit" class="validationCommentaire" name="validationCommentaire" value="Commenter">
                                                </div>

                                            </form>
                                        </div>
                                        <div class="ajoutCommentaire">
                                            <span class="boutonAjoutCommentaire" onclick="commenter(<?php echo $indexCommentaire; ?>)">Ajouter un commentaire <span class="material-symbols-rounded">chat</span></span>
                                        </div>
                                        <?php if (isset($_GET['erreurCommentaire'])) {
                                            echo '<p id="error" class="erreur">' . $_GET['erreurCommentaire'] . '</p>';
                                        } ?>
                                        <?php if (isset($resultatCommentaire) && count($resultatCommentaire) > 1) { ?>
                                            <div class="boutonScrollCommentaire">
                                                <button id="scrollLeft" onclick="changeP(<?php echo $indexCommentaire; ?>)"><span class="material-symbols-rounded">skip_previous</span>Précédent</button>
                                                <button id="scrollRight" onclick="change(<?php echo $indexCommentaire; ?>)">Suivant<span class="material-symbols-rounded">skip_next</span></button>
                                            </div>

                                        <?php }
                                    } else {  ?>
                                        <div class="inputCommentaire" id="inputCommentaire<?php echo $indexCommentaire; ?>">
                                            <form action="./traitements/traitementCommentaire.php" method="post" id="formCommentaire">
                                                <label for="entreCommentaire">Votre commentaire : <span class="material-symbols-rounded">chat</span></label>
                                                <textarea id="entreCommentaire" name="commentaireContenu" rows="5" maxlength="1000" class="commentaireContenu"></textarea>
                                                <input type="hidden" name="hiddenID" value="<?php echo $idRecettePublic; ?>">
                                                <input type="hidden" name="typeRecette" value="<?php echo $navigationEnCoursRecette; ?>">
                                                <input type="hidden" name="recetteTitre" value="<?php echo $titreRecettePublic; ?>">
                                                <div class="containerValidationCommentaire">
                                                    <input type="submit" class="validationCommentaire" name="validationCommentaire" value="Commenter">
                                                </div>

                                            </form>
                                        </div>
                                        <div class="ajoutCommentaire">
                                            <span class="boutonAjoutCommentaire" onclick="commenter(<?php echo $indexCommentaire; ?>)">Ajouter un commentaire <span class="material-symbols-rounded">chat</span></span>
                                        </div>
                                        <?php if (isset($_GET['erreurCommentaire'])) {
                                            echo '<p id="error" class="erreur">' . $_GET['erreurCommentaire'] . '</p>';
                                        } ?>
                                    <?php } ?>
                                </div>

                            </div>
                            </div>
                            </div>
                        </section>
                <?php   }
                    $indexCommentaire++;
                } ?>

                <div class="pagination">
                    <?php
                    if ($page > 1) {
                        echo '<a href="index.php?action=recettes-gourmandises&page=' . ($page - 1) . '&recette=' . $search . '" ><<</a>';
                    }
                    if ($nombrePage > 1) {
                        echo '<div class="checkPage"><form method="get">
                                      <input type="number" class="navPage" name="page" min="1" max="' .  $nombrePage . '" value="' .  $page . '">
                                      <input type="hidden" name="action" value="recettes-desserts">
                                      <input type="hidden" name="recette" value="' . $search . '">

                                      <span>/ ' . $nombrePage . '</span>
                                      <input type="submit" class="boutonOK" value="ok">
                                      </form></div>';
                    } else {

                        if ($nombrePage == 0) {
                            $nombrePage = 1;
                        }
                        echo '<div class="checkPage2">
                                      <p class="navPage2">' . $nombrePage . '</p><span> / ' . $nombrePage . '</span>
                                      
                                      </div>';
                    }
                    if ($page < $nombrePage) {
                        echo '<a href="index.php?action=recettes-gourmandises&page=' . ($page + 1) . '&recette=' . $search . '"  >>></a>';
                    }

                    ?>
                </div>
                <?php    }
        } else {

            $typeCount = 'Gourmandise';
            $tableauCount = countRecettes($typeCount);

            $pageParDefaut = $_GET["page"] ?? 1;
            if (!filter_var($pageParDefaut, FILTER_VALIDATE_INT) || $pageParDefaut <= 0) {
                header("Location: index.php?action=recettes-gourmandises&page=1");
            }

            $page = (int)$pageParDefaut;

            if (empty($page)) {
                $page = 1;
            }
            $recetteParPage = 5;
            $nombrePage = ceil($tableauCount[0]["COUNT"] / $recetteParPage);
            $debut = ($page - 1) * $recetteParPage;

            $resultatRecettesPublic = recettesGourmandisesPublic($debut, $recetteParPage);
            if (count($resultatRecettesPublic) == 0) {
                header("Location: index.php?action=recettes-gourmandises&page=1");
            }
            $indexCommentaire = 1;
            foreach ($resultatRecettesPublic as $recette) {
                $idRecettePublic = $recette['RECETTE_ID'];
                $typeRecettePublic = $recette['RECETTE_TYPE'];
                $titreRecettePublic = $recette['RECETTE_TITRE'];
                $photoRecettePublic = $recette['RECETTE_PHOTO'];
                $visibiliteRecettePublic = $recette['RECETTE_VISIBILITE'];
                $auteurRecette = $recette['AUTEUR'];

                if ($typeRecettePublic === 'Gourmandise' && $visibiliteRecettePublic === 1) { ?>

                    <section class="sectionRecettePublic">
                        <div class="cardRecettePublic">
                            <div class="containerRecettePublic">
                                <h3><?php echo strip_tags($titreRecettePublic); ?></h3>
                                <div class="containerPhotoRecettePublic">
                                    <?php
                                    if (isset($photoRecettePublic) && $photoRecettePublic != NULL && file_exists("./uploads/imagesRecettes/" . strip_tags($photoRecettePublic))) { ?>
                                        <img src="./uploads/imagesRecettes/<?php echo strip_tags($photoRecettePublic) ?>" class="photoRecettePublic" alt="photo de recette">
                                    <?php } else { ?>
                                        <img src="./images/photoRecetteDefaut/recetteDefaut.svg" class="photoRecetteDefaut" alt="photo de recette par defaut">
                                    <?php };
                                    echo '<p> Auteur de la recette : ' . strip_tags($auteurRecette) . '</p>';
                                    ?>
                                </div>
                                <h3>INGREDIENTS </h3>
                                <ul>
                                    <?php $resultatIngredientRecettePublic = ingredientRecettePublic($idRecettePublic);
                                    foreach ($resultatIngredientRecettePublic as $unIngredient) {

                                        echo "<li>" . strip_tags($unIngredient['RECETTE_INGREDIENT_QUANTITE']) . ' ' . strip_tags($unIngredient['UNITE_MESURE_NOM']) . ' ' . strip_tags($unIngredient['INGREDIENT_NOM']) . "</li>";
                                    }
                                    ?>
                                </ul>
                                <h3>ÉTAPES<span class="material-symbols-rounded">restaurant_menu</span></h3>
                                <div class="etapesRecettePublic">
                                    <?php $resultatEtapesMaRecette = etapesRecettePublic($idRecettePublic);
                                    $numeroEtape = 1;
                                    $numeroLigneResultatEtape = 1;
                                    foreach ($resultatEtapesMaRecette as $uneEtape) {
                                        while ($numeroEtape <= $numeroLigneResultatEtape) {
                                            echo "<h4> Etapes " . $numeroEtape . "</h4>";
                                            $numeroEtape++;
                                        }
                                        $numeroLigneResultatEtape++;
                                        echo "<p>" . strip_tags($uneEtape['ETAPE_INSTRUCTION'])  . "</p>";
                                    }
                                    ?>
                                </div>
                                <?php $resultatCommentaire = commentaireRecette($idRecettePublic);
                                if (isset($resultatCommentaire) && $resultatCommentaire != '' && isset($resultatCommentaire[0]['COMMENTAIRE_ID']) != '') {
                                ?>

                                    <h4>COMMENTAIRES</h4>
                                    <p class="nombreCommentaire">Nombre de commentaire(s) : <?php echo count($resultatCommentaire); ?></p>
                                    <div class="containerCarousel" id="containerCarousel<?php echo $indexCommentaire; ?>">
                                        <?php foreach ($resultatCommentaire as $index => $commentaire) { ?>


                                            <div class="commentaire">
                                                <div class="containerInfosUser">
                                                    <div class="photoProfil">
                                                        <?php
                                                        if (isset($commentaire['USER_PHOTO']) && $commentaire['USER_PHOTO'] != 'NULL' && file_exists("./uploads/photoProfil/" . strip_tags($commentaire['USER_PHOTO']))) { ?>
                                                            <img src="./uploads/photoProfil/<?php echo strip_tags($commentaire['USER_PHOTO']) ?>" class="photoUtilisateur" alt="photo de profil">
                                                        <?php } else { ?>
                                                            <img src="./images/photoProfilDefaut/defaut.svg" alt="photo de profil par defaut">
                                                        <?php }; ?>
                                                    </div>
                                                    <div class="containerNameDate">
                                                        <div class="infoUser"><?php echo $commentaire['AUTEUR'] ?></div>
                                                        <div class="dateCommentaire"><?php echo $commentaire['COMMENTAIRE_DATE'] ?></div>
                                                    </div>
                                                </div>

                                                <div class="contenuCommentaire"><?php echo $commentaire['COMMENTAIRE_CONTENU'] ?></div>
                                            </div>

                                        <?php  } ?>
                                    </div>
                                    <div class="inputCommentaire" id="inputCommentaire<?php echo $indexCommentaire; ?>">
                                        <form action="./traitements/traitementCommentaire.php" method="post" id="formCommentaire">
                                            <label for="entreCommentaire">Votre commentaire : <span class="material-symbols-rounded">chat</span></label>
                                            <textarea id="entreCommentaire" name="commentaireContenu" rows="5" maxlength="1000" class="commentaireContenu"></textarea>
                                            <input type="hidden" name="hiddenID" value="<?php echo $idRecettePublic; ?>">
                                            <input type="hidden" name="typeRecette" value="<?php echo $navigationEnCoursRecette; ?>">
                                            <input type="hidden" name="recetteTitre" value="<?php echo $titreRecettePublic; ?>">
                                            <div class="containerValidationCommentaire">
                                                <input type="submit" class="validationCommentaire" name="validationCommentaire" value="Commenter">
                                            </div>

                                        </form>
                                    </div>
                                    <div class="ajoutCommentaire">
                                        <span class="boutonAjoutCommentaire" onclick="commenter(<?php echo $indexCommentaire; ?>)">Ajouter un commentaire <span class="material-symbols-rounded">chat</span></span>
                                    </div>
                                    <?php if (isset($_GET['erreurCommentaire'])) {
                                        echo '<p id="error" class="erreur">' . $_GET['erreurCommentaire'] . '</p>';
                                    } ?>
                                    <?php if (isset($resultatCommentaire) && count($resultatCommentaire) > 1) { ?>
                                        <div class="boutonScrollCommentaire">
                                            <button id="scrollLeft" onclick="changeP(<?php echo $indexCommentaire; ?>)"><span class="material-symbols-rounded">skip_previous</span>Précédent</button>
                                            <button id="scrollRight" onclick="change(<?php echo $indexCommentaire; ?>)">Suivant<span class="material-symbols-rounded">skip_next</span></button>
                                        </div>

                                    <?php } ?>
                            </div>
                        </div>


                    <?php } else { ?>
                        <div class="inputCommentaire" id="inputCommentaire<?php echo $indexCommentaire; ?>">
                            <form action="./traitements/traitementCommentaire.php" method="post" id="formCommentaire">
                                <label for="entreCommentaire">Votre commentaire : <span class="material-symbols-rounded">chat</span></label>
                                <textarea id="entreCommentaire" name="commentaireContenu" rows="5" maxlength="1000" class="commentaireContenu"></textarea>
                                <input type="hidden" name="hiddenID" value="<?php echo $idRecettePublic; ?>">
                                <input type="hidden" name="typeRecette" value="<?php echo $navigationEnCoursRecette; ?>">
                                <input type="hidden" name="recetteTitre" value="<?php echo $titreRecettePublic; ?>">
                                <div class="containerValidationCommentaire">
                                    <input type="submit" class="validationCommentaire" name="validationCommentaire" value="Commenter">
                                </div>

                            </form>
                        </div>
                        <div class="ajoutCommentaire">
                            <span class="boutonAjoutCommentaire" onclick="commenter(<?php echo $indexCommentaire; ?>)">Ajouter un commentaire <span class="material-symbols-rounded">chat</span></span>
                        </div>
                        <?php if (isset($_GET['erreurCommentaire'])) {
                                        echo '<p id="error" class="erreur">' . $_GET['erreurCommentaire'] . '</p>';
                                    } ?>
                    <?php } ?>

                    </section>
            <?php   }
                $indexCommentaire = 1;
            } ?>

            <div class="pagination">
                <?php
                if ($page > 1) {
                    echo '<a href="index.php?action=recettes-gourmandises&page=' . ($page - 1) . '" ><<</a>';
                }
                echo '<div class="checkPage"><form method="get">
                <input type="hidden" name="action" value="recettes-desserts">
                <input type="number" class="navPage" name="page" min="1" max="' .  $nombrePage . '" value="' .  $page . '"><span>/ ' . $nombrePage . '</span>
                <input type="submit" class="boutonOK" value="ok">
            </form></div>';


                if ($page < $nombrePage) {
                    echo '<a href="index.php?action=recettes-gourmandises&page=' . ($page + 1) . '"  >>></a>';
                }

                ?>
            </div><?php
                }
            } else { ?>
        <section>
            <div class="cardErreurRecette">
                <h2>Vous n'êtes pas connecté</h2>
                <div class="containerMessageErreurRecette">
                    <div class="nonConnecteEmote">
                        <span class="material-symbols-rounded ">sensor_occupied</span>
                    </div>
                    <p>Pour accéder aux recettes et aux autres fonctionnalitées du site merci de vous connecter. <a href="index.php?action=authentification">Se connecter</a></p>
                </div>
            </div>
        </section>
    <?php }; ?>
</main>

<?php include_once('./elements/footer.php'); ?>