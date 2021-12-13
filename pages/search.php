<main>
    <h1>Montres d'occasion</h1>
    <article>
        <form id="filtres" method="get">
            <input type="hidden" name="action" value="filtrer">
            <input type="hidden" name="page" value="search">
            <?php display_prices(); ?>
            <div class="multiple">
                <label>Marque : </label>
                <div class="multipleFilters" >
                    <p onclick="show_filters('filter1');">Choisir un filtre</p>
                    <div id="filter1">
                        <?php display_multiple_filters("marque"); ?>
                    </div>
                </div>
            </div>
            <div class="multiple">
                <label>Matériau : </label>
                <div class="multipleFilters" >
                    <p onclick="show_filters('filter2');">Choisir un filtre</p>
                    <div id="filter2">
                        <?php display_multiple_filters("materiaux"); ?>
                    </div>
                </div>
            </div>
            <div class="multiple">
                <label>État : </label>
                <div class="multipleFilters" >
                    <p onclick="show_filters('filter3');">Choisir un filtre</p>
                    <div id="filter3">
                        <?php display_multiple_filters("etat"); ?>
                    </div>
                </div>

            </div>
            <div>
                <label>Type de vente : </label>
                <select name="buy">
                    <option value="all">Tous types</option>
                    <option value="buynowtrue">Achat immédiat</option>
                    <option value="buynowfalse">Enchère</option>
                </select>
            </div>
            <div>
                <label>Trier par : </label>
                <select name="filter">
                    <option value="views">Populaire</option>
                    <option value="date">Le plus récent</option>
                    <option value="prix croissant">Prix : Croissant</option>
                    <option value="prix decroissant">Prix : Décroissant</option>
                    <option value="fin_enchere">Enchère : Fin proche</option>
                </select>
            </div>

            <input type="submit" id="search" value="Confirmer">
        </form>
        <form action="." method="post">
            <input type="hidden" name="action" value="trier">
        </form>

    </article>
    <?php
    display_watch()
    ?>
</main>