<section class="form">
    <div class="introduction">
        <h1>Veuillez indiquer les caractéristiques de la montre que vous souhaitez vendre</h1>
    </div>
    <form action="." method="post" class="container" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add_watches" >
        <div class="data">
            <div class="text">
                <div id="champ1"><label>Nom de la montre</label><br/>
                    <input type="text" name="name"><br/>
                </div>

                <div id="champ2"><label>Marque</label><br/>
                    <select name="brand" id="brand-select">
                        <option value="">Choisir une marque</option>
                        <?php display_choices('marque'); ?>
                    </select>
                </div>

                <div id="champ3"><label>Prix</label><br/>
                    <input type="text" name="price"><br/>
                </div>

                <div id="champ4"><label>Type de vente</label><br/>
                    <select name="buy" id="type_vente-select">
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


            </div>
            <div class="file-area" onclick="openfile()">
                <input type="file" multiple id="mySelector" name="toUpload" accept="image/*" onchange="loadFile(event)" style="display:none;">  
                <img id="output" width="300px;" />
                <p>Click here to upload image</p>
            </div>
            
        </div>
        </div>
        <input type="submit" value="Envoyer" style="display:none;" id="submit">
        <input type="image" alt="Submit" src="images/arrow.png" width="300px" onclick="postform()">
    </form>

    


</section>