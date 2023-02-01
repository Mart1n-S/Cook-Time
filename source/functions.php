<?php

// Connexion à la base de données
// Dans le fichier bddConfiguration se trouve les éléments de configuration 
function SGBDConnect()
{
    require 'bddConfiguration.php';
    try {
        $connexionBaseDonees = new PDO($bddDNS, $bddUser, $bddMotDePasse, $options);
    } catch (PDOException $e) {
        echo 'Erreur !: ' . $e->getMessage() . '<br />';
        exit();
    }
    return $connexionBaseDonees;
};

// Démarrage d'une Session
function initialisationSEssion(): bool
{
    if (!session_id()) {
        session_start();
        session_regenerate_id();
        return true;
    }

    return false;
};

// Fermeture de Session
function fermetureSession(): void
{
    session_unset();
    session_destroy();
};

// Fonction qui permet de vérifier si l'utilisateur est connecté et donc possède un compte ce qui lui permet d'accéder aux recettes etc...
function utilisateurConnecte(): bool
{
    if (isset($_SESSION['id'])) {
        return true;
    } else {
        return false;
    }
};

function AllEmailUsers()
{
    $requeteSQLAllEmailUsers = SGBDConnect()->prepare('SELECT USER_EMAIL 
    FROM users');
    $requeteSQLAllEmailUsers->execute();

    $resultatRequeteSQLAllEmailUsers = $requeteSQLAllEmailUsers->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLAllEmailUsers;
}

function verificationDoublonEmail($emailForm, $resultatRequeteSQLAllEmailUsers)
{
    foreach ($resultatRequeteSQLAllEmailUsers as $email) {
        if ($email['USER_EMAIL'] == $emailForm) {
            return true;
        }
    }
    return false;
}

function  connexionUtilisateur($email)
{
    $requeteSQLConnexion = SGBDConnect()->prepare('SELECT *, YEAR(CURDATE()) - YEAR(USER_AGE) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), "-", MONTH(USER_AGE), "-", DAY(USER_AGE)) ,"%Y-%c-%e") > CURDATE(), 1, 0) AS AGE  
    FROM users 
    WHERE USER_EMAIL = :email');
    $requeteSQLConnexion->bindParam(":email", $email);
    $requeteSQLConnexion->execute();

    $resultatRequeteSQLConnexion = $requeteSQLConnexion->fetch(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLConnexion;
}

// Fonction permettant de sélectionner un âge entre 10 et 99 ans via une boucle lors d'un select
function choixAge()
{
    $age = 99;
    echo '<option value="">--Sélectionner votre âge--</option>';
    for ($indexAge = 10; $indexAge <= $age; $indexAge++) {
        echo '<option value=' . $indexAge . '>' . $indexAge . ' ans </option>';
    };
};
// Requete recette profil

function nombreRecettes()
{
    $requeteSQLNombreRecette = SGBDConnect()->prepare('SELECT COUNT(RECETTE_ID) AS NOMBRE_RECETTES FROM recettes WHERE USER_ID_RECETTE = :idUser ');
    $requeteSQLNombreRecette->bindParam(":idUser", $_SESSION["id"]);
    $requeteSQLNombreRecette->execute();

    $resultatRequeteSQLNombreRecette = $requeteSQLNombreRecette->fetch(PDO::FETCH_ASSOC);

    $resultatLNombreRecette = $resultatRequeteSQLNombreRecette['NOMBRE_RECETTES'];
    return $resultatLNombreRecette;
}

function infosRecettes()
{
    $requeteSQLInfosMesRecettes = SGBDConnect()->prepare('SELECT RECETTE_ID, RECETTE_TITRE, RECETTE_TYPE, RECETTE_PHOTO, RECETTE_VISIBILITE 
    FROM recettes 
    WHERE USER_ID_RECETTE = :idUser');
    $requeteSQLInfosMesRecettes->bindParam(":idUser", $_SESSION["id"]);
    $requeteSQLInfosMesRecettes->execute();

    $resultatRequeteSQLInfosMesRecettes = $requeteSQLInfosMesRecettes->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLInfosMesRecettes;
}

function ingredientMaRecette($idRecetteIngredient)
{
    $requeteSQLIngredientMaRecette = SGBDConnect()->prepare('SELECT RECETTE_INGREDIENT_QUANTITE, UNITE_MESURE_NOM, INGREDIENT_NOM 
    FROM recettes INNER JOIN recettes_ingredients ON RECETTE_ID = RECETTE_INGREDIENT_ID_RECETTE 
    INNER JOIN ingredients ON  RECETTE_INGREDIENT_ID_INGREDIENT = INGREDIENT_ID 
    INNER JOIN unites_mesure ON RECETTE_INGREDIENT_ID_MESURE = UNITE_MESURE_ID
    WHERE INGREDIENT_ID = RECETTE_INGREDIENT_ID_INGREDIENT 
    AND RECETTE_ID = :idRecette');
    $requeteSQLIngredientMaRecette->bindParam(":idRecette", $idRecetteIngredient);
    $requeteSQLIngredientMaRecette->execute();

    $resultatRequeteSQLIngredientMaRecette = $requeteSQLIngredientMaRecette->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLIngredientMaRecette;
}

function etapesMaRecette($idRecetteEtapes)
{
    $requeteSQLEtapesMaRecette = SGBDConnect()->prepare('SELECT ETAPE_INSTRUCTION 
    FROM etapes 
    INNER JOIN recettes ON RECETTE_ID_ETAPE = RECETTE_ID 
    WHERE RECETTE_ID_ETAPE = :idRecette');
    $requeteSQLEtapesMaRecette->bindParam(":idRecette", $idRecetteEtapes);
    $requeteSQLEtapesMaRecette->execute();

    $resultatRequeteSQLEtapesMaRecette = $requeteSQLEtapesMaRecette->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLEtapesMaRecette;
}

function existanceRecette($idRecette)
{
    $requeteSQLExistanceRecette = SGBDConnect()->prepare('SELECT *
    FROM recettes  
    WHERE RECETTE_ID = :idRecette 
    AND USER_ID_RECETTE = :idUser');
    $requeteSQLExistanceRecette->bindParam(":idRecette", $idRecette);
    $requeteSQLExistanceRecette->bindParam(":idUser", $_SESSION["id"]);
    $requeteSQLExistanceRecette->execute();

    $resultatRequeteSQLExistanceRecette = $requeteSQLExistanceRecette->fetch(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLExistanceRecette;
}

function supprimerRecette($idRecette)
{
    $requeteSQLSupprimerCommentaireRecette = SGBDConnect()->prepare('DELETE FROM commentaires  
    WHERE COMMENTAIRE_ID_RECETTE = :idRecette');
    $requeteSQLSupprimerCommentaireRecette->bindParam(":idRecette", $idRecette);
    $requeteSQLSupprimerCommentaireRecette->execute();


    $requeteSQLSupprimerIngredientRecette = SGBDConnect()->prepare('DELETE FROM recettes_ingredients  
    WHERE RECETTE_INGREDIENT_ID_RECETTE = :idRecette');
    $requeteSQLSupprimerIngredientRecette->bindParam(":idRecette", $idRecette);
    $requeteSQLSupprimerIngredientRecette->execute();

    $requeteSQLSupprimerEtapeRecette = SGBDConnect()->prepare('DELETE FROM etapes  
    WHERE RECETTE_ID_ETAPE = :idRecette');
    $requeteSQLSupprimerEtapeRecette->bindParam(":idRecette", $idRecette);
    $requeteSQLSupprimerEtapeRecette->execute();

    $requeteSQLSupprimerRecette = SGBDConnect()->prepare('DELETE FROM recettes  
    WHERE RECETTE_ID = :idRecette
    AND USER_ID_RECETTE = :idUser');
    $requeteSQLSupprimerRecette->bindParam(":idRecette", $idRecette);
    $requeteSQLSupprimerRecette->bindParam(":idUser", $_SESSION["id"]);
    $requeteSQLSupprimerRecette->execute();
}


// Requete recette public

function recettesEntreesPublic($debut, $recetteParPage)
{
    $typeRecette = 'Entrée';
    $requeteSQLRecettesPublic = SGBDConnect()->prepare('SELECT RECETTE_ID, RECETTE_TYPE, RECETTE_TITRE, RECETTE_PHOTO, RECETTE_VISIBILITE, concat(USER_PRENOM,\' \',USER_NOM) AS AUTEUR
    FROM recettes 
    INNER JOIN users 
    on USER_ID = USER_ID_RECETTE
    WHERE RECETTE_TYPE = :typeRecette
    AND RECETTE_TITRE IS NOT NULL
    AND RECETTE_PHOTO IS NOT NULL
    ORDER BY RECETTE_TITRE
    LIMIT :debut, :elementParPage');
    $requeteSQLRecettesPublic->bindParam(":typeRecette", $typeRecette);
    $requeteSQLRecettesPublic->bindParam(":debut", $debut);
    $requeteSQLRecettesPublic->bindParam(":elementParPage", $recetteParPage);
    $requeteSQLRecettesPublic->execute();

    $resultatRequeteSQLRecettesPublic = $requeteSQLRecettesPublic->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLRecettesPublic;
}

function recettesPlatsPublic($debut, $recetteParPage)
{
    $typeRecette = 'Plat';
    $requeteSQLRecettesPublic = SGBDConnect()->prepare('SELECT RECETTE_ID, RECETTE_TYPE, RECETTE_TITRE, RECETTE_PHOTO, RECETTE_VISIBILITE, concat(USER_PRENOM,\' \',USER_NOM) AS AUTEUR 
    FROM recettes 
    INNER JOIN users 
    on USER_ID = USER_ID_RECETTE
    WHERE RECETTE_TYPE = :typeRecette
    AND RECETTE_TITRE IS NOT NULL
    AND RECETTE_PHOTO IS NOT NULL
    ORDER BY RECETTE_TITRE
    LIMIT :debut, :elementParPage');
    $requeteSQLRecettesPublic->bindParam(":typeRecette", $typeRecette);
    $requeteSQLRecettesPublic->bindParam(":debut", $debut);
    $requeteSQLRecettesPublic->bindParam(":elementParPage", $recetteParPage);
    $requeteSQLRecettesPublic->execute();

    $resultatRequeteSQLRecettesPublic = $requeteSQLRecettesPublic->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLRecettesPublic;
}

function recettesDessertsPublic($debut, $recetteParPage)
{
    $typeRecette = 'Dessert';
    $requeteSQLRecettesPublic = SGBDConnect()->prepare('SELECT RECETTE_ID, RECETTE_TYPE, RECETTE_TITRE, RECETTE_PHOTO, RECETTE_VISIBILITE, concat(USER_PRENOM,\' \',USER_NOM) AS AUTEUR 
    FROM recettes
    INNER JOIN users 
    on USER_ID = USER_ID_RECETTE 
    WHERE RECETTE_TYPE = :typeRecette
    AND RECETTE_TITRE IS NOT NULL
    AND RECETTE_PHOTO IS NOT NULL
    ORDER BY RECETTE_TITRE
    LIMIT :debut, :elementParPage');
    $requeteSQLRecettesPublic->bindParam(":typeRecette", $typeRecette);
    $requeteSQLRecettesPublic->bindParam(":debut", $debut);
    $requeteSQLRecettesPublic->bindParam(":elementParPage", $recetteParPage);
    $requeteSQLRecettesPublic->execute();

    $resultatRequeteSQLRecettesPublic = $requeteSQLRecettesPublic->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLRecettesPublic;
}

function recettesGourmandisesPublic($debut, $recetteParPage)
{
    $typeRecette = 'Gourmandise';
    $requeteSQLRecettesPublic = SGBDConnect()->prepare('SELECT RECETTE_ID, RECETTE_TYPE, RECETTE_TITRE, RECETTE_PHOTO, RECETTE_VISIBILITE, concat(USER_PRENOM,\' \',USER_NOM) AS AUTEUR 
    FROM recettes
    INNER JOIN users 
    on USER_ID = USER_ID_RECETTE 
    WHERE RECETTE_TYPE = :typeRecette
    AND RECETTE_TITRE IS NOT NULL
    AND RECETTE_PHOTO IS NOT NULL
    ORDER BY RECETTE_TITRE
    LIMIT :debut, :elementParPage');
    $requeteSQLRecettesPublic->bindParam(":typeRecette", $typeRecette);
    $requeteSQLRecettesPublic->bindParam(":debut", $debut);
    $requeteSQLRecettesPublic->bindParam(":elementParPage", $recetteParPage);
    $requeteSQLRecettesPublic->execute();

    $resultatRequeteSQLRecettesPublic = $requeteSQLRecettesPublic->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLRecettesPublic;
}

function ingredientRecettePublic($idRecetteIngredient)
{
    $requeteSQLIngredientRecettePublic = SGBDConnect()->prepare('SELECT RECETTE_INGREDIENT_QUANTITE, UNITE_MESURE_NOM, INGREDIENT_NOM 
    FROM recettes INNER JOIN recettes_ingredients ON RECETTE_ID = RECETTE_INGREDIENT_ID_RECETTE 
    INNER JOIN ingredients ON  RECETTE_INGREDIENT_ID_INGREDIENT = INGREDIENT_ID 
    INNER JOIN unites_mesure ON RECETTE_INGREDIENT_ID_MESURE = UNITE_MESURE_ID
    WHERE INGREDIENT_ID = RECETTE_INGREDIENT_ID_INGREDIENT 
    AND RECETTE_ID = :idRecette');
    $requeteSQLIngredientRecettePublic->bindParam(":idRecette", $idRecetteIngredient);
    $requeteSQLIngredientRecettePublic->execute();

    $resultatRequeteSQLIngredientRecettePublic = $requeteSQLIngredientRecettePublic->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLIngredientRecettePublic;
}


function etapesRecettePublic($idRecetteEtapes)
{
    $requeteSQLEtapesRecettePublic = SGBDConnect()->prepare('SELECT ETAPE_INSTRUCTION 
    FROM etapes 
    INNER JOIN recettes ON RECETTE_ID_ETAPE = RECETTE_ID 
    WHERE RECETTE_ID_ETAPE = :idRecette');
    $requeteSQLEtapesRecettePublic->bindParam(":idRecette", $idRecetteEtapes);
    $requeteSQLEtapesRecettePublic->execute();

    $resultatRequeteSQLEtapesRecettePublic = $requeteSQLEtapesRecettePublic->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLEtapesRecettePublic;
}

// AJOUT RECETTE
function choixTypeRecette()
{
    $typeRecette = ['Entrée', 'Plat', 'Dessert', 'Gourmandise'];
    $typeRecetteValue = ['Entrée', 'Plat', 'Dessert', 'Gourmandise'];
    $indexTypeRecette = count($typeRecette);
    echo '<option value="">--Sélectionner un type de recette--</option>';
    for ($index = 0; $index < $indexTypeRecette; $index++) {
        echo '<option value=' . $typeRecetteValue[$index] . '>' . $typeRecette[$index] . '</option>';
    };
};

function getUniteMesure()
{
    $requeteSQLUniteMesure = SGBDConnect()->prepare('SELECT * 
    FROM unites_mesure
    ORDER BY UNITE_MESURE_NOM');
    $requeteSQLUniteMesure->execute();

    $resultatRequeteSQLUniteMesure = $requeteSQLUniteMesure->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLUniteMesure;
}

function choixUniteMesure($resultatRequeteSQLUniteMesure)
{
    echo '<option value="">--Mesure--</option>';
    foreach ($resultatRequeteSQLUniteMesure as $uniteMesure) {
        echo '<option value="' . $uniteMesure['UNITE_MESURE_ID'] . '">' . $uniteMesure['UNITE_MESURE_NOM'] . '</option>';
    }
}

function verificationUniteMesure($uniteMesureForm, $resultatRequeteSQLUniteMesure)
{
    foreach ($resultatRequeteSQLUniteMesure as $uniteMesure) {
        if ($uniteMesure['UNITE_MESURE_ID'] == $uniteMesureForm) {
            return true;
        }
    }
    return false;
}

//Traitement ajout recette

//TITRE
function traitementTitre($titre)
{
    // On vérifie si le titre de la recette existe et qu'il n'est pas vide 
    if (isset($titre) && !empty($titre)  && strlen($titre) <= 128) {
        $titreRecette = strip_tags($titre);
    } else {
        $erreurTitreRecette = "Le titre de la recette n'est pas valide";
        if (!isset($_SESSION['traitementModification']) || $_SESSION['traitementModification'] !== 'traitementModification') {
            header('Location: ../index.php?action=ajouter-une-recette&erreurTitre=' . $erreurTitreRecette);
            exit();
        } else {
            header('Location: ../index.php?action=modifier-ma-recette&erreurTitre=' . $erreurTitreRecette);
            exit();
        }
    }
    return $titreRecette;
}

//TYPE
function traitementType($typeRecette)
{
    // On vérifie que la valeur du type de la recette est strictement égale à celle autorisé
    if ($typeRecette === 'Entrée' || $typeRecette === 'Plat' || $typeRecette === 'Dessert' || $typeRecette === 'Gourmandise' || $typeRecette === '') {
        if ($typeRecette === '') {
            $typeRecette = NULL;
        } else {
            $typeRecette = trim(strip_tags($typeRecette));
        }
    } else {
        $erreurTypeRecette = "Le type de la recette n'est pas valide";
        if (!isset($_SESSION['traitementModification']) || $_SESSION['traitementModification'] !== 'traitementModification') {
            header('Location: ../index.php?action=ajouter-une-recette&erreurType=' . $erreurTypeRecette);
            exit();
        } else {
            header('Location: ../index.php?action=modifier-ma-recette&erreurType=' . $erreurTypeRecette);
            exit();
        }
    }
    return $typeRecette;
}

//IMAGE
function traitementImage($image)
{
    // Si la variable imageRecette existe mais que aucune image n'a été mise on initialise à NULL
    if (isset($image) && $image['size'] === 0) {
        $image = trim(strip_tags($image['name']));
        $image = NULL;
        $nomImage = $image;
    } elseif (isset($image) && $image['error'] === 0) {
        // on vérifie sa taille
        if (($image['size'] <= 5242880)) {
            // On test si l'extension est autorisée
            $informationImage = pathinfo($image['name']);
            $extension = $informationImage['extension'];
            $extensionAutorise = ['jpg', 'jpeg', 'png'];

            if (in_array($extension, $extensionAutorise)) {
                // On peut valider le fichier et le stocker définitivement si l'extension est autorisé
                $nomImage = rand() . uniqid() . '.' . $extension;
                $transfert =  move_uploaded_file($image['tmp_name'], '../uploads/photoProfil/' . $nomImage);
                // Si un erreur est survenue on stop le reste du programme et on préviens l'utilisateur
                if ($transfert == false) {
                    $erreurUploadImage = "L'upload de l'image n'a pas fonctionné";
                    if (!isset($_SESSION['traitementModificationProfil']) || $_SESSION['traitementModificationProfil'] != 'traitementModificationProfil') {
                        header('Location: ../index.php?action=inscrivez-vous&erreurUploadImage=' . $erreurUploadImage);
                        exit();
                    } else {
                        header('Location: ../index.php?action=modifier-mon-profil&erreurUploadImage=' . $erreurUploadImage);
                        exit();
                    }
                }
            } else {
                $erreurFormatImage = "Le format de l'image n'est pas valide. Uniquement JPG / JPEG / PNG";
                if (!isset($_SESSION['traitementModificationProfil']) || $_SESSION['traitementModificationProfil'] != 'traitementModificationProfil') {
                    header('Location: ../index.php?action=inscrivez-vous&erreurFormatImage=' . $erreurFormatImage);
                    exit();
                } else {
                    header('Location: ../index.php?action=modifier-mon-profil&erreurFormatImage=' . $erreurFormatImage);
                    exit();
                }
            }
        } else {
            $erreurTailleImage = "La taille de l'image n'est pas valide (Maximum 5Mo)";
            if (!isset($_SESSION['traitementModificationProfil']) || $_SESSION['traitementModificationProfil'] != 'traitementModificationProfil') {
                header('Location: ../index.php?action=inscrivez-vous&erreurTailleImage=' . $erreurTailleImage);
                exit();
            } else {
                header('Location: ../index.php?action=modifier-mon-profil&erreurTailleImage=' . $erreurTailleImage);
                exit();
            }
        }
    } else {
        $erreurImage = "L'image n'est pas valide";
        if (!isset($_SESSION['traitementModificationProfil']) || $_SESSION['traitementModificationProfil'] != 'traitementModificationProfil') {
            header('Location: ../index.php?action=inscrivez-vous&erreurImage=' . $erreurImage);
            exit();
        } else {
            header('Location: ../index.php?action=modifier-mon-profil&erreurImage=' . $erreurImage);
            exit();
        }
    }
    return $nomImage;
}

function traitementImage2($image)
{
    // Si la variable imageRecette existe mais que aucune image n'a été mise on initialise à NULL
    if (isset($image) && $image['size'] === 0) {
        $image = trim(strip_tags($image['name']));
        $image = NULL;
        $nomImage = $image;
    } elseif (isset($image) && $image['error'] === 0) {
        // on vérifie sa taille
        if (($image['size'] <= 5242880)) {
            // On test si l'extension est autorisée
            $informationImage = pathinfo($image['name']);
            $extension = $informationImage['extension'];
            $extensionAutorise = ['jpg', 'jpeg', 'png'];

            if (in_array($extension, $extensionAutorise)) {
                // On peut valider le fichier et le stocker définitivement si l'extension est autorisé
                $nomImage = rand() . uniqid() . '.' . $extension;
                $transfert =  move_uploaded_file($image['tmp_name'], '../uploads/imagesRecettes/' . $nomImage);
                // Si un erreur est survenue on stop le reste du programme et on préviens l'utilisateur
                if ($transfert == false) {
                    $erreurUploadImage = "L'upload de l'image n'a pas fonctionné";
                    if (!isset($_SESSION['traitementModification']) || $_SESSION['traitementModification'] != 'traitementModification') {
                        header('Location: ../index.php?action=ajouter-une-recette&erreurUploadImage=' . $erreurUploadImage);
                        exit();
                    } else {
                        header('Location: ../index.php?action=modifier-ma-recette&erreurUploadImage=' . $erreurUploadImage);
                        exit();
                    }
                }
            } else {
                $erreurFormatImage = "Le format de l'image n'est pas valide. Uniquement JPG / JPEG / PNG";
                if ((!isset($_SESSION['traitementModification']) || $_SESSION['traitementModification'] != 'traitementModification') && (!isset($_SESSION['traitementModificationProfil']) && $_SESSION['traitementModificationProfil'] != 'traitementModificationProfil')) {
                    header('Location: ../index.php?action=ajouter-une-recette&erreurFormatImage=' . $erreurFormatImage);
                    exit();
                } else {
                    header('Location: ../index.php?action=modifier-ma-recette&erreurFormatImage=' . $erreurFormatImage);
                    exit();
                }
            }
        } else {
            $erreurTailleImage = "La taille de l'image n'est pas valide (Maximum 5Mo)";
            if ((!isset($_SESSION['traitementModification']) || $_SESSION['traitementModification'] != 'traitementModification') && (!isset($_SESSION['traitementModificationProfil']) && $_SESSION['traitementModificationProfil'] != 'traitementModificationProfil')) {
                header('Location: ../index.php?action=ajouter-une-recette&erreurTailleImage=' . $erreurTailleImage);
                exit();
            } else {
                header('Location: ../index.php?action=modifier-ma-recette&erreurTailleImage=' . $erreurTailleImage);
                exit();
            }
        }
    } else {
        $erreurImage = "L'image n'est pas valide";
        if ((!isset($_SESSION['traitementModification']) || $_SESSION['traitementModification'] != 'traitementModification') && (!isset($_SESSION['traitementModificationProfil']) && $_SESSION['traitementModificationProfil'] != 'traitementModificationProfil')) {
            header('Location: ../index.php?action=ajouter-une-recette&erreurImage=' . $erreurImage);
            exit();
        } else {
            header('Location: ../index.php?action=modifier-ma-recette&erreurImage=' . $erreurImage);
            exit();
        }
    }
    return $nomImage;
}

//QUANTITE
function traitementQuantite($quantites)
{
    //On initialise un index pour supprimer les variables inutiles
    //On initialise un tableau vide pour récupérer toutes les valeurs qui on été traité
    $indexTableauQuantite = 0;
    $tableauResultatQuantite = array();
    $quantiteQuart = ['1/2', '1/3', '1/4', '1/5', '1/6', '1/7', '1/8', '1/9', '2/3', '2/4', '3/4', '4/3'];
    foreach ($quantites as $quantite) {
        if (is_numeric($quantite) || in_array($quantite, $quantiteQuart) && strlen($quantite) >= 0 && strlen($quantite) <= 6) {
            if ($quantite === '0') {
                $quantite = '';
                $tableauResultatQuantite[] = $quantite;
                $indexTableauQuantite++;
            } else {
                $quantite = strip_tags($quantite);
                $tableauResultatQuantite[] = $quantite;
                $indexTableauQuantite++;
            }
        } elseif ($quantite === '') {
            unset($quantites[$indexTableauQuantite]);
            $indexTableauQuantite++;
        } else {
            $erreurQuantiteRecette = "Nombre de caractères pour quantité entre 0 et 6.<br>(1/2, 1/3, 1/4,..., 4/3 sont acceptés)<br> Si pas de quantité précise merci de mettre 0.";
            if (!isset($_SESSION['traitementModification']) || $_SESSION['traitementModification'] !== 'traitementModification') {
                header('Location: ../index.php?action=ajouter-une-recette&erreurQuantite=' . $erreurQuantiteRecette);
                exit();
            } else {
                header('Location: ../index.php?action=modifier-ma-recette&erreurQuantite=' . $erreurQuantiteRecette);
                exit();
            }
        }
    }
    return $tableauResultatQuantite;
}

//UNITE DE MESURE
function traitementUniteMesure($unites)
{
    //On récupère le résultat des unité de mesure pour vérifier si elle est valide
    //On initialise un index pour supprimer les variables inutiles
    //On initialise un tableau vide pour récupérer toutes les valeurs qui on été traité
    $resultatUniteMesure = getUniteMesure();
    $indexTableauUniteMesure = 0;
    $tableauResultatUniteMesure = array();
    foreach ($unites as $unite) {

        if (verificationUniteMesure($unite, $resultatUniteMesure)) {

            $tableauResultatUniteMesure[] = $unite;
            $indexTableauUniteMesure++;
        } elseif ($unite === '') {
            unset($unites[$unite]);
            $indexTableauUniteMesure++;
        } else {
            $erreurUniteMesure = "L'unité de mesure n'est pas valide.";
            if (!isset($_SESSION['traitementModification']) || $_SESSION['traitementModification'] !== 'traitementModification') {
                header('Location: ../index.php?action=ajouter-une-recette&erreurUniteMesure=' . $erreurUniteMesure);
                exit();
            } else {
                header('Location: ../index.php?action=modifier-ma-recette&erreurUniteMesure=' . $erreurUniteMesure);
                exit();
            }
        }
    }
    return $tableauResultatUniteMesure;
}

//INGREDIENT
function traitementIngredient($ingredients)
{
    //On initialise un index pour supprimer les variables inutiles
    //On initialise un tableau vide pour récupérer toutes les valeurs qui on été traité
    $indexTableauIngredient = 0;
    $tableauResultatIngredient = array();
    foreach ($ingredients as $ingredient) {
        if (strlen($ingredient) > 2 && strlen($ingredient) <= 35 && is_string($ingredient)) {

            $ingredient = strip_tags($ingredient);
            $tableauResultatIngredient[] = $ingredient;
            $indexTableauIngredient++;
        } elseif ($ingredient === '') {
            unset($ingredients[$indexTableauIngredient]);
            $indexTableauIngredient++;
        } else {
            $erreurIngredient = "L'ingrédient n'est pas valide.";
            if (!isset($_SESSION['traitementModification']) || $_SESSION['traitementModification'] !== 'traitementModification') {
                header('Location: ../index.php?action=ajouter-une-recette&erreurIngredient=' . $erreurIngredient);
                exit();
            } else {
                header('Location: ../index.php?action=modifier-ma-recette&erreurIngredient=' . $erreurIngredient);
                exit();
            }
        }
    }
    return $tableauResultatIngredient;
}

//Vérification de l'ensemble ingrédient (quantité,uniteMesure,ingrédient)
function traitementGlobalIngredient($tailleTableauQuantite, $tailleTableauUniteMesure, $tailleTableauIngredient)
{
    if ($tailleTableauQuantite !== $tailleTableauUniteMesure || $tailleTableauQuantite !== $tailleTableauIngredient || $tailleTableauQuantite < 3) {
        $erreurTailleTableau = "Champs Quantité/Mesure/Ingredient invalides";
        if (!isset($_SESSION['traitementModification']) || $_SESSION['traitementModification'] !== 'traitementModification') {
            header('Location: ../index.php?action=ajouter-une-recette&erreurTailleTableau=' . $erreurTailleTableau);
            exit();
        } else {
            header('Location: ../index.php?action=modifier-ma-recette&erreurTailleTableau=' . $erreurTailleTableau);
            exit();
        }
    }
}

//ETAPES
function traitementEtapes($etapes)
{
    //On initialise un index pour supprimer les variables inutiles
    //On initialise un tableau vide pour récupérer toutes les valeurs qui on été traité
    $indexTableauEtapes = 0;
    $tableauResultatEtapes = array();
    foreach ($etapes as $etape) {
        //On vérifie que au moins les 3 premières étapes ont été remplis car obligatoire
        if ($indexTableauEtapes === 0 || $indexTableauEtapes === 1 || $indexTableauEtapes === 2) {
            if (!empty($etape) && !is_null($etape) && strlen($etape) >= 5 && strlen($etape) <= 1000) {
                $etape = strip_tags($etape);
                $tableauResultatEtapes[] = $etape;
                $indexTableauEtapes++;
            } elseif (strlen($etape) < 5 || strlen($etape) > 1000) {
                $erreurEtapes = "Merci de remplir au minimum les 3 premières étapes.<br>(5 - 1000 caractères par étape) ";
                if (!isset($_SESSION['traitementModification']) || $_SESSION['traitementModification'] !== 'traitementModification') {
                    header('Location: ../index.php?action=ajouter-une-recette&erreurEtapes=' . $erreurEtapes);
                    exit();
                } else {
                    header('Location: ../index.php?action=modifier-ma-recette&erreurEtapes=' . $erreurEtapes);
                    exit();
                }
            } else {
                $erreurEtapes = "Merci de remplir au minimum les 3 premières étapes.";
                if (!isset($_SESSION['traitementModification']) || $_SESSION['traitementModification'] !== 'traitementModification') {
                    header('Location: ../index.php?action=ajouter-une-recette&erreurEtapes=' . $erreurEtapes);
                    exit();
                } else {
                    header('Location: ../index.php?action=modifier-ma-recette&erreurEtapes=' . $erreurEtapes);
                    exit();
                }
            }
            //On vérifie le reste ici
        } elseif (!empty($etape) && strlen($etape) >= 5 && strlen($etape) <= 1000 && is_string($etape)) {
            $etape = strip_tags($etape);
            $tableauResultatEtapes[] = $etape;
            $indexTableauEtapes++;
        } elseif ($etape === '') {
            unset($etapes[$indexTableauEtapes]);
            $indexTableauEtapes++;
        } else {
            $indexTableauEtapes++;
            $erreurEtapes = "L'étape " .  $indexTableauEtapes . " n'est pas valide.";
            if (!isset($_SESSION['traitementModification']) || $_SESSION['traitementModification'] !== 'traitementModification') {
                header('Location: ../index.php?action=ajouter-une-recette&erreurEtapes=' . $erreurEtapes);
                exit();
            } else {
                header('Location: ../index.php?action=modifier-ma-recette&erreurEtapes=' . $erreurEtapes);
                exit();
            }
        }
    }
    return $tableauResultatEtapes;
}

//INSERTION RECETTE
//Table Recette
function ajoutTableRecette($titre, $type, $imageNom, $userID)
{
    $connexionBDD = SGBDConnect();
    $requeteSQLRecette = $connexionBDD->prepare('INSERT INTO recettes (RECETTE_TITRE, RECETTE_TYPE, RECETTE_PHOTO, USER_ID_RECETTE) 
    VALUES (:titre, :typeRecette, :imageNom, :idUser)');
    $requeteSQLRecette->bindParam(":titre", $titre);
    $requeteSQLRecette->bindParam(":typeRecette", $type);
    $requeteSQLRecette->bindParam(":imageNom", $imageNom);
    $requeteSQLRecette->bindParam(":idUser", $userID);
    $requeteSQLRecette->execute();
    $dernierIDRecette = $connexionBDD->lastInsertId();
    return $dernierIDRecette;
}

//Table Etapes
function ajoutTableEtape($recetteID, $etapes)
{
    foreach ($etapes as $etape) {
        $requeteSQLEtape = SGBDConnect()->prepare('INSERT INTO etapes (RECETTE_ID_ETAPE, ETAPE_INSTRUCTION) 
    VALUES (:recetteID, :etapeInstruction)');
        $requeteSQLEtape->bindParam(":recetteID", $recetteID);
        $requeteSQLEtape->bindParam(":etapeInstruction", $etape);
        $requeteSQLEtape->execute();
    }
    if ($requeteSQLEtape == true) {
        return true;
    }
}

//Table Ingredient
function ajoutTableIngredient($ingredients)
{
    $ingredientsID = array();
    $connexionBDD = SGBDConnect();
    foreach ($ingredients as $ingredient) {
        //Vérifie si l'ingrédient existe déjà dans la table
        $requeteSQLIngredient =  $connexionBDD->prepare("SELECT INGREDIENT_ID FROM ingredients WHERE INGREDIENT_NOM = :ingredient");
        $requeteSQLIngredient->bindParam(':ingredient', $ingredient);
        $requeteSQLIngredient->execute();
        $resultatComparaison = $requeteSQLIngredient->fetch();

        if ($resultatComparaison) {
            //L'ingrédient existe déjà, récupère son ID
            $ingredientsID[] = $resultatComparaison['INGREDIENT_ID'];
        } else {
            //L'ingrédient n'existe pas, l'ajoute à la table
            $requeteSQLIngredient = $connexionBDD->prepare("INSERT INTO ingredients (INGREDIENT_NOM) VALUES (LOWER(:ingredient))");
            $requeteSQLIngredient->bindParam(':ingredient', $ingredient);
            $requeteSQLIngredient->execute();
            $ingredientsID[] = $connexionBDD->lastInsertId();
        }
    }
    return $ingredientsID;
}

//Table Recette_Ingredient
function ajoutTableRecetteIngredient($idRecette, $idIngredient, $quantites, $idUniteMesure)
{
    foreach (array_map(null, $idIngredient, $quantites, $idUniteMesure) as list($ingredient, $quantite, $unite)) {
        $requeteSQLRecetteIngredient = SGBDConnect()->prepare('INSERT INTO recettes_ingredients (RECETTE_INGREDIENT_ID_RECETTE, RECETTE_INGREDIENT_ID_INGREDIENT, RECETTE_INGREDIENT_QUANTITE, RECETTE_INGREDIENT_ID_MESURE) 
    VALUES (:idRecette, :idIngredient, :quantite, :idUniteMesure)');
        $requeteSQLRecetteIngredient->bindParam(":idRecette", $idRecette);
        $requeteSQLRecetteIngredient->bindParam(":idIngredient", $ingredient);
        $requeteSQLRecetteIngredient->bindParam(":quantite", $quantite);
        $requeteSQLRecetteIngredient->bindParam(":idUniteMesure", $unite);
        $requeteSQLRecetteIngredient->execute();
    }
    if ($requeteSQLRecetteIngredient == true) {
        return true;
    }
}

/////////////////MODIFIER RECETTE////////////////////////

function modifierChoixTypeRecette($type)
{
    $typeRecette = ['Entrée', 'Plat', 'Dessert', 'Gourmandise'];
    $typeRecetteValue = ['Entrée', 'Plat', 'Dessert', 'Gourmandise'];
    $indexTypeRecette = count($typeRecette);
    echo '<option value="">--Sélectionner un type de recette--</option>';
    for ($index = 0; $index < $indexTypeRecette; $index++) {
        if ($type == $typeRecetteValue[$index]) {
            echo '<option value=' . strip_tags($typeRecetteValue[$index]) . ' selected>' . strip_tags($typeRecette[$index]) . '</option>';
        } else {
            echo '<option value=' . strip_tags($typeRecetteValue[$index]) . '>' . strip_tags($typeRecette[$index]) . '</option>';
        }
    };
};

function getEtapes($idRecette)
{

    $requeteSQLEtapes = SGBDConnect()->prepare('SELECT * 
    FROM etapes 
    WHERE RECETTE_ID_ETAPE = :idRecette');
    $requeteSQLEtapes->bindParam(":idRecette", $idRecette);
    $requeteSQLEtapes->execute();

    $resultatRequeteSQLEtapes = $requeteSQLEtapes->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLEtapes;
}

function afficherEtapes($etapes, $countEtapes)
{
    foreach ($etapes as $etape) {

        echo '<div class="entreeEtapeRecette" id="modifierEtapeRecette' . $countEtapes . '">' . '<label for="modifierEtapeRecette' . $countEtapes . '"><span class="material-symbols-rounded edit">edit_square</span>Étape ' . $countEtapes . '</label>'
            . '<textarea name="etapeRecette' . $countEtapes . '" id="modifierEtapeRecette' . $countEtapes . '" rows="5" maxlength="1000">' . strip_tags($etape['ETAPE_INSTRUCTION']) . '</textarea>'
            . '<span class="supprimerEtape" onclick="supprimerEtape(`modifierEtapeRecette' . $countEtapes . '`)"><img src="./images/style/delete.svg"></span>'
            . '</div>';
        $countEtapes++;
    }
}


function modifierChoixUniteMesure($uniteMesureId, $uniteMesureNom)
{

    echo '<option value="">--Mesure--</option>';
    foreach ($uniteMesureId as $uniteMesure) {
        if ($uniteMesureNom == $uniteMesure['UNITE_MESURE_NOM']) {
            echo '<option value=' .  $uniteMesure['UNITE_MESURE_ID'] . ' selected>' . $uniteMesure['UNITE_MESURE_NOM'] . '</option>';
        } else {
            echo '<option value="' . $uniteMesure['UNITE_MESURE_ID'] . '">' . $uniteMesure['UNITE_MESURE_NOM'] . '</option>';
        }
    }
}

function modifierChoixUniteMesure2($uniteMesureId, $uniteMesureNom)
{
    $options = '<option value="">--Mesure--</option>';
    foreach ($uniteMesureId as $uniteMesure) {
        if ($uniteMesureNom == $uniteMesure['UNITE_MESURE_NOM']) {
            $options .= '<option value="' .  $uniteMesure['UNITE_MESURE_ID'] . '" selected>' . $uniteMesure['UNITE_MESURE_NOM'] . '</option>';
        } else {
            $options .= '<option value="' . $uniteMesure['UNITE_MESURE_ID'] . '">' . $uniteMesure['UNITE_MESURE_NOM'] . '</option>';
        }
    }
    return $options;
}

function afficherIngredients($ingredients,  $resultatUniteMesure)
{
    $count = 1;
    foreach ($ingredients as $index => $ingredient) {

        $countUp = $index + $count;

        echo '<div class="containerGlobale" id="containerGlobale' . $countUp . '">'
            . '<div class="containerQuantite" id="containerQuantite' . $countUp . '">'
            . '<input type="text" maxlength="6" name="quantite' . $countUp . '" id="modifierQuantite' . $countUp . '" class="quantite" value="' . strip_tags($ingredient['RECETTE_INGREDIENT_QUANTITE']) . '">'
            . '</div>'
            . '<div class="containerUniteMesure" id="containerUniteMesure' . $countUp . '">'
            . '<select name="uniteMesure' . $countUp . '" id="modifierUniteMesure' . $countUp . '" class="unite">' .  modifierChoixUniteMesure2($resultatUniteMesure, $ingredient["UNITE_MESURE_NOM"]) . '</select>'
            . '</div>'
            . '<div class="containerIngredient" id="containerIngredient' . $countUp . '">'
            . '<input type="text" maxlength="35" name="ingredient' . $countUp . '" id="modifierIngredient' . $countUp . '" class="ingredient" value="' . strip_tags($ingredient['INGREDIENT_NOM']) . '">'
            . '</div>'
            . '<span class="supprimerIngredient" onclick="supprimerIngredient(`containerGlobale' . $countUp . '`)"><img src="./images/style/delete.svg"></span>'
            . '</div>';
        $count++;
    }
}


//Mofier base de données recettes

// gestion de l'image
function updatePhoto($recetteID, $value, $userID)
{
    $requeteSQLUpdateModifier = SGBDConnect()->prepare('UPDATE recettes 
    SET RECETTE_PHOTO = :imageValue
    WHERE USER_ID_RECETTE = :idUser
    AND RECETTE_ID = :idRecette');
    $requeteSQLUpdateModifier->bindParam(":imageValue", $value);
    $requeteSQLUpdateModifier->bindParam(":idUser", $userID);
    $requeteSQLUpdateModifier->bindParam(":idRecette", $recetteID);
    $requeteSQLUpdateModifier->execute();

    if ($requeteSQLUpdateModifier == true) {
        return true;
    }
}
// Table Recette
function modifierTableRecette($titre, $type, $imageNom, $userID, $recetteID)
{

    $requeteSQLModiferRecette = SGBDConnect()->prepare('UPDATE recettes 
    SET RECETTE_TITRE = :titre, RECETTE_TYPE = :typeRecette, RECETTE_PHOTO = :imageNom
    WHERE USER_ID_RECETTE = :idUser
    AND RECETTE_ID = :idRecette');
    $requeteSQLModiferRecette->bindParam(":titre", $titre);
    $requeteSQLModiferRecette->bindParam(":typeRecette", $type);
    $requeteSQLModiferRecette->bindParam(":imageNom", $imageNom);
    $requeteSQLModiferRecette->bindParam(":idUser", $userID);
    $requeteSQLModiferRecette->bindParam(":idRecette", $recetteID);
    $requeteSQLModiferRecette->execute();

    if ($requeteSQLModiferRecette == true) {
        return true;
    }
}

// Table Recette sans image
function modifierTableRecette2($titre, $type, $userID, $recetteID)
{

    $requeteSQLModiferRecette2 = SGBDConnect()->prepare('UPDATE recettes 
    SET RECETTE_TITRE = :titre, RECETTE_TYPE = :typeRecette
    WHERE USER_ID_RECETTE = :idUser
    AND RECETTE_ID = :idRecette');
    $requeteSQLModiferRecette2->bindParam(":titre", $titre);
    $requeteSQLModiferRecette2->bindParam(":typeRecette", $type);
    $requeteSQLModiferRecette2->bindParam(":idUser", $userID);
    $requeteSQLModiferRecette2->bindParam(":idRecette", $recetteID);
    $requeteSQLModiferRecette2->execute();

    if ($requeteSQLModiferRecette2 == true) {
        return true;
    }
}

// Table Etapes 
function modifierTableEtape($recetteID, $etapeInstruction, $etapeID)
{

    $requeteSQLModifierEtape = SGBDConnect()->prepare('UPDATE etapes 
        SET ETAPE_INSTRUCTION = :etapeInstruction
        WHERE RECETTE_ID_ETAPE = :recetteID
        AND ETAPE_ID = :etapeID');
    $requeteSQLModifierEtape->bindParam(":recetteID", $recetteID);
    $requeteSQLModifierEtape->bindParam(":etapeInstruction", $etapeInstruction);
    $requeteSQLModifierEtape->bindParam(":etapeID", $etapeID);
    $requeteSQLModifierEtape->execute();

    if ($requeteSQLModifierEtape == true) {
        return true;
    }
}

function deleteEtape($recetteID, $etapeID)
{
    $requeteSQLSupprimerEtape =  SGBDConnect()->prepare('DELETE FROM etapes 
    WHERE RECETTE_ID_ETAPE = :recetteID
    AND ETAPE_ID = :etapeID');
    $requeteSQLSupprimerEtape->bindParam(":recetteID", $recetteID);
    $requeteSQLSupprimerEtape->bindParam(":etapeID", $etapeID);
    $requeteSQLSupprimerEtape->execute();

    if ($requeteSQLSupprimerEtape == true) {
        return true;
    }
}

function ajoutNouvelleEtape($recetteID, $etapeInstruction)
{

    $requeteSQLAjoutEtape = SGBDConnect()->prepare('INSERT INTO etapes (RECETTE_ID_ETAPE, ETAPE_INSTRUCTION) 
    VALUES (:recetteID, :etapeInstruction)');
    $requeteSQLAjoutEtape->bindParam(":recetteID", $recetteID);
    $requeteSQLAjoutEtape->bindParam(":etapeInstruction", $etapeInstruction);
    $requeteSQLAjoutEtape->execute();

    if ($requeteSQLAjoutEtape == true) {
        return true;
    }
}


// Modifier table recettes_ingredients

function getIngredientGlobale($recetteID)
{
    $requeteSQLIngredientGlobale = SGBDConnect()->prepare('SELECT * 
    FROM recettes_ingredients 
    WHERE RECETTE_INGREDIENT_ID_RECETTE = :idRecette');
    $requeteSQLIngredientGlobale->bindParam(":idRecette", $recetteID);
    $requeteSQLIngredientGlobale->execute();

    $resultatRequeteSQLIngredientGlobale = $requeteSQLIngredientGlobale->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLIngredientGlobale;
}

function modifierTableIngredientRecette($recetteID, $ingredientRecetteID, $ingredientID, $quantite, $uniteMesureID)
{

    $requeteSQLModifierIngredientGlobale = SGBDConnect()->prepare('UPDATE recettes_ingredients 
        SET RECETTE_INGREDIENT_ID_INGREDIENT = :ingredientID, RECETTE_INGREDIENT_QUANTITE = :quantite, RECETTE_INGREDIENT_ID_MESURE = :uniteMesureID
        WHERE RECETTE_INGREDIENT_ID_RECETTE = :recetteID
        AND RECETTE_INGREDIENT_ID = :id');
    $requeteSQLModifierIngredientGlobale->bindParam(":ingredientID", $ingredientID);
    $requeteSQLModifierIngredientGlobale->bindParam(":quantite", $quantite);
    $requeteSQLModifierIngredientGlobale->bindParam(":uniteMesureID", $uniteMesureID);
    $requeteSQLModifierIngredientGlobale->bindParam(":recetteID", $recetteID);
    $requeteSQLModifierIngredientGlobale->bindParam(":id", $ingredientRecetteID);
    $requeteSQLModifierIngredientGlobale->execute();

    if ($requeteSQLModifierIngredientGlobale == true) {
        return true;
    }
}

function deleteIngredientGlobale($recetteID, $ingredientRecetteID)
{
    $requeteSQLSupprimerIngredientGlobale =  SGBDConnect()->prepare('DELETE FROM recettes_ingredients 
    WHERE RECETTE_INGREDIENT_ID_RECETTE = :recetteID
    AND RECETTE_INGREDIENT_ID = :id');
    $requeteSQLSupprimerIngredientGlobale->bindParam(":recetteID", $recetteID);
    $requeteSQLSupprimerIngredientGlobale->bindParam(":id", $ingredientRecetteID);
    $requeteSQLSupprimerIngredientGlobale->execute();

    if ($requeteSQLSupprimerIngredientGlobale == true) {
        return true;
    }
}

function ajoutNouvelleIngredientGlobale($recetteID, $ingredientID, $quantite, $uniteMesureID)
{
    $requeteSQLAjoutIngredientGlobale = SGBDConnect()->prepare('INSERT INTO recettes_ingredients (RECETTE_INGREDIENT_ID_RECETTE, RECETTE_INGREDIENT_ID_INGREDIENT, RECETTE_INGREDIENT_QUANTITE, RECETTE_INGREDIENT_ID_MESURE) 
    VALUES (:recetteID, :ingredientID, :quantite, :uniteMesureID)');
    $requeteSQLAjoutIngredientGlobale->bindParam(":recetteID", $recetteID);
    $requeteSQLAjoutIngredientGlobale->bindParam(":ingredientID", $ingredientID);
    $requeteSQLAjoutIngredientGlobale->bindParam(":quantite", $quantite);
    $requeteSQLAjoutIngredientGlobale->bindParam(":uniteMesureID", $uniteMesureID);
    $requeteSQLAjoutIngredientGlobale->execute();

    if ($requeteSQLAjoutIngredientGlobale == true) {
        return true;
    }
}
//INSCRIPTION
function traitementMotDePasseInscription($motDePasse)
{
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,24}$/';


    if (strlen($motDePasse) >= 8 && strlen($motDePasse) <= 16) {
        if (preg_match($pattern, $motDePasse)) {

            $motDePasse = password_hash($motDePasse, PASSWORD_BCRYPT);
        } else {
            $erreurMotDePasseInscription = "Votre mot de passe doit contenir au minimum :<br>1 Minuscule<br>1 Majuscule<br>1 Chiffre<br>1 Caractère spécial.";
            header('Location: ../index.php?action=inscrivez-vous&erreurMotDePasseInscription=' . $erreurMotDePasseInscription);
            exit();
        }
    } else {
        $erreurMotDePasseInscription = "Votre mot de passe doit être compris entre 8 et 16 caractères.";
        header('Location: ../index.php?action=inscrivez-vous&erreurMotDePasseInscription=' . $erreurMotDePasseInscription);
        exit();
    }

    return $motDePasse;
}

//AJOUT UTILISATEUR
function ajoutUtilisateur($nomTraite, $prenomTraite, $ageTraite, $photoProfilTraite, $emailTraite, $motDePasseTraite)
{
    $requeteSQLAjoutUtilisateur = SGBDConnect()->prepare('INSERT INTO users (USER_NOM, USER_PRENOM, USER_AGE, USER_EMAIL, USER_HASH, USER_PHOTO) 
    VALUES (:nom, :prenom, :age, :email, :mdp, :photo)');
    $requeteSQLAjoutUtilisateur->bindParam(":nom", $nomTraite);
    $requeteSQLAjoutUtilisateur->bindParam(":prenom", $prenomTraite);
    $requeteSQLAjoutUtilisateur->bindParam(":age", $ageTraite);
    $requeteSQLAjoutUtilisateur->bindParam(":email", $emailTraite);
    $requeteSQLAjoutUtilisateur->bindParam(":mdp", $motDePasseTraite);
    $requeteSQLAjoutUtilisateur->bindParam(":photo", $photoProfilTraite);
    $requeteSQLAjoutUtilisateur->execute();

    if ($requeteSQLAjoutUtilisateur == true) {
        return true;
    }
}

// VERIFICATION PROFIL
// NOM
function traitementNom($nom)
{

    if (isset($nom) && !empty($nom) && strlen($nom) > 2 && strlen($nom) <= 64 && !is_numeric($nom)) {
        $nom = strtoupper($nom);
        $nom = strip_tags($nom);
    } else {
        $erreurNomProfil = "Votre nom n'est pas valide";
        if (!isset($_SESSION['traitementModificationProfil']) || $_SESSION['traitementModificationProfil'] !== 'traitementModificationProfil') {
            header('Location: ../index.php?action=inscrivez-vous&erreurNom=' . $erreurNomProfil);
            exit();
        } else {
            header('Location: ../index.php?action=modifier-mon-profil&erreurNom=' . $erreurNomProfil);
            exit();
        }
    }
    return $nom;
}

function updateNom($nomTraite, $userID)
{

    $requeteSQLUpdateNom = SGBDConnect()->prepare('UPDATE users 
    SET USER_NOM = :nom
    WHERE USER_ID = :idUser');
    $requeteSQLUpdateNom->bindParam(":nom", $nomTraite);
    $requeteSQLUpdateNom->bindParam(":idUser", $userID);
    $requeteSQLUpdateNom->execute();

    if ($requeteSQLUpdateNom == true) {
        return true;
    }
}
// PRENOM
function traitementPrenom($prenom)
{
    if (isset($prenom) && !empty($prenom) && strlen($prenom) > 2 && strlen($prenom) <= 64 && !is_numeric($prenom)) {
        $prenom = ucfirst($prenom);
        $prenom = strip_tags($prenom);
    } else {
        $erreurPrenomProfil = "Votre prénom n'est pas valide";
        if (!isset($_SESSION['traitementModificationProfil']) || $_SESSION['traitementModificationProfil'] !== 'traitementModificationProfil') {
            header('Location: ../index.php?action=inscrivez-vous&erreurPrenom=' . $erreurPrenomProfil);
            exit();
        } else {
            header('Location: ../index.php?action=modifier-mon-profil&erreurPrenom=' . $erreurPrenomProfil);
            exit();
        }
    }
    return $prenom;
}

function updatePrenom($prenomTraite, $userID)
{

    $requeteSQLUpdatePrenom = SGBDConnect()->prepare('UPDATE users 
    SET USER_PRENOM = :prenom
    WHERE USER_ID = :idUser');
    $requeteSQLUpdatePrenom->bindParam(":prenom", $prenomTraite);
    $requeteSQLUpdatePrenom->bindParam(":idUser", $userID);
    $requeteSQLUpdatePrenom->execute();

    if ($requeteSQLUpdatePrenom == true) {
        return true;
    }
}
//AGE
function traitementAge($age)
{
    list($year, $month, $day) = explode('-', $age);
    $dateOfBirth = new DateTime($age);
    $now = new DateTime(); // Date actuelle

    $interval = $now->diff($dateOfBirth);

    $ageInYears = $interval->y; // Récupère l'âge en années
    $ageInMonths = $interval->m; // Récupère l'âge en mois
    $ageInDays = $interval->d;
    $ageInDays = ($ageInYears * 365) + ($ageInMonths * 30) + $ageInDays;

    if (isset($age) && !empty($age) && checkdate($month, $day, $year) && $ageInDays >= 3650 && $ageInDays <= 36500) {
        $age = strip_tags($age);
    } else {
        $erreurAgeProfil = "Votre date de naissance est invalide.<br>Il faut avoir entre 10 et 99 ans";
        if (!isset($_SESSION['traitementModificationProfil']) || $_SESSION['traitementModificationProfil'] !== 'traitementModificationProfil') {
            header('Location: ../index.php?action=inscrivez-vous&erreurAge=' . $erreurAgeProfil);
            exit();
        } else {
            header('Location: ../index.php?action=modifier-mon-profil&erreurAge=' . $erreurAgeProfil);
            exit();
        }
    }
    return $age;
}

function updateAge($ageTraite, $userID)
{
    $requeteSQLUpdateDateNaissance = SGBDConnect()->prepare('UPDATE users 
    SET USER_AGE = :date
    WHERE USER_ID = :idUser');
    $requeteSQLUpdateDateNaissance->bindParam(":date", $ageTraite);
    $requeteSQLUpdateDateNaissance->bindParam(":idUser", $userID);
    $requeteSQLUpdateDateNaissance->execute();

    if ($requeteSQLUpdateDateNaissance == true) {
        return true;
    }
}
// PHOTO PROFIL

function  updatePhotoProfil($value, $userID)
{
    $requeteSQLUpdatePhotoProfil = SGBDConnect()->prepare('UPDATE users 
    SET USER_PHOTO = :nomPhoto
    WHERE USER_ID = :idUser');
    $requeteSQLUpdatePhotoProfil->bindParam(":nomPhoto", $value);
    $requeteSQLUpdatePhotoProfil->bindParam(":idUser", $userID);
    $requeteSQLUpdatePhotoProfil->execute();

    if ($requeteSQLUpdatePhotoProfil == true) {
        return true;
    }
}


// EMAIL

function traitementEmail($email, $confirmationEmail)
{
    $resultatCheckEmail = AllEmailUsers();

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && filter_var($confirmationEmail, FILTER_VALIDATE_EMAIL)) {
        if (verificationDoublonEmail($email, $resultatCheckEmail) == false) {
            $email = strip_tags($email);
        } else {
            $erreurEmailProfil2 = "Cet email est déjà existant.";
            if (!isset($_SESSION['traitementModificationProfil']) || $_SESSION['traitementModificationProfil'] !== 'traitementModificationProfil') {
                header('Location: ../index.php?action=inscrivez-vous&erreurEmail2=' . $erreurEmailProfil2);
                exit();
            } else {
                header('Location: ../index.php?action=modifier-mon-profil&erreurEmail2=' . $erreurEmailProfil2);
                exit();
            }
        }
    } else {
        $erreurEmailProfil2 = "Votre email est invalide.";
        if (!isset($_SESSION['traitementModificationProfil']) || $_SESSION['traitementModificationProfil'] !== 'traitementModificationProfil') {
            header('Location: ../index.php?action=inscrivez-vous&erreurEmail2=' . $erreurEmailProfil2);
            exit();
        } else {
            header('Location: ../index.php?action=modifier-mon-profil&erreurEmail2=' . $erreurEmailProfil2);
            exit();
        }
    }
    return $email;
}

function updateEmail($emailTraite, $userID)
{
    $requeteSQLUpdateEmail = SGBDConnect()->prepare('UPDATE users 
    SET USER_EMAIL = :email
    WHERE USER_ID = :idUser');
    $requeteSQLUpdateEmail->bindParam(":email", $emailTraite);
    $requeteSQLUpdateEmail->bindParam(":idUser", $userID);
    $requeteSQLUpdateEmail->execute();

    if ($requeteSQLUpdateEmail == true) {
        return true;
    }
}

// MOT DE PASSE
function traitementMotDePasse($nouveauMpd, $ancienMdp)
{
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,24}$/';

    if (password_verify($nouveauMpd, $ancienMdp) == false) {
        if (strlen($nouveauMpd) >= 8 && strlen($nouveauMpd) <= 16) {
            if (preg_match($pattern, $nouveauMpd)) {

                $nouveauMpd = password_hash($nouveauMpd, PASSWORD_BCRYPT);
            } else {
                $erreurMotDePasseProfil = "Votre mot de passe doit contenir au minimum :<br>1 Minuscule<br>1 Majuscule<br>1 Chiffre<br>1 Caractère spécial.";
                header('Location: ../index.php?action=modifier-mon-profil&erreurMotDePasseProfil=' . $erreurMotDePasseProfil);
                exit();
            }
        } else {
            $erreurMotDePasseProfil = "Votre mot de passe doit être compris entre 8 et 16 caractères.";
            header('Location: ../index.php?action=modifier-mon-profil&erreurMotDePasseProfil=' . $erreurMotDePasseProfil);
            exit();
        }
    } else {
        $erreurMotDePasseProfil = "Mot de passe déjà en cours d'utilisation.";
        header('Location: ../index.php?action=modifier-mon-profil&erreurMotDePasseProfil=' . $erreurMotDePasseProfil);
        exit();
    }
    return $nouveauMpd;
}

function updateMotDePasse($motDePasseTraite, $userID)
{
    $requeteSQLUpdateMotDePasse = SGBDConnect()->prepare('UPDATE users 
    SET USER_HASH = :mdp
    WHERE USER_ID = :idUser');
    $requeteSQLUpdateMotDePasse->bindParam(":mdp", $motDePasseTraite);
    $requeteSQLUpdateMotDePasse->bindParam(":idUser", $userID);
    $requeteSQLUpdateMotDePasse->execute();

    if ($requeteSQLUpdateMotDePasse == true) {
        return true;
    }
}

function countRecettes($typeRecettePublic)
{
    $requeteSQLCountEnregistrement = SGBDConnect()->prepare('SELECT COUNT(RECETTE_ID) AS COUNT
    FROM recettes
    WHERE RECETTE_TYPE = :typeRecette
    AND RECETTE_TITRE IS NOT NULL
    AND RECETTE_PHOTO IS NOT NULL');
    $requeteSQLCountEnregistrement->bindParam(":typeRecette", $typeRecettePublic);
    $requeteSQLCountEnregistrement->execute();

    $resultatRequeteSQLCountEnregistrement = $requeteSQLCountEnregistrement->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLCountEnregistrement;
}

function countRecettes2($typeRecettePublic, $search)
{
    $search = '%' . $search . '%';
    $requeteSQLCountEnregistrement = SGBDConnect()->prepare('SELECT COUNT(RECETTE_ID) AS COUNT
    FROM recettes
    WHERE RECETTE_TYPE = :typeRecette
    AND RECETTE_TITRE LIKE :recherche
    AND RECETTE_TITRE IS NOT NULL
    AND RECETTE_PHOTO IS NOT NULL');
    $requeteSQLCountEnregistrement->bindParam(":typeRecette", $typeRecettePublic);
    $requeteSQLCountEnregistrement->bindParam(":recherche", $search);
    $requeteSQLCountEnregistrement->execute();

    $resultatRequeteSQLCountEnregistrement = $requeteSQLCountEnregistrement->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLCountEnregistrement;
}

function recettesEntreesPublic2($debut, $recetteParPage, $search)
{
    $search = '%' . $search . '%';
    $typeRecette = 'Entrée';
    $requeteSQLRecettesPublic = SGBDConnect()->prepare('SELECT RECETTE_ID, RECETTE_TYPE, RECETTE_TITRE, RECETTE_PHOTO, RECETTE_VISIBILITE, concat(USER_PRENOM,\' \',USER_NOM) AS AUTEUR 
    FROM recettes
    INNER JOIN users 
    on USER_ID = USER_ID_RECETTE 
    WHERE RECETTE_TYPE = :typeRecette
    AND RECETTE_TITRE IS NOT NULL
    AND RECETTE_PHOTO IS NOT NULL
    AND RECETTE_TITRE LIKE :recherche
    ORDER BY RECETTE_TITRE
    LIMIT :debut, :elementParPage');
    $requeteSQLRecettesPublic->bindParam(":typeRecette", $typeRecette);
    $requeteSQLRecettesPublic->bindParam(":debut", $debut);
    $requeteSQLRecettesPublic->bindParam(":elementParPage", $recetteParPage);
    $requeteSQLRecettesPublic->bindParam(":recherche", $search);
    $requeteSQLRecettesPublic->execute();

    $resultatRequeteSQLRecettesPublic = $requeteSQLRecettesPublic->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLRecettesPublic;
}

function recettesPlatsPublic2($debut, $recetteParPage, $search)
{
    $search = '%' . $search . '%';
    $typeRecette = 'Plat';
    $requeteSQLRecettesPublic = SGBDConnect()->prepare('SELECT RECETTE_ID, RECETTE_TYPE, RECETTE_TITRE, RECETTE_PHOTO, RECETTE_VISIBILITE, concat(USER_PRENOM,\' \',USER_NOM) AS AUTEUR 
    FROM recettes
    INNER JOIN users 
    on USER_ID = USER_ID_RECETTE 
    WHERE RECETTE_TYPE = :typeRecette
    AND RECETTE_TITRE IS NOT NULL
    AND RECETTE_PHOTO IS NOT NULL
    AND RECETTE_TITRE LIKE :recherche
    ORDER BY RECETTE_TITRE
    LIMIT :debut, :elementParPage');
    $requeteSQLRecettesPublic->bindParam(":typeRecette", $typeRecette);
    $requeteSQLRecettesPublic->bindParam(":debut", $debut);
    $requeteSQLRecettesPublic->bindParam(":elementParPage", $recetteParPage);
    $requeteSQLRecettesPublic->bindParam(":recherche", $search);
    $requeteSQLRecettesPublic->execute();

    $resultatRequeteSQLRecettesPublic = $requeteSQLRecettesPublic->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLRecettesPublic;
}

function recettesDessertsPublic2($debut, $recetteParPage, $search)
{
    $search = '%' . $search . '%';
    $typeRecette = 'Dessert';
    $requeteSQLRecettesPublic = SGBDConnect()->prepare('SELECT RECETTE_ID, RECETTE_TYPE, RECETTE_TITRE, RECETTE_PHOTO, RECETTE_VISIBILITE, concat(USER_PRENOM,\' \',USER_NOM) AS AUTEUR 
    FROM recettes
    INNER JOIN users 
    on USER_ID = USER_ID_RECETTE 
    WHERE RECETTE_TYPE = :typeRecette
    AND RECETTE_TITRE IS NOT NULL
    AND RECETTE_PHOTO IS NOT NULL
    AND RECETTE_TITRE LIKE :recherche
    ORDER BY RECETTE_TITRE
    LIMIT :debut, :elementParPage');
    $requeteSQLRecettesPublic->bindParam(":typeRecette", $typeRecette);
    $requeteSQLRecettesPublic->bindParam(":debut", $debut);
    $requeteSQLRecettesPublic->bindParam(":elementParPage", $recetteParPage);
    $requeteSQLRecettesPublic->bindParam(":recherche", $search);
    $requeteSQLRecettesPublic->execute();

    $resultatRequeteSQLRecettesPublic = $requeteSQLRecettesPublic->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLRecettesPublic;
}

function recettesGourmandisesPublic2($debut, $recetteParPage, $search)
{
    $search = '%' . $search . '%';
    $typeRecette = 'Gourmandise';
    $requeteSQLRecettesPublic = SGBDConnect()->prepare('SELECT RECETTE_ID, RECETTE_TYPE, RECETTE_TITRE, RECETTE_PHOTO, RECETTE_VISIBILITE, concat(USER_PRENOM,\' \',USER_NOM) AS AUTEUR 
    FROM recettes
    INNER JOIN users 
    on USER_ID = USER_ID_RECETTE 
    WHERE RECETTE_TYPE = :typeRecette
    AND RECETTE_TITRE IS NOT NULL
    AND RECETTE_PHOTO IS NOT NULL
    AND RECETTE_TITRE LIKE :recherche
    ORDER BY RECETTE_TITRE
    LIMIT :debut, :elementParPage');
    $requeteSQLRecettesPublic->bindParam(":typeRecette", $typeRecette);
    $requeteSQLRecettesPublic->bindParam(":debut", $debut);
    $requeteSQLRecettesPublic->bindParam(":elementParPage", $recetteParPage);
    $requeteSQLRecettesPublic->bindParam(":recherche", $search);
    $requeteSQLRecettesPublic->execute();

    $resultatRequeteSQLRecettesPublic = $requeteSQLRecettesPublic->fetchAll(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLRecettesPublic;
}

function commentaireRecette($idRecette)
{
    $requeteSQLCommentaireRecette = SGBDConnect()->prepare('SELECT COMMENTAIRE_ID, COMMENTAIRE_ID_USER, COMMENTAIRE_ID_RECETTE, COMMENTAIRE_DATE, COMMENTAIRE_CONTENU, USER_PHOTO, concat(USER_PRENOM,\' \',USER_NOM) AS AUTEUR 
    FROM commentaires  
    INNER JOIN users on COMMENTAIRE_ID_USER = USER_ID
    WHERE COMMENTAIRE_ID_RECETTE = :idRecette
    AND COMMENTAIRE_ID_USER = USER_ID
    ORDER BY COMMENTAIRE_DATE desc ');
    $requeteSQLCommentaireRecette->bindParam(":idRecette", $idRecette);
    $requeteSQLCommentaireRecette->execute();

    $resultatRequeteSQLCommentaireRecette = $requeteSQLCommentaireRecette->fetchALL(PDO::FETCH_ASSOC);

    return $resultatRequeteSQLCommentaireRecette;
}

function traitementCommentaire($commentaire, $typeRecette, $titreRecette)
{
    if (isset($commentaire) && !empty($commentaire) && strlen($commentaire) >= 5 && strlen($commentaire) <= 1000) {
        $commentaireTraite = strip_tags($commentaire);
    } else {
        $erreurCommentaire = "Un commentaire doit être compris entre 5 et 1000 caractères.";
        if ($typeRecette == 'desserts') {
            header('Location: ../index.php?action=recettes-desserts&recette=' . $titreRecette . '&erreurCommentaire=' . $erreurCommentaire);
            exit();
        } elseif ($typeRecette == 'entrées') {
            header('Location: ../index.php?action=recettes-entrees&recette=' . $titreRecette . '&erreurCommentaire=' . $erreurCommentaire);
            exit();
        } elseif ($typeRecette == 'plats') {
            header('Location: ../index.php?action=recettes-plats&recette=' . $titreRecette . '&erreurCommentaire=' . $erreurCommentaire);
            exit();
        } elseif ($typeRecette == 'gourmandises') {
            header('Location: ../index.php?action=recettes-gourmandises&recette=' . $titreRecette . '&erreurCommentaire=' . $erreurCommentaire);
            exit();
        }
    }
    return $commentaireTraite;
}

function traitementIDRecetteCommenter($recetteCommenter)
{
    if (isset($recetteCommenter) && !empty($recetteCommenter) && is_numeric($recetteCommenter)) {
        $recetteCommenterTraite = strip_tags($recetteCommenter);
    } else {
        header('Location: ../index.php?action=accueil');
        exit();
    }
    return $recetteCommenterTraite;
}

function insertionCommentaire($auteurCommentaire, $recetteCommenterTraite, $date, $commentaireTraite)
{
    $requeteSQLAjoutCommentaire = SGBDConnect()->prepare('INSERT INTO commentaires (COMMENTAIRE_ID_USER, COMMENTAIRE_ID_RECETTE, COMMENTAIRE_DATE, COMMENTAIRE_CONTENU) 
    VALUES (:idUser, :idRecette, :date, :commentaire)');
    $requeteSQLAjoutCommentaire->bindParam(":idUser", $auteurCommentaire);
    $requeteSQLAjoutCommentaire->bindParam(":idRecette", $recetteCommenterTraite);
    $requeteSQLAjoutCommentaire->bindParam(":date", $date);
    $requeteSQLAjoutCommentaire->bindParam(":commentaire", $commentaireTraite);
    $requeteSQLAjoutCommentaire->execute();

    if ($requeteSQLAjoutCommentaire == true) {
        return true;
    }
}
