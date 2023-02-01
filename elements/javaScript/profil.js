// fonction qui change la visibilité de la recette en prennant comme parametre son id
function changeVisibiliteRecette(idRecette){
    var checkbox = document.getElementById(idRecette);
    var xhr = new XMLHttpRequest();

//ici on change la valeur de la checkbox en fonction de si elle est cochée ou non

    if (checkbox.checked) {
      checkbox.value = "1";
    } else {
      checkbox.value = "0";
    }


    xhr.onreadystatechange = function(){
      if(this.readyState == 4 && this.status == 200){
        console.log(this.response);
      } else if (this.readyState == 4) {
        alert("Une erreur est survenue...")
      }
    };
    xhr.open("POST", "./fonctionnalites/switchVisibiliteRecette.php", true)
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("recette_visibilite=" + checkbox.value +"&recette_id=" + checkbox.id );

    return false;
  }
