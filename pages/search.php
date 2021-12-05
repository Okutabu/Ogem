<script>const heart = document.querySelector(".heart");

heart.addEventListener("click", () => {
if (heart.classList.contains("liked")) {
    heart.classList.remove("liked");
} else {
    heart.classList.add("liked");
}
});</script>
<main>
    <h1>Montres d'occasion</h1>
    <article>
        <form action="." method="post">
            <input type="hidden" name="action" value="filtrer">
            <?php display_prices(); ?>
            <label>Marque : </label>
            <input type="text" name="password_retype" class="form-control" placeholder="">
            <label>Matériau : </label>
            <input type="text" name="password_retype" class="form-control" placeholder="">
            <label>Type de vente : </label>
            <input type="text" name="password_retype" class="form-control" placeholder="">
            <label></label>
            <input type="text" name="password_retype" class="form-control" placeholder="">
            <input type="submit" class="btn" value="Inscription">
        </form>
        <form action="." method="post">
            <input type="hidden" name="action" value="trier">
        </form>
        
    </article>
    <?php
        display_watch()
    ?>  
</main>

