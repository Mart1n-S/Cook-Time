<?php
require '../source/functions.php';
initialisationSEssion();
$navigationEnCours = "profil";

$_SESSION['traitementModificationProfil'] = 'traitementModificationProfil';

if (!utilisateurConnecte()) {

    header('Location: ../index.php?action=accueil');
    exit();
} else {
    $modifierProfil = $_POST['validationModifierProfil'];
    if (isset($modifierProfil) && !empty($modifierProfil)) {

        $resultatInfosUtilisateur = connexionUtilisateur($_SESSION['email']);


        // On récupère les données
        // On utilise la fonction trim() pour s'assurer que la valeur n'est pas vide et isset pour vérifier que la variable existe bien 
        $nom = trim(isset($_POST['nomModifierProfil']) ? $_POST['nomModifierProfil'] : '');
        // Si la variable n'existe pas on l'initialise à vide/NULL uniquement pour les champs qui accepte la valeur NULL dans la base de données
        $prenom = trim(isset($_POST['prenomModifierProfil']) ? $_POST['prenomModifierProfil'] : '');
        $age = trim(isset($_POST['ageModifierProfil']) ? $_POST['ageModifierProfil'] : '');
        $photoProfil = isset($_FILES['photoModifierProfil']) ? $_FILES['photoModifierProfil'] : '';
        $email = trim(isset($_POST['emailModifierProfil']) ? $_POST['emailModifierProfil'] : '');
        $confirmationEmail = trim(isset($_POST['emailNouveauConfirmationProfil']) ? $_POST['emailNouveauConfirmationProfil'] : '');
        $ancienMdp = isset($_POST['mdpAncienProfil']) ? $_POST['mdpAncienProfil'] : '';
        $nouveauMpd = isset($_POST['mdpProfil']) ? $_POST['mdpProfil'] : '';
        $confirmationNouveauMdp = isset($_POST['mdpConfirmationProfil']) ? $_POST['mdpConfirmationProfil'] : '';

        // Nom
        if ($nom !== $_SESSION['nom']) {

            $nomTraite = traitementNom($nom);
        }

        // Prenom
        if ($prenom !== $_SESSION['prenom']) {

            $prenomTraite = traitementPrenom($prenom);
        }

        // Age
        if ($age !== $_SESSION['dateNaissance']) {

            $ageTraite = traitementAge($age);
        }

        // Photo
        if ($photoProfil['size'] !== 0) {

            $photoProfilTraite = traitementImage($photoProfil);
        }
        if (isset($_POST['oldimage']) && $photoProfil['size'] !== 0 || isset($_POST['oldimage']) &&  isset($_SESSION['oldImageProfil']) == 'supprimerOldImageProfil' || !isset($_POST['previewImageModifierProfil']) && isset($_POST['oldimage']) && $photoProfil['size'] == 0) {

            unlink('../uploads/photoProfil/' . $_SESSION['photoNameModifierProfil']);
            $value = NULL;
            $resultatUpdatePhoto = updatePhotoProfil($value, $_SESSION['id']);
            $_SESSION['photoNameModifierProfil'] = '';
            $_SESSION['oldImageProfil'] = '';
            if ($resultatUpdatePhoto == true) {
                $_SESSION['photo'] = $value;
            }
        }

        // Email
        if ($email !== '' && $confirmationEmail !== '') {
            if ($email === $confirmationEmail) {
                if ($email !== $_SESSION['email']) {
                    $emailTraite = traitementEmail($email, $confirmationEmail);
                } else {
                    $erreurEmailProfil2 = "Pour changer d'email merci de mettre un email différent de votre email actuel.";
                    header('Location: ../index.php?action=modifier-mon-profil&erreurEmail2=' . $erreurEmailProfil2);
                    exit();
                }
            } else {
                $erreurEmailProfil2 = "' Nouveau email ' et ' Confirmation de email ' ne sont pas identiques.";
                header('Location: ../index.php?action=modifier-mon-profil&erreurEmail2=' . $erreurEmailProfil2);
                exit();
            }
        } elseif ($email !== '' && $confirmationEmail == '' || $email == '' && $confirmationEmail !== '') {
            $erreurEmailProfil2 = "Pour changer d'email, merci de remplir les 2 champs correspondant.";
            header('Location: ../index.php?action=modifier-mon-profil&erreurEmail2=' . $erreurEmailProfil2);
            exit();
        }



        //Mot de passe
        if ($ancienMdp !== '' && $nouveauMpd !== '' && $confirmationNouveauMdp !== '') {
            if ($nouveauMpd === $confirmationNouveauMdp) {
                if (password_verify($ancienMdp, $resultatInfosUtilisateur['USER_HASH'])) {
                    $motDePasseTraite = traitementMotDePasse($nouveauMpd, $resultatInfosUtilisateur['USER_HASH']);
                } else {
                    $erreurMotDePasseProfilAncien = "Mot de passe incorrect.";
                    header('Location: ../index.php?action=modifier-mon-profil&erreurMotDePasseProfilAncien=' . $erreurMotDePasseProfilAncien);
                    exit();
                }
            } else {
                $erreurMotDePasseProfil = "'Nouveau mot de passe' et 'Confirmation mot de passe' ne sont pas identiques.";
                header('Location: ../index.php?action=modifier-mon-profil&erreurMotDePasseProfil=' . $erreurMotDePasseProfil);
                exit();
            }
        } elseif ($ancienMdp == '' && $nouveauMpd !== '' && $confirmationNouveauMdp !== '' || $ancienMdp !== '' && $nouveauMpd == '' && $confirmationNouveauMdp !== '' || $ancienMdp !== '' && $nouveauMpd !== '' && $confirmationNouveauMdp == '') {
            $erreurMotDePasseProfil = "Pour changer de mot de passe,<br>merci de renseigner votre ancien mot de passe.<br>Et de remplir les champs :<br>'Nouveau mot de passe' et 'Confirmation mot de passe'.";
            header('Location: ../index.php?action=modifier-mon-profil&erreurMotDePasseProfil=' . $erreurMotDePasseProfil);
            exit();
        }

        // Une fois tout vérifier on fait les update
        //Update Nom
        if (isset($nomTraite)) {
            $resultatUpdateNom = updateNom($nomTraite, $_SESSION['id']);
            if ($resultatUpdateNom == true) {
                $_SESSION['nom'] = $nomTraite;
            }
        }




        //Update Prenom
        if (isset($prenomTraite)) {
            $resultatUpdatePrenom = updatePrenom($prenomTraite, $_SESSION['id']);
            if ($resultatUpdatePrenom == true) {
                $_SESSION['prenom'] = $prenomTraite;
            }
        }




        //Update Age 
        if (isset($ageTraite)) {
            $resultatUpdateAge = updateAge($ageTraite, $_SESSION['id']);
            if ($resultatUpdateAge == true) {
                $_SESSION['dateNaissance'] = $ageTraite;

                $newAge = connexionUtilisateur($_SESSION['email']);

                $_SESSION['age'] = $newAge['AGE'];
            }
        }




        //Update Photo de profil
        if (isset($photoProfilTraite)) {
            $resultatUpdatePhoto = updatePhotoProfil($photoProfilTraite, $_SESSION['id']);
            if ($resultatUpdatePhoto == true) {
                $_SESSION['photo'] = $photoProfilTraite;
            }
        }

        //Update Email
        if (isset($emailTraite)) {
            $resultatUpdateEmail = updateEmail($emailTraite, $_SESSION['id']);
            if ($resultatUpdateEmail == true) {
                $_SESSION['email'] = $emailTraite;
            }
        }




        //Update Mot de passe
        if (isset($motDePasseTraite)) {
            $resultatUpdateMotDePasse = updateMotDePasse($motDePasseTraite, $_SESSION['id']);
        }


        if (isset($resultatUpdateNom) && $resultatUpdateNom == true || isset($resultatUpdatePrenom) && $resultatUpdatePrenom == true || isset($resultatUpdateAge) && $resultatUpdateAge == true || isset($resultatUpdatePhoto) && $resultatUpdatePhoto == true || isset($resultatUpdateEmail) && $resultatUpdateEmail == true || isset($resultatUpdateMotDePasse) && $resultatUpdateMotDePasse == true || $_SESSION['modificationProfilEnCours'] == 'modificationProfilEnCours') {
            $_SESSION['succesModificationProfil'] = true;
            $_SESSION['traitementModificationProfil'] = '';
            $_SESSION['modificationProfilEnCours'] = '';
            header('Location: ../index.php?action=validation-modification-profil');
            exit();
        } else {
            header('Location: ../index.php?action=modifier-mon-profil');
            exit();
        }
    } else {
        header('Location: ../index.php?action=accueil');
        exit();
    }
}
