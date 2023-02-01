     <header class="headerRecettes">
         <nav class="navigationRecettes">
             <div class="liensNavigationRecettes">
                 <a href="index.php?action=recettes-entrees" <?php if (isset($navigationEnCoursRecette) && $navigationEnCoursRecette == 'entrées') {
                                                                    echo ' id="navigationEnCoursRecette"';
                                                                } ?>>Entrées</a>
                 <a href="index.php?action=recettes-plats" <?php if (isset($navigationEnCoursRecette) && $navigationEnCoursRecette == 'plats') {
                                                                echo ' id="navigationEnCoursRecette"';
                                                            } ?>>Plats</a>
                 <a href="index.php?action=recettes-desserts" <?php if (isset($navigationEnCoursRecette) && $navigationEnCoursRecette == 'desserts') {
                                                                    echo ' id="navigationEnCoursRecette"';
                                                                } ?>>Desserts</a>
                 <a href="index.php?action=recettes-gourmandises" <?php if (isset($navigationEnCoursRecette) && $navigationEnCoursRecette == 'gourmandises') {
                                                                        echo ' id="navigationEnCoursRecette"';
                                                                    } ?>>Gourmandises</a>
             </div>
         </nav>
     </header>