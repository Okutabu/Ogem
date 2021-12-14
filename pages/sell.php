<section class="form">
    <div class="introduction">
        <h1>Veuillez indiquer les caractéristiques de la montre que vous souhaitez vendre</h1>
    </div>
    <form action="." method="post" class="container" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add_watches" >
        <div class="data">
            <div class="text">
                <div id="champ1"><label>Nom de la montre</label><br/>
                    <input required="required" type="text" name="name"><br/>
                </div>

                <div id="champ2"><label>Marque</label><br/>
                    <select name="brand" id="brand-select">
                        <option value="">Choisir une marque</option>
                        <?php display_choices('marque'); ?>
                    </select>
                </div>

                <div id="champ3"><label>Prix</label><br/>
                    <input required="required" type="number" name="price" min="0"><br/>
                </div>

                <div id="champ4"><label>Type de vente</label><br/>
                    <select name="buy" id="type_vente-select" onchange="updateField('champ7')">
                        <option value="buynowtrue">Achat immédiat</option>
                        <option value="buynowfalse">Enchère</option>
                    </select>
                </div>

                <div id="champ5"><label>Materiaux</label><br/>
                    <select name="materiaux" id="materiaux-select">
                        <option value="">Choisir un matériau</option>
                        <?php display_choices('materiaux'); ?>
                    </select>
                </div>

                <div id="champ6"><label>Etat</label><br/>
                    <select name="etat" id="etat-select">
                        <?php display_choices('etat'); ?>
                    </select>
                </div>

                <div id="champ7"><label>Date de fin de l'enchere</label><br/>
                    <input <?php $_today = getdate();
                    echo "type='date' name='date' min='".date("Y-m-d")?>><br/>    
                </div>


            </div>
            <div class="file-area" onclick="openfile()">
                <input type="file" multiple id="mySelector" name="toUpload" accept="image/*" onchange="loadFile(event)" style="display:none;">  
                <img id="output" />
                <p id="placeholding">Cliquez ici pour uploader une image (Format d'image recommandé 4:3)</p>
            </div>
            
        </div>
        </div>
        <input type="submit" value="Envoyer" style="display:none;" id="submit">
        <input type="image" alt="Submit" src="images/arrow.svg" width="300px" height="300px" onclick="postform()">
    </form>

    


</section>