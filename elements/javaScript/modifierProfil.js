function setAgeRange() {

    var today = new Date();
    var minAge = new Date(today.getFullYear() - 99, today.getMonth(), today.getDate());
    var maxAge = new Date(today.getFullYear() - 10, today.getMonth(), today.getDate() + 1);
    document.getElementById("ageModifierProfil1").min = minAge.toISOString().substr(0, 10);
    document.getElementById("ageModifierProfil1").max = maxAge.toISOString().substr(0, 10);
}

// On check la taille du fichier avant la soumission du fichier et on vérifie également après que la taille du fichier est valide en php
var urlImage = document.getElementById("hiddenNamePhoto").value;
var photo = document.getElementById("hiddenTaillePhoto").value;
const input1 = document.getElementById("photoModifierProfil1");
if(photo || input1){
input1.addEventListener("change", function() {
    const file = this.files[0];
    if (file.size > 5242880) { // 5 Mo en bytes
        alert("L'image est trop volumineuse! La taille maximum est de 5 MO.");
        this.value = ""; // Réinitialiser le champ de fichier
    }
});
}
// S'il y avait déjà une image on la présélectionne

var imageSize = document.getElementById("hiddenTaillePhoto").value;
if(urlImage !== null && urlImage !== '' ){
    const preview = document.querySelector(".previewImage");
    const list = document.createElement("ol");
    preview.appendChild(list);


    const listItem = document.createElement("li");
    const para = document.createElement("p");
    var urlImage = document.getElementById("hiddenNamePhoto").value;
    var inputHidden = document.createElement("input");
    inputHidden.type = 'hidden';
    inputHidden.name = 'previewImageModifierProfil';

    const image = document.createElement("img");
    image.src = "./uploads/photoProfil/"+urlImage ;
    para.textContent = `Nom du fichier : ${urlImage} Taille : ${returnFileSize(imageSize)}.`;

    listItem.appendChild(image);
    listItem.appendChild(para);
    listItem.appendChild(inputHidden);

    list.appendChild(listItem);
    
} 

// VERIFICATION DEPOT FICHIER
// Ici on vérifie si un fichier à été séléctionné
const input = document.querySelector(".inputInvisible");
const preview = document.querySelector(".previewImage");

input.addEventListener("change", updateImageDisplay);

function updateImageDisplay() {
    while (preview.firstChild) {
        preview.removeChild(preview.firstChild);
    }

    const curFiles = input.files;
    if (curFiles.length === 0) {                        // Si non on affiche rien
        const para = document.createElement("p");
        para.textContent = "Aucune image sélectionée";
        preview.appendChild(para);                    
    } else {                                            // Si oui on affiche la preview
        const list = document.createElement("ol");
        preview.appendChild(list);

        for (const file of curFiles) {
            const listItem = document.createElement("li");
            const para = document.createElement("p");

            if (validFileType(file)) {
                para.textContent = `Nom du fichier : ${file.name} Taille : ${returnFileSize(file.size)}.`;
                const image = document.createElement("img");
                image.src = URL.createObjectURL(file);

                listItem.appendChild(image);
                listItem.appendChild(para);
            } else {                                         // Mais si un type de fichier invalide est séléctionné on affiche une erreur
                para.textContent = `Nom du fichier ${file.name}: Le type de fichier n'est pas valide, changer votre sélection.`;  
                listItem.appendChild(para);
            }

            list.appendChild(listItem);
        }
    }
}

// Ici la function qui est utilisé plus haut permettant la vérification de l'extension du fichier 
const fileTypes = [
    "image/jpg",
    "image/jpeg",
    "image/pjpeg",
    "image/png",
];

function validFileType(file) {
    return fileTypes.includes(file.type);
}

function returnFileSize(number) {
    if (number < 1024) {
        return number + "bytes";
    } else if (number > 1024 && number < 1048576) {
        return (number / 1024).toFixed(1) + "KB";
    } else if (number > 1048576) {
        return (number / 1048576).toFixed(1) + "MB";
    }
}

function supprimerPhoto(value){

    // Récupération des éléments
    var image = document.querySelector(".previewImage img");
    var para = document.querySelector(".previewImage p");
    var ol = document.querySelector("ol")
 
    // Suppression des éléments
    image.remove();
    para.remove();
    ol.remove();
 
    // Création preview vide
    var preview = document.querySelector(".previewImage");
    var para = document.createElement("p");
         para.textContent = "Aucune image sélectionée";          
    preview.appendChild(para);
    
 
         $.ajax({
             type: "POST",
             url: "./App/modifierProfil.php",
             data: { value: value }
         });
}

// FIN VERIFICATION DEPOT FICHIER

// VERIFICATION EMAIL IDENTIQUE
var email = document.getElementById("emailModifierProfil1");
var confirmEmail = document.getElementById("emailNouveauConfirmationProfil1");

function validateEmail(){
    if (confirmEmail.value != email.value) {
        confirmEmail.setCustomValidity("Les emails ne correspondent pas.");
    } else {
        confirmEmail.setCustomValidity('');
    }
}

email.onchange = validateEmail;        
confirmEmail.onkeyup = validateEmail;
// FIN VERIFICATION EMAIL IDENTIQUE

// Fonction JavaScript pour voir/masquer le mot de passe dans inscription
function showPassword() {
    // Récupération du champ de mot de passe
    var oldPassword = document.getElementById("mdpAncienProfil1");
    var passwordInput1 = document.getElementById("mdpProfil1");
    var confirmPasswordInput = document.getElementById("mdpConfirmationProfil1");
  
    // Si le champ est actuellement en mode "mot de passe" (type="password")
    if (passwordInput1.type === "password" ) {
      // On le met en mode "texte en clair" (type="text")
      oldPassword.type = "text";
      passwordInput1.type = "text";
      confirmPasswordInput.type = "text";
    } else {
      // Sinon, c'est qu'il est déjà en mode "texte en clair", on le remet donc en mode "mot de passe"
      oldPassword.type = "password";
      passwordInput1.type = "password";
      confirmPasswordInput.type = "password";
    }
  }

// Vérification mdp identique

var password = document.getElementById("mdpProfil1");
var confirmPassword = document.getElementById("mdpConfirmationProfil1");

function validatePassword(){
    if (confirmPassword.value != password.value) {
    confirmPassword.setCustomValidity("Les mots de passes ne correspondent pas.");
    } else {
    confirmPassword.setCustomValidity('');
    }
}

password.onchange = validatePassword;        
confirmPassword.onkeyup = validatePassword;
// FIN VERIFICATION MOT DE PASSE IDENTIQUE


// GESTION AFFICHAGE ERREUR

// Récupère l'élément de type "form"
const formulaire = document.querySelector('#formulaireModifierProfil');
// Si on à bien des message d'erreurs on applique la fonction
if(document.querySelector('#error')){
  // ajoute un écouteur d'événement qui s'active lorsque l'utilisateur clique sur un champ du formulaire
    formulaire.addEventListener('click', () => {
        // cache l'élément de type "div" qui contient le message d'erreur
        document.querySelector('#error').style.display = 'none';
    });  
}
