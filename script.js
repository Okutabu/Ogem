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

function show_filters(filterToShow) {
    var filter = document.getElementById(filterToShow);
    var display = filter.style.display;

    if (display == "none") {
        display = "initial";
    } else {
        display = "none";
    }

    filter.style.display = display;
}