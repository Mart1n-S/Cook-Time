<?php
require 'source/functions.php';
initialisationSEssion();
$navigationEnCours = "profil";
$succesProfil = "succesProfil";



if (isset($_SESSION['succesInscription']) && $_SESSION['succesInscription'] === true || isset($_SESSION['succesModificationProfil']) && $_SESSION['succesModificationProfil'] === true) {

    if (isset($_SESSION['succesInscription']) &&  $_SESSION['succesInscription'] === true) {
        $inscription2 = true;
    }
    $_SESSION['succesInscription'] = false;
    $_SESSION['succesModificationProfil'] = false;
?>

    <?php include_once('./elements/header.php');

    ?>
    <main>
        <section>
            <div class="cardSucces">
                <h2><?php if (isset($inscription2) && $inscription2 == true) {
                        echo 'INSCRIPTION RÉUSSITE';
                    } else {
                        echo 'PROFIL MODIFIÉ AVEC SUCCÈS';
                    } ?><span class="material-symbols-rounded">celebration</span></h2>
                <div class="containerSucces">
                    <div class="logoSite">
                        <img src="./images/logoSiteRecettes.png" alt="logo site de recettes de cuisine">
                    </div>

                    <?php if (isset($inscription2) && $inscription2 == true) {
                        echo '<p>Votre inscription a bien été effectuée.</p>';
                    } else {
                        echo '<p>Votre profil a bien été modifié.</p>';
                    } ?>

                    <div class="retourProfil">
                        <?php if (isset($inscription2) && $inscription2 == true) {
                            echo '<a href="index.php?action=authentification" class="boutonRetourRetourConnexion">SE CONNECTER</a>';
                        } else {
                            echo '<a href="index.php?action=mon-profil" class="boutonRetourProfil">RETOURNER AU PROFIL</a>';
                        } ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php include_once('./elements/footer.php');
} else {
    header('Location: index.php?action=accueil');
    exit();
};  ?>