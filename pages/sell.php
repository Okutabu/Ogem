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
                <input type="text" name="brand" ><br></div>

                <div id="champ3"><label>Prix</label><br>
                <input type="text" name="price" ><br></div>

                <div id="champ4"><label>Type de vente</label><br>
                <input type="text" name="buy" ><br></div>

                <div id="champ5"><label>Materiaux</label><br>
                <input type="text" name="materiaux" ><br></div>
                

            </div>
            <div class="file-area" onclick="openfile();">
                <input type="file" multiple id="mySelector" style="display:none;">
                    <p>Click here to upload image</p>
        </div>
    </div>
  </div>
  <button type="submit" value="Envoyer">
    </form>
    
    
    

</section>