<?php
require './source/functions.php';
initialisationSEssion();
$erreurContact = "erreurContact";
$navigationEnCours = "contact";

if (isset($_GET['erreurContact']) && $_GET['erreurContact'] == 'echecEnvoieMail') {


?>

    <?php include_once('./elements/header.php'); ?>

    <main>
        <section>
            <div class="cardErreurRecette">
                <h2>ERREUR ENVOI EMAIL</h2>
                <div class="containerMessageErreurRecette">
                    <div class="nonConnecteEmote">
                        <span class="material-symbols-rounded">cancel_schedule_send</span>
                    </div>
                    <p>Un problème est survenu lors de l'envoi de votre message. <br><a href="index.php?action=contactez-nous">Retour contact</a></p>
                </div>
            </div>
        </section>
    </main>

<?php include_once('./elements/footer.php');
} else {
    header('Location: index.php?action=accueil');
    exit();
} ?>
