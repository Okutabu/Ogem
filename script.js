function openfile(){
    document.getElementById("mySelector").click();
}

function postform(){
    document.getElementById("submit").click();
}

function coeur(heart){
    if (heart.classList.contains("liked")) {
        heart.classList.remove("liked");
    } else {
        heart.classList.add("liked");
    }
}