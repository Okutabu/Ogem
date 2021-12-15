<main>
    <form action="." method="post" class="container" enctype="multipart/form-data">
        <input type="hidden" name="action" value="changeProPic" >
        <div id="profile_picture">
                    <h1>Changer sa photo de profil</h1>
                <div class="file-area" onclick="openfile()">
                        <input type="file" multiple id="mySelector" name="toUpload" accept="image/*" onchange="loadFile(event)" style="display:none;">  
                        <img id="output" />
                        <p id="placeholding">Cliquez ici pour uploader une image</p>
                    </div>
                    <input type="submit" value="Envoyer" style="display:none;" id="submit">
                    <input type="image" alt="Submit" src="images/arrow.svg" width="100px" height="300px" onclick="postform()">
        </div>
    </form>
    
    <h1>Vos montres</h1>
    <?php
        personal_watches();
    ?>
</main>