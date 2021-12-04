<script>const heart = document.querySelector(".heart");

heart.addEventListener("click", () => {
  if (heart.classList.contains("liked")) {
    heart.classList.remove("liked");
  } else {
    heart.classList.add("liked");
  }
});</script>

<?php
    display_watch()
?>  
