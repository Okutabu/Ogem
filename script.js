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
    output.src = URL.createObjectURL(event.target.files[0]);
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

    document.addEventListener("click", {

    });

    filter.style.display = display;
}
