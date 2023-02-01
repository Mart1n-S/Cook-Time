<?php
require './source/functions.php';
initialisationSEssion();
$navigationEnCours = "connexionInscription";
$inscription = "inscription";
if (utilisateurConnecte()) {

    header('Location: index.php?action=accueil');
} else {

    $_SESSION['inscription'] = 'inscriptionlEnCours';
?>

    <?php include_once('./elements/header.php'); ?>

    <main>
        <section>
            <div class="cardInscription">
                <h2>Inscription</h2>
                <div class="containerFormulaireInscription">
                    <form method="POST" action="./traitements/traitementInscription.php" enctype="multipart/form-data">
                        <div class="logoSite">
                            <img src="./images/logoSiteRecettes.png" alt="logo site de recettes de cuisine">
                        </div>
                        <p class="sousLogo">Merci de remplir tous les champs.</p>
                        <div class="entreeNomInscription">
                            <label for="nomInscription1"><span class="material-symbols-rounded">person</span>Nom</label>
                            <input type="text" name="nomInscription" id="nomInscription1" required>
                        </div>
                        <?php if (isset($_GET['erreurNom'])) {
                            echo '<p id="error" class="erreur">' . $_GET['erreurNom'] . '</p>';
                        } ?>
                        <div class="entreePrenomInscription">
                            <label for="prenomInscription1"><span class="material-symbols-rounded">person</span>Prenom</label>
                            <input type="text" name="prenomInscription" id="prenomInscription1" required>
                        </div>
                        <?php if (isset($_GET['erreurPrenom'])) {
                            echo '<p id="error" class="erreur">' . $_GET['erreurPrenom'] . '</p>';
                        } ?>
                        <div class="entreeAgeInscription">
                            <label for="ageInscription1"><span class="material-symbols-rounded">cake</span>Age</label>

                            <input type="date" name="ageInscription" id="ageInscription1" onfocus="setAgeRange()" required>
                            <?php if (isset($_GET['erreurAge'])) {
                                echo '<div class="erreurAgeProfil"><p id="error" class="erreur">' . $_GET['erreurAge'] . '</p></div>';
                            } ?>

                        </div>
                        <div class="entreePhotoProfilInscription">
                            <label class="labelSolide"><span class="material-symbols-rounded">face</span>Photo de profil <span id="labelPhotoFacultatif">(optionel)</span></label>
                            <input type="file" name="PhotoProfilInscription" id="PhotoProfilInscription1" accept=".jpg, .jpeg, .png" class="inputInvisible">
                            <div class="containerFauxInput">
                                <label for="PhotoProfilInscription1" class="fauxBouton">Choisir une image (PNG, JPG)</label>
                                <div class="previewImage">
                                    <p>Aucune image sélectionnée</p>
                                </div>
                                <span class="supprimerImage" onclick="supprimerPhoto()"><img src="./images/style/delete.svg"></span>
                            </div>
                        </div>
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
                        <div class="entreeEmailInscription">
                            <label for="emailInscription1"><span class="material-symbols-rounded"> mail </span>Email</label>
                            <input type="email" name="emailInscription" id="emailInscription1" autocomplete="off" required>
                        </div>
                        <div class="entreeEmailInscriptionConfirmation">
                            <label for="emailInscriptionConfirmation1"><span class="material-symbols-rounded"> mail </span>Confirmation de email</label>
                            <input type="email" name="emailInscriptionConfirmation" id="emailInscriptionConfirmation1" required>
                        </div>
                        <?php if (isset($_GET['erreurEmail'])) {
                            echo '<p id="error" class="erreur">' . $_GET['erreurEmail'] . '</p>';
                        } ?>
                        <?php if (isset($_GET['erreurEmail2'])) {
                            echo '<p id="error" class="erreur">' . $_GET['erreurEmail2'] . '</p>';
                        } ?>
                        <div class="entreeMotDePasseInscription">
                            <label for="motDePasseInscription1"><span class="material-symbols-rounded"> lock </span>Mot de passe</label>
                            <div class="boutonVoirMotDePasse">
                                <input type="password" name="motDePasseInscription" id="motDePasseInscription1" minlength="8" maxlength="16" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$.%^&*_=+-]).{8,24}" title="Minimum (a-z / A-Z / 0-9 / !@#$.%^&*_=+-)" autocomplete="new-password" required>
                                <span onclick="showPassword()" class="material-symbols-rounded">visibility</span>
                            </div>
                        </div>
                        <div class="entreeMotDePasseInscriptionConfirmation">
                            <label for="motDePasseInscriptionConfirmation1"><span class="material-symbols-rounded"> lock </span>Confirmation mot de passe</label>
                            <input type="password" name="motDePasseInscriptionConfirmation" id="motDePasseInscriptionConfirmation1" required>
                        </div>
                        <?php if (isset($_GET['erreurMotDePasseInscription'])) {
                            echo '<p id="error" class="erreur">' . $_GET['erreurMotDePasseInscription'] . '</p>';
                        } ?>
                        <div class="containerBoutonInscription">
                            <input type="submit" name="validationInscription" value="S'INSCRIRE" class="boutonInscription">
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
<?php include_once('./elements/footer.php');
}; ?>