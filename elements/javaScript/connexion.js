// Fonction JavaScript pour voir/masquer le mot de passe dans connexion
function showPassword2() {
    // Récupération du champ de mot de passe
    var passwordInput2 = document.getElementById("motDePasseConnexion1");
  
    // Si le champ est actuellement en mode "mot de passe" (type="password")
    if (passwordInput2.type === "password" ) {
      // On le met en mode "texte en clair" (type="text")
      passwordInput2.type = "text";
    } else {
      // Sinon, c'est qu'il est déjà en mode "texte en clair", on le remet donc en mode "mot de passe"
      passwordInput2.type = "password";
    }
  }
