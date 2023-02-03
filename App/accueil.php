<?php
require 'source/functions.php';
initialisationSEssion();
$accueil = "accueil";
$navigationEnCours = "accueil";
?>

<?php include_once('./elements/header.php'); ?>

<main>
    <section>
        <div class="cardAccueil">
            <h2>Bienvenue sur Cook-Time</h2>
            <div class="containerPresentationAccueil">
                <h2>Qui sommes-nous ?</h2>
                <p>Nous ? Plutôt qui suis-je ? Je suis un étudiant en informatique et pour m'entraîner je me suis lancé dans ce projet qui est un site de recettes de cuisine et qui est totalement fonctionnel.<br>Ce site est avant tout pour moi et mon entourage je n'attends pas un grand public, donc il est fort possible que des erreurs s'y cachent...j'en suis désolé d'avance.<br>Si vous voulez participer aux recettes n'hésiter pas à vous inscrire, bien évidemment les données ne sont pas revendues ! C'est juste un site pour m'entraîner mais qui reste fonctionnel !<br>Voilà c'est tout pour moi, je vous laisse découvrir le reste.</p>
                <h2>Des recettes multiples</h2>

                <div class="containerPhotosAccueil">
                    <div class="gridPhotosAccueil">
                        <a href="index.php?action=recettes-entrees" class="lienImageAccueil">
                            <img src="./images/taboulet.jpg" class="photoAccueil" alt="image taboulet cliquable" title="Accéder aux entrées">
                            <div class="titrePhotoHover">Entrées</div>
                        </a>
                        <a href="index.php?action=recettes-plats" class="lienImageAccueil">
                            <img src="./images/parmentier.jpg" class="photoAccueil" alt="image parmentier cliquable" title="Accéder aux plats">
                            <div class="titrePhotoHover">Plats</div>
                        </a>
                        <a href="index.php?action=recettes-desserts" class="lienImageAccueil">
                            <img src="./images/fraisier.jpg" class="photoAccueil" alt="image fraisier cliquable" title="Accéder aux desserts">
                            <div class="titrePhotoHover">Desserts</div>
                        </a>
                        <a href="index.php?action=recettes-gourmandises" class="lienImageAccueil">
                            <img src="./images/brioche.jpg" class="photoAccueil" alt="image brioche cliquable" title="Accéder aux gourmandises">
                            <div class="titrePhotoHover">Gourmandises</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include_once('./elements/footer.php'); ?>
