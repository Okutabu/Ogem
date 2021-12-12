function openfile(){
    document.getElementById("mySelector").click();
}

function postform(){
    document.getElementById("submit").click();
}

const heart = document.getElementsByClassName("heart");
heart.addEventListener("click", () => {
    if (heart.classList.contains("liked")) {
        heart.classList.remove("liked");
    } else {
        heart.classList.add("liked");
    }
});