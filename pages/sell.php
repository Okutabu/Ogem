<section class="form">
    <div class="introduction">
        <h1>Veuillez indiquer les caractéristiques de la montre que vous souhaitez vendre</h1>
    </div>
    <form action="." method="post" class="container">
        <input type="hidden" name="action" value="add_watches">
            <div class="data">
                <div class="text">
                <div id="champ1"><label>Nom de la montre</label><br>
                <input type="text" name="name" ><br></div>

                <div id="champ2"><label>Marque</label><br>
                <select name="brand" id="brand-select">
                    <option value="">Choisir une marque</option>
                    <?php
                    foreach ($data["marque"] as $marque){
                    echo "<option value=".$marque.">".$marque."</option>"; } ?>
                </select></div>

                <div id="champ3"><label>Prix</label><br>
                <input type="text" name="price" ><br></div>

                <div id="champ4"><label>Type de vente</label><br>
                <select name="buy" id="type_vente-select">
                    <option value="buynowtrue">Achat immédiat</option>
                    <option value="buynowfalse">Enchère</option>
                </select></div>

                <div id="champ5"><label>Materiaux</label><br>
                <select name="materiaux" id="materiaux-select">
                    <option value="">Choisir un matériau</option>
                    <?php
                    foreach ($data["materiaux"] as $materiau){
                    echo "<option value=".$materiau.">".$materiau."</option>"; } ?>
                </select></div>
                
                <div id="champ6"><label>Etat</label><br>
                    <select name="etat" id="etat-select">
                        <option value="neuf">Neuf</option>
                        <option value="tres bon etat">Très bon état</option>
                        <option value="bon etat">Bon état</option>
                        <option value="moyen">Moyen</option>
                    </select>
                </div>
                

            </div>
            <div class="file-area" onclick="openfile();">
                <input type="file" multiple id="mySelector" style="display:none;">
                    <p>Click here to upload image</p>
        </div>
    </div>
  </div>
  <input type="submit" value="Envoyer" style="display:none;" id="submit">
  <input type="image" alt="Submit" src="images/arrow.png" width="300px" onclick="postform()">
    </form>
    
    
    

</section>