<?php
require '../source/functions.php';
initialisationSEssion();


$validationInscription = $_POST['validationInscription'];
if (isset($validationInscription) && !empty($validationInscription)) {

    // On récupère les données
    // On utilise la fonction trim() pour s'assurer que la valeur n'est pas vide et isset pour vérifier que la variable existe bien 
    $nom = trim(isset($_POST['nomInscription']) ? $_POST['nomInscription'] : '');
    // Si la variable n'existe pas on l'initialise à vide/NULL uniquement pour les champs qui accepte la valeur NULL dans la base de données
    $prenom = trim(isset($_POST['prenomInscription']) ? $_POST['prenomInscription'] : '');
    $age = trim(isset($_POST['ageInscription']) ? $_POST['ageInscription'] : '');
    $photoProfil = isset($_FILES['PhotoProfilInscription']) ? $_FILES['PhotoProfilInscription'] : '';
    $email = trim(isset($_POST['emailInscription']) ? $_POST['emailInscription'] : '');
    $confirmationEmail = trim(isset($_POST['emailInscriptionConfirmation']) ? $_POST['emailInscriptionConfirmation'] : '');
    $motDePasse = isset($_POST['motDePasseInscription']) ? $_POST['motDePasseInscription'] : '';
    $confirmationMdp = isset($_POST['motDePasseInscriptionConfirmation']) ? $_POST['motDePasseInscriptionConfirmation'] : '';

    // Nom
    $nomTraite = traitementNom($nom);


    // Prenom
    $prenomTraite = traitementPrenom($prenom);


    // Age
    $ageTraite = traitementAge($age);


    // Photo
    $photoProfilTraite = traitementImage($photoProfil);

    // Email
    if ($email !== '' && $confirmationEmail !== '') {
        if ($email === $confirmationEmail) {

            $emailTraite = traitementEmail($email, $confirmationEmail);
        } else {
            $erreurEmailProfil = "' Email ' et ' Confirmation de email ' ne sont pas identiques.";
            header('Location: ../index.php?action=inscrivez-vous&erreurEmail=' . $erreurEmailProfil);
            exit();
        }
    } else {
        $erreurEmailProfil = "Pour vous inscrire, merci de renseigner<br>votre email dans les 2 champs correspondant.";
        header('Location: ../index.php?action=inscrivez-vous&erreurEmail=' . $erreurEmailProfil);
        exit();
    }



    //Mot de passe
    if ($motDePasse !== '' && $confirmationMdp !== '') {
        if ($motDePasse === $confirmationMdp) {
            $motDePasseTraite = traitementMotDePasseInscription($motDePasse);
        } else {
            $erreurMotDePasseInscription = "'Mot de passe' et 'Confirmation mot de passe' ne sont pas identiques.";
            header('Location: ../index.php?action=inscrivez-vous&erreurMotDePasseInscription=' . $erreurMotDePasseInscription);
            exit();
        }
    } else {
        $erreurMotDePasseInscription = "Pour vous inscrire merci de rentrer un mot de passe.";
        header('Location: ../index.php?action=inscrivez-vous&erreurMotDePasseInscription=' . $erreurMotDePasseInscription);
        exit();
    }

    // Une fois tout vérifier on fait l'insertion
    $resultatAjoutUtilisateur = ajoutUtilisateur($nomTraite, $prenomTraite, $ageTraite, $photoProfilTraite, $emailTraite, $motDePasseTraite);

    if (isset($resultatAjoutUtilisateur) && $resultatAjoutUtilisateur == true) {
        $_SESSION['succesInscription'] = true;
        $_SESSION['inscription'] = '';
        header('Location: ../index.php?action=validation-modification-profil');
        exit();
    } else {
        header('Location: ../index.php?action=inscrivez-vous');
        exit();
    }
} else {
    header('Location: ../index.php?action=accueil');
    exit();
}
