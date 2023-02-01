$(document).ready(function(){
    $("#search_text").keyup(function(){
      var search = $(this).val();
      $.ajax({
        url: './fonctionnalites/searchDesserts.php',
        type: 'get',
        data: {search:search},
        success: function(data){
          $("#result").html(data);
        }
      });
    });
});
  
function goRecette() {
    var link = this.getElementsByTagName("a")[0];
    window.location.href = link.href;
}


function changeP(index){
  var container = document.getElementById('containerCarousel'+index);
  var scrollLeft = document.querySelector('#scrollLeft');
  var scrollRight = document.querySelector('#scrollRight');
  if (container.scrollLeft <= 1) {
    scrollLeft.disabled = true;
  } else {
    container.scrollLeft -= container.offsetWidth;
    scrollRight.disabled = false;
  }
}

function change(index){
  var container = document.getElementById('containerCarousel'+index);
  var scrollLeft = document.querySelector('#scrollLeft');
  var scrollRight = document.querySelector('#scrollRight');
  if (container.scrollLeft >= container.scrollWidth - container.offsetWidth) {
    scrollRight.disabled = true;
 
  } else {
    container.scrollLeft += container.offsetWidth;
    scrollLeft.disabled = false;

  }
 
}


function commenter(index) {
  var inputCommentaire = document.getElementById("inputCommentaire" + index);
  inputCommentaire.style.display = (inputCommentaire.style.display === "block") ? "none" : "block";
}

// Récupère l'élément de type "form"
const formulaire = document.querySelector('#formCommentaire');
// Si on à bien des message d'erreurs on applique la fonction
if(document.querySelector('#error')){
  // ajoute un écouteur d'événement qui s'active lorsque l'utilisateur clique sur un champ du formulaire
    formulaire.addEventListener('click', () => {
        // cache l'élément de type "div" qui contient le message d'erreur
        document.querySelector('#error').style.display = 'none';
    });  
}
