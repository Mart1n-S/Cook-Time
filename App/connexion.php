<?php
require './source/functions.php';
initialisationSEssion();
$connexion = "connexion";
$navigationEnCours = "connexionInscription";


if (isset($_POST['validationConnexion'])) {
    if (isset($_POST['emailConnexion']) && !empty($_POST['emailConnexion']) && isset($_POST['motDePasseConnexion']) && !empty($_POST['motDePasseConnexion'])) {

        $email = $_POST['emailConnexion'];
        $mdp = $_POST['motDePasseConnexion'];

        $resultatConnexion = connexionUtilisateur($email);

        if (password_verify($mdp, $resultatConnexion['USER_HASH'])) {

            $_SESSION['id'] = $resultatConnexion['USER_ID'];
            $_SESSION['nom'] = $resultatConnexion['USER_NOM'];
            $_SESSION['prenom'] = $resultatConnexion['USER_PRENOM'];
            $_SESSION['dateNaissance'] = $resultatConnexion['USER_AGE'];
            $_SESSION['age'] = $resultatConnexion['AGE'];
            $_SESSION['email'] = $resultatConnexion['USER_EMAIL'];
            $_SESSION['photo'] = $resultatConnexion['USER_PHOTO'];
            header('Location: index.php?action=mon-profil');
            exit();
        } else {
            $erreurConnexion = "Email ou mot de passe incorrect";
        }
    }
}

if (utilisateurConnecte()) {

    header('Location: index.php?action=accueil');
    exit();
} else {


?>

    <?php include_once('./elements/header.php'); ?>

    <main>
        <section>
            <div class="cardConnexion">
                <h2>Connexion</h2>
                <div class="containerFormulaireConnexion">
                    <form method="POST" action="index.php?action=authentification">
                        <div class="logoSite">
                            <img src="./images/logoSiteRecettes.png" alt="logo site de recettes de cuisine">
                        </div>
                        <p>Merci de vous connecter pour utiliser le site.</p>
                        <?php if (isset($erreurConnexion)) {

                            echo '<span class="erreurConnexion">' . $erreurConnexion . '</span>';
                        } ?>

                        <div class="entreeEmailConnexion">
                            <input type="email" name="emailConnexion" id="emailConnexion1" required>
                            <label for="emailConnexion1"><span class="material-symbols-rounded"> mail </span>Email</label>
                        </div>
                        <div class="entreeMotDePasseConnexion">
                            <input type="password" name="motDePasseConnexion" id="motDePasseConnexion1" required>
                            <label for="motDePasseConnexion1"><span class="material-symbols-rounded"> lock </span>Mot de passe</label>
                        </div>
                        <div class="boutonVoirMotDePasseConnexion">
                            <span onclick="showPassword2()" class="material-symbols-rounded">visibility</span>
                        </div>
                        <a href="#" class="lienMotDePasseOublie">Mot de passe oublié ?</a>
                        <div class="containerBoutonConnexion">
                            <input type="submit" name="validationConnexion" value="CONNEXION" class="boutonConnexion">
                        </div>
                        <div class="lienCreationCompte">
                            Vous n'avez pas de compte ? <a href="index.php?action=inscrivez-vous">Venez vous inscrire ici.</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
<?php include_once('./elements/footer.php');
}; ?>