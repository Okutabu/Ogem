function openfile() {
    document.getElementById("mySelector").click();
}

function postform() {
    document.getElementById("submit").click();
}

function coeur(heart) {
    if (heart.classList.contains("liked")) {
        heart.classList.remove("liked");
    } else {
        heart.classList.add("liked");
    }
    
}

function loadFile(event) {
    var output = document.getElementById('output');
    var toDelete = document.getElementById('placeholding');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.style.width = 300;
    toDelete.style.display = "none";
    output.onload = function() {
        URL.revokeObjectURL(output.src) // free memory
    }
};

function show_filters(filterToShow) {
    var filter = document.getElementById(filterToShow);
    var display = filter.style.display;

    if (display == "initial") {
        display = "none";
    } else {
        display = "initial";
    }

    filter.style.display = display;
}

/* Quand l'utilisateur clique le menu s'affiche ou non */
function menuderou() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Ferme le menu déroulant quand l'utilisateur clique en dehors du menu
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {

    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
function updateField($fieldID){
    var champ = document.getElementById($fieldID);
    var dependance = document.getElementById('type_vente-select');
    var value = dependance.options[dependance.selectedIndex].value;
    if (value == "buynowtrue"){
        champ.style.display = "none";
    }
    else
    {
        champ.style.display = "inline-grid";
    }
    console.log("whatsiup");
}