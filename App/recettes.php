<?php
require 'source/functions.php';
initialisationSEssion();
$recettes = "recettes";
$navigationEnCours = "recettes";
?>

<?php include_once('./elements/header.php'); ?>

<main>
    <?php if (utilisateurConnecte()) {

        header('Location: index.php?action=recettes-entrees');
        exit();
    } else { ?>
        <section>
            <div class="cardErreurRecette">
                <h2>Vous n'êtes pas connecté</h2>
                <div class="containerMessageErreurRecette">
                    <div class="nonConnecteEmote">
                        <span class="material-symbols-rounded">sensor_occupied</span>
                    </div>
                    <p>Pour accéder aux recettes et aux autres fonctionnalitées du site merci de vous connecter. <a href="index.php?action=authentification">Se connecter</a></p>
                </div>
            </div>
        </section>
    <?php }; ?>
</main>

<?php include_once('./elements/footer.php'); ?>