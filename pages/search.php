
<main>
    <h1>Montres d'occasion</h1>
    <article>
        <form action=".?page=search" method="get">
            <input type="hidden" name="action" value="filtrer">
            <?php display_filters(); ?>
            
            <label>Type de vente : </label>
            <input type="text" name="password_retype" class="form-control" placeholder="">
            <input type="submit" class="btn" value="Confirmer">
        </form>
        
    </article>
    <?php
        display_watch()
    ?>  
    <script>const heart = document.getElementsByClassName("heart");

    heart.addEventListener("click", () => {
    if (heart.classList.contains("liked")) {
        heart.classList.remove("liked");
    } else {
        heart.classList.add("liked");
    }
    });</script>
</main>

