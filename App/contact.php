<?php
require './source/functions.php';
initialisationSEssion();
$contact = "contact";
$navigationEnCours = "contact";
?>

<?php include_once('./elements/header.php');  ?>

<main>
    <section>
        <div class="cardContact">
            <h2>Contactez nous</h2>
            <div class="containerFormulaireContact">
                <form method="post" action="index.php?action=recapitulatif-message">
                    <div class="entreeEmailContact">
                        <label><span class="material-symbols-rounded"> mail </span> Email :</label>
                        <input type="email" name="emailContact" id="emailContact1" maxlength="40" required />
                    </div>
                    <p>Nous ne revendrons pas votre email</p>
                    <div class="entreeMessageContact">
                        <label><span class="material-symbols-rounded"> chat_bubble </span>Votre Message :</label>
                        <textarea name="messageContact" id="messageContact1" rows="10" maxlength="611" placeholder="Votre message..." required></textarea>
                    </div>
                    <div class="containerBoutonEnvoyer">
                        <input type="submit" name="validationContact" value="ENVOYER" class="boutonEnvoyer">
                        <input type="reset" value="ANNULER" class="boutonAnnuler">
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
<?php include_once('./elements/footer.php'); ?>