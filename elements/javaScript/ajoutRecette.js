// On check la taille du fichier avant la soumission du fichier et on vérifie également après que la taille du fichier est valide en php
const input1 = document.getElementById("imageRecette1");
input1.addEventListener("change", function() {
    const file = this.files[0];
    if (file.size > 5242880) { // 5 Mo en bytes
        alert("L'image est trop volumineuse! La taille maximum est de 5 MO.");
        this.value = ""; // Réinitialiser le champ de fichier
    }
});

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
// FIN VERIFICATION DEPOT FICHIER

//SUPPRIMER PHOTO
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
 
        
 }

// SUPPRIMER ETAPE
function supprimerEtape(elementId) {

    var div = document.getElementById(elementId);

    div.parentNode.removeChild(div);
    var textareas = document.querySelectorAll('textarea');
    for (var i = 0; i < textareas.length; i++) {
       
        if(textareas[i].id > elementId && i>3) {
            
        textareas[i].parentNode.id = 'etape' + (i + 1);
        textareas[i].previousElementSibling.setAttribute("for", 'etapeRecette' + (i + 1));
        textareas[i].previousElementSibling.textContent = 'Étape ' + (i + 1);
        textareas[i].id = 'etapeRecette' + (i + 1);        
        textareas[i].name = 'etapeRecette' + (i + 1); 
        textareas[i].nextElementSibling.onclick = function() {supprimerEtape('etape'+i);};                
        }
    }
} 

function ajoutEtape(){

// récupère tous les éléments de type "textarea" présents dans la page
var textareas = document.querySelectorAll('textarea');
// compte le nombre d'éléments de type "textarea"
var textareaCount = textareas.length;


    if (textareaCount >= 10) {
        // affiche un message d'erreur si le nombre maximal de textarea a été atteint
        alert('Vous ne pouvez pas ajouter plus de 10 étapes.');
        return; // arrête l'exécution de la fonction
      }
    textareaCount++; // incrémente le compteur

    // crée une nouvelle div qui va contenir la textarea et le label
    var div = document.createElement('div');
    div.className = 'entreeEtapeRecette';
    div.id = 'etape' + textareaCount;

    // crée un nouvel élément de type "textarea"
    var textarea = document.createElement('textarea');
    textarea.id = 'etapeRecette' + textareaCount; // donne un identifiant unique à la textarea
    textarea.name = 'etapeRecette' + textareaCount; // donne un nom  à la textarea
    textarea.rows = '5'; // donne un nombre de ligne initial
    textarea.maxLength = '1000'; // donne une longueur max de caractères

    // crée un nouvel élément de type "label" et lui attribue un attribut "for" qui correspond à l'identifiant de la textarea
    var label = document.createElement('label');
    label.setAttribute('for', textarea.id);    
    label.textContent = 'Étape ' + textareaCount; // ajoute du texte au label

    // crée une span et lui ajoute du texte
    var span = document.createElement('span');
    span.className = 'material-symbols-rounded edit'
    span.textContent = 'edit_square';
     // ajoute la span en premier enfant de l'élément de type "label"
     label.insertBefore(span, label.firstChild);

     //crée une span qui servira de bouton
     var spann = document.createElement('span');
     spann.onclick = function(){supprimerEtape('etape'+textareaCount);};
     spann.className = 'supprimerEtape'
 
     // On créé l'image de surppression
     var image = document.createElement('img');
     image.src ="images/style/delete.svg";
 
     // On l'ajoute à la span
     spann.appendChild(image);

    // ajoute la textarea et le label à la nouvelle div
    div.appendChild(label);
    div.appendChild(textarea);
    div.appendChild(spann);

    // ajoute la nouvelle div à la div "textarea-container"
    document.getElementById('textarea-container').appendChild(div);
};


document.getElementsByTagName('form')[0].addEventListener('submit', function(event) {
    // récupère les éléments de type "textarea" du formulaire
    var textareas = document.getElementsByTagName('textarea');
    // vérifie si un élément de type "textarea" n'est pas rempli, mais que le suivant oui
    for (var i = 0; i < textareas.length - 1; i++) {
      if (textareas[i].value == '' && textareas[i+1].value != '') {
        // affiche un message d'erreur dans l'élément de type "div"
        document.getElementById('error-message').textContent = 'Vous avez oublié une étape.';
        event.preventDefault(); // empêche la soumission du formulaire
        return;
      }
    }
}); 


//SUPPRIMER INGREDIENT
function supprimerIngredient (elementId){

    // On récupère la div, donc l'ensemble de la ligne ingrédient à supprimer
    var ingredientGlobale = document.getElementById(elementId);

    // On supprime la div sélectionné
    ingredientGlobale.parentNode.removeChild(ingredientGlobale);

    // On doit mettre à jour les id, name des éléments suivant donc si on supprime la ligne ingredient4 la ligne ingredient5 doit devenir ingredient4 etc
    // On récupère toutes les containerGlobale
    var containerGlobale = document.querySelectorAll('.containerGlobale') 
    
    for( let i = 0; i < containerGlobale.length; i ++){

        containerGlobale[i].id = 'containerGlobale' + (i + 4)
        containerGlobale[i].children[0].id = 'containerQuantite' + (i + 4)
        containerGlobale[i].children[0].firstChild.id = 'quantite' + (i + 4)        
        containerGlobale[i].children[0].firstChild.name = 'quantite' + (i + 4)

        containerGlobale[i].children[1].id = 'containerUniteMesure' + (i + 4)
        containerGlobale[i].children[1].firstChild.id = 'uniteMesure' + (i + 4)
        containerGlobale[i].children[1].firstChild.name = 'uniteMesure' + (i + 4)

        containerGlobale[i].children[2].id = 'containerIngredient' + (i + 4)
        containerGlobale[i].children[2].firstChild.id = 'ingredient' + (i + 4)
        containerGlobale[i].children[2].firstChild.name = 'ingredient' + (i + 4)
       
        
    // On récupère tous les éléments ayant la classe supprimerIngredient pour mettre à jour les id et name
    var elementsSup = document.querySelectorAll('.supprimerIngredient');
    for (var j = 0; j < elementsSup.length; j++) {

        var element = elementsSup[j];
        // On met à jour l'attribut onclick avec la nouvelle valeur
        element.setAttribute('onclick', 'supprimerIngredient(`containerGlobale' + (j+4) + '`)');
    }
    }
   
}



// AJOUT INGREDIENT
  function ajoutIngredient(){

    // récupère tous les éléments avec la classe quantite et unite présents dans la page
    var inputs =  document.querySelectorAll('.quantite');
    var selects =  document.querySelectorAll('.unite');
    // compte le nombre d'éléments avec la classe quantite et unite puisque
    var inputsCount = inputs.length;
    var selectsCount = selects.length;

        if (inputsCount >= 15 ) {
            // affiche un message d'erreur si le nombre maximal d'ingrédient a été atteint
            alert('Vous ne pouvez pas ajouter plus de 15 ingredients.');
            return; // arrête l'exécution de la fonction
          }
          // incrémente les compteurs
          inputsCount++; 
          selectsCount++;
    
        // crée des nouvelles div qui vont contenir les différents champs
        var div0 = document.createElement('div');
        div0.className = 'containerGlobale';
        div0.id = 'containerGlobale' + inputsCount;

        var div1 = document.createElement('div');
        div1.className = 'containerQuantite';
        div1.id = 'containerQuantite' + inputsCount;

        var div2 = document.createElement('div');
        div2.className = 'containerUniteMesure';
        div2.id = 'containerUniteMesure' + inputsCount;

        var div3 = document.createElement('div');
        div3.className = 'containerIngredient';
        div3.id = 'containerIngredient' + inputsCount;
    
        // crée un nouvel élément de type "input"
        var input = document.createElement('input');
        input.type = 'text';
        input.maxLength = '6';
        input.name = 'quantite' + inputsCount; 
        input.id = 'quantite' + inputsCount; 
        input.className = 'quantite';

        // crée un nouvel élément de type "select"
        var select = document.createElement('select');
        select.name = 'uniteMesure' + selectsCount;
        select.id = 'uniteMesure' + selectsCount; 
        select.className = 'unite';

        // récupère le "select" source
        const select1 = document.getElementById('uniteMesure1');

        // parcourt chaque "option" du "select" source
        for (let indexOption = 0; indexOption < select1.options.length; indexOption++) {
        // crée une nouvelle "option"
        const option = document.createElement('option');

        // copie les valeurs de l'option source
        option.value = select1.options[indexOption].value;
        option.text = select1.options[indexOption].text;

        // ajoute l'option à la liste cible
        select.appendChild(option);
        }

        // crée un nouvel élément de type "input"
        var input2 = document.createElement('input');
        input2.name = 'ingredient' + inputsCount; 
        input2.id = 'ingredient' + inputsCount; 
        input2.maxLength = '35';
        input2.className = 'ingredient';

        // On crée la span qui servira de bouton suppression
        var span = document.createElement('span');
        span.onclick = function(){supprimerIngredient('containerGlobale' + inputsCount);};
        span.className = 'supprimerIngredient'

        // On créé l'image de surppression
        var image = document.createElement('img');
        image.src ="images/style/delete.svg";
        
        // On l'ajoute à la span
        span.appendChild(image);
        
       // Ajoute les différents champs aux différentes div
        div1.appendChild(input);
        div2.appendChild(select);
        div3.appendChild(input2);

        div0.appendChild(div1);
        div0.appendChild(div2);
        div0.appendChild(div3);
        div0.appendChild(span);
    
        // ajoute la nouvelle div à la div "#inputContainer"
        document.getElementById('inputContainer').appendChild(div0);
        
    };


  
    
  
// Récupère l'élément de type "form"
const formulaire = document.querySelector('#formulaireAjoutRecette');
// Si on à bien des message d'erreurs on applique la fonction
if(document.querySelector('#error')){
  // ajoute un écouteur d'événement qui s'active lorsque l'utilisateur clique sur un champ du formulaire
    formulaire.addEventListener('click', () => {
        // cache l'élément de type "div" qui contient le message d'erreur
        document.querySelector('#error').style.display = 'none';
    });  
}



    