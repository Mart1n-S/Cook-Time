<?php
require 'source/functions.php';
initialisationSEssion();
$modifierProfil = "modifierProfil";
$navigationEnCours = "profil";
if (!utilisateurConnecte()) {

    header('Location: index.php?action=accueil');
    exit();
} elseif (isset($_SESSION['id']) && isset($_POST['modifierProfil']) || isset($_SESSION['modificationProfilEnCours']) && $_SESSION['modificationProfilEnCours'] !== '') {

    $_SESSION['modificationProfilEnCours'] = 'modificationProfilEnCours';

    if ($_SESSION['photo'] !== NULL) {
        $image_path = "./uploads/photoProfil/" . $_SESSION['photo'];
        $photoSize = filesize($image_path);
        $_SESSION['photoNameModifierProfil'] = $_SESSION['photo'];
    }

    if (isset($_POST['value']) && $_POST['value'] === 'supprimerOldImageProfil') {
        $_SESSION['oldImageProfil'] =  $_POST['value'];
    }

?>

    <?php include_once('./elements/header.php'); ?>

    <main>
        <section>
            <div class="cardModifierProfil">
                <h2>Modifier mon profil</h2>
                <div class="containerModifierProfil">
                    <form action="./traitements/traitementModifierProfil.php" method="post" id="formulaireModifierProfil" enctype="multipart/form-data">
                        <div class="topModifierProfil">
                            <div class="photoModifierProfil">
                                <?php
                                if (isset($_SESSION['photo']) && $_SESSION['photo'] != 'NULL' && file_exists("./uploads/photoProfil/" . strip_tags($_SESSION['photo']))) { ?>
                                    <img src="./uploads/photoProfil/<?php echo strip_tags($_SESSION['photo']) ?>" class="photoUtilisateur" alt="photo de profil">
                                <?php } else { ?>
                                    <img src="./images/photoProfilDefaut/defaut.svg" alt="photo de profil par defaut">
                                <?php }; ?>
                            </div>
                            <h3><?php echo strip_tags($_SESSION['prenom']); ?></h3>
                            <p>Modifier vos informations à votre guise.</p>
                        </div>
                        <div class="containerModifierInformationProfil">
                            <div class="entreeModifierNom">
                                <label for="nomModifierProfil1"><span class="material-symbols-rounded">person</span>Nom</label>
                                <input type="text" name="nomModifierProfil" id="nomModifierProfil1" value="<?php echo strip_tags($_SESSION['nom']) ?>" required>
                                <?php if (isset($_GET['erreurNom'])) {
                                    echo '<p id="error" class="erreur">' . $_GET['erreurNom'] . '</p>';
                                } ?>
                            </div>
                            <div class="entreeModifierPrenom">
                                <label for="prenomModifierProfil1"><span class="material-symbols-rounded">person</span>Prenom</label>
                                <input type="text" name="prenomModifierProfil" id="prenomModifierProfil1" value="<?php echo strip_tags($_SESSION['prenom']) ?>" required>
                                <?php if (isset($_GET['erreurPrenom'])) {
                                    echo '<p id="error" class="erreur">' . $_GET['erreurPrenom'] . '</p>';
                                } ?>
                            </div>
                            <div class="entreeModifierAge">
                                <label for="ageModifierProfil1"><span class="material-symbols-rounded">cake</span>Age</label>
                                <input type="date" name="ageModifierProfil" id="ageModifierProfil1" onfocus="setAgeRange()" value="<?php echo strip_tags($_SESSION['dateNaissance']) ?>" required>
                                <?php if (isset($_GET['erreurAge'])) {
                                    echo '<div class="erreurAgeProfil"><p id="error" class="erreur">' . $_GET['erreurAge'] . '</p></div>';
                                } ?>
                            </div>
                            <div class="entreeModifierPhoto">
                                <label class="labelSolide"><span class="material-symbols-rounded">face</span>Photo de profil <span id="labelPhotoModifier">(optionel)</span></label>
                                <input type="file" name="photoModifierProfil" id="photoModifierProfil1" accept=".jpg, .jpeg, .png" class="inputInvisible">
                                <input type="hidden" id="hiddenNamePhoto" name="oldimage" <?php if ($_SESSION['photo'] !== NULL) {
                                                                                                echo 'value="' . strip_tags($_SESSION['photo']) . '"';
                                                                                            }; ?>>
                                <input type="hidden" id="hiddenTaillePhoto" <?php if (isset($photoSize)) {
                                                                                echo 'value="' . $photoSize . '"';
                                                                            } ?>>
                                <div class="containerFauxInput">
                                    <label for="photoModifierProfil1" class="fauxBouton">Choisir une image (PNG, JPG)</label>
                                    <div class="previewImage">
                                        <?php if ($_SESSION['photo'] == NULL || $_SESSION['photo'] == '') {
                                            echo '<p>Aucune image sélectionnée</p>';
                                        }; ?>
                                    </div>
                                </div>
                            </div>
                            <span class="supprimerImage" onclick="supprimerPhoto('supprimerOldImageProfil')"><img src="./images/style/delete.svg"></span>
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
                            <div class="entreeModifierEmail">
                                <label for="emailModifierProfil1"><span class="material-symbols-rounded"> mail </span>Nouveau email</label>
                                <input type="mail" name="emailModifierProfil" id="emailModifierProfil1" maxlength="40" autocomplete="off">
                            </div>
                            <div class="entreeModifierConfirmationEmail">
                                <label for="emailNouveauConfirmationProfil1"><span class="material-symbols-rounded"> mail </span>Confirmation de email</label>
                                <input type="mail" name="emailNouveauConfirmationProfil" id="emailNouveauConfirmationProfil1" maxlength="40" autocomplete="off">
                            </div>
                            <?php if (isset($_GET['erreurEmail2'])) {
                                echo '<p id="error" class="erreur">' . $_GET['erreurEmail2'] . '</p>';
                            } ?>
                            <div class="entreeAncienMotDePasse">
                                <label for="mdpAncienProfil1"><span class="material-symbols-rounded"> lock </span>Ancien mot de passe</label>
                                <input type="password" name="mdpAncienProfil" id="mdpAncienProfil1" autocomplete="new-password">
                            </div>
                            <?php if (isset($_GET['erreurMotDePasseProfilAncien'])) {
                                echo '<p id="error" class="erreur">' . $_GET['erreurMotDePasseProfilAncien'] . '</p>';
                            } ?>
                            <div class="entreeModifierMotDePasse">
                                <label for="mdpProfil1"><span class="material-symbols-rounded"> lock </span>Nouveau mot de passe</label>
                                <div class="boutonVoirMotDePasse">
                                    <input type="password" name="mdpProfil" id="mdpProfil1" minlength="8" maxlength="16" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$.%^&*_=+-]).{8,24}" title="Minimum (a-z / A-Z / 0-9 / !@#$.%^&*_=+-)">
                                    <span onclick="showPassword()" class="material-symbols-rounded">visibility</span>
                                </div>
                            </div>
                            <div class="entreeModifierConfirmationMotDePasse">
                                <label for="mdpConfirmationProfil1"><span class="material-symbols-rounded"> lock </span>Confirmation mot de passe</label>
                                <input type="password" name="mdpConfirmationProfil" id="mdpConfirmationProfil1">
                            </div>
                            <?php if (isset($_GET['erreurMotDePasseProfil'])) {
                                echo '<p id="error" class="erreur">' . $_GET['erreurMotDePasseProfil'] . '</p>';
                            } ?>
                        </div>
                        <div class="containerBoutonModifierProfil">
                            <input type="submit" name="validationModifierProfil" value="MODIFIER" class="boutonModifierProfil">
                            <input type="reset" value="ANNULER" class="boutonAnnuler">
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
<?php include_once('./elements/footer.php');
} else {
    header('Location: index.php?action=Mon-profil');
    exit();
};  ?>