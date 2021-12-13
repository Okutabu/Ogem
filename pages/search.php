<main>
    <h1>Montres d'occasion</h1>
    <article>
        <form id="filtres" method="get">
            <input type="hidden" name="action" value="filtrer">
            <?php display_filters(); ?>
            <div><label>Type de vente : </label>
            <input type="text" name="buy" class="form-control" placeholder=""></div>
            <input type="submit" class="btn" value="Confirmer">
        </form>
        <form action="." method="post">
            <input type="hidden" name="action" value="trier">
        </form>
        
    </article>
    <?php
        display_watch()
    ?>  
</main>

