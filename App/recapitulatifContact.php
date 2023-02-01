<?php
require 'source/functions.php';
initialisationSEssion();
$navigationEnCours = "contact";
$recapitulatifContact = "recapitulatifContact";
?>


<?php include_once('./elements/header.php'); ?>

<main>
    <?php
    if ($_POST['validationContact']) {
        $postData = $_POST;
        $resultatSupressionEspaces = trim($postData['messageContact']);

        if ((!isset($postData['emailContact']) || !filter_var($postData['emailContact'], FILTER_VALIDATE_EMAIL)) || (!isset($postData['messageContact']) || empty($resultatSupressionEspaces))) {
    ?>
            <section>
                <div class="cardErreurContact">
                    <h2>ERREUR</h2>
                    <div class="containerMessageErreur">
                        <div class="badEmote">
                            <span class="material-symbols-rounded">mood_bad</span>
                        </div>
                        <p>Il faut un email et un message valides pour soumettre le formulaire. <a href="index.php?action=contactez-nous">Retour à la page contact</a></p>
                    </div>
                </div>
            </section>

        <?php  } else {
            $emailRecapitulatifContact = $postData['emailContact'];
            $messageRecapitulatifContact = wordwrap($resultatSupressionEspaces, 70, "\r\n");
            $destinataire = "contact.cooktime@cook-time.online";
            $sujet = "Contact Cook-Time";
            $contenuEnvoyerEmailHebergeur = "Email : " . $emailRecapitulatifContact . "\nMessage : " . $messageRecapitulatifContact;
            $expediteur = "From: " . $emailRecapitulatifContact;
            $envoieMail = mail($destinataire, $sujet, $contenuEnvoyerEmailHebergeur, $expediteur);

             if (!$envoieMail) {

                $erreurEnvoieMail = "echecEnvoieMail";
                header('Location: index.php?action=erreur-contact&erreurContact=' . $erreurEnvoieMail . '');
                exit;
            }
        ?>
            <section>
                <div class="cardRecapitulatif">
                    <h2>Message bien reçu ! <span class="material-symbols-rounded">rocket_launch</span></h2>
                    <div class="containerRecapitulatif">
                        <h3>Récapitulatif de votre envoie :</h3>
                        <div class="recapitulatifEmail">
                            <label><span class="material-symbols-rounded"> mail </span> Email :</label>
                            <input name="emailRecapitulatif" type="text" value=<?php echo ($emailRecapitulatifContact) ?> readonly disabled>
                        </div>
                        <div class="recapitulatifMessage">
                            <label><span class="material-symbols-rounded"> chat_bubble </span>Votre Message :</label>
                            <textarea name="messageRecapitulatif" readonly disabled><?php echo strip_tags($messageRecapitulatifContact) ?></textarea>
                        </div>
                        <div class="btnRetour">
                            <a href="index.php?action=accueil" class="boutonRetournAccueil">RETOUR ACCUEIL</a>
                        </div>
                    </div>
                </div>
            </section>
        <?php }; ?>
</main>

<?php include_once('./elements/footer.php');
    } else {
        header('Location: index.php?action=accueil');
    } ?>