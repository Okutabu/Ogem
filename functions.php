<?php
$data = ['etat' => ["Neuf", "Très bon état", "Bon état", "Moyen"], 'materiaux' => ["Acier", "Argent", "Céramique", "Diamant", "Or", "Platine", "Tungstène", "Autres matériaux"], 'marque' => ["Audemars Piguet", "Breitling", "Grand Seiko", "Hublot", "IWC", "Jaeger-LeCoultre", "Longines", "Omega", "Patek Philippe", "Richard Mille", "Rolex", "Tag Heuer", "Tissot", "Tudor", "Vacheron Constantin", "Autres marques"]];
//----------------- Partie recherche montres ------------------- 

function get_watches_sorted($sort1)
{
    global $bdd;
    
    if ($sort1 == 'prixcroissant') {
        $check = $bdd->prepare('SELECT * FROM watches ORDER BY ? ASC');
    } else {
        $check = $bdd->prepare('SELECT * FROM watches ORDER BY ? DESC');
    }
    if ($sort1 == 'prixdecroissant' || $sort1 == 'prixcroissant') {
        $sort1 = 'prix';
    }
    $check->execute([$sort1]);
    $_SESSION['watches'] = $check->fetchAll(PDO::FETCH_ASSOC); // avec le PDO::FETCH_ASSOC nous précisons que nous voulons un tableau associatif (le fetchAll va bien si le nombre de montres n'est pas trop important, sinon il y aura beaucoup de latence)
    //On met le resultat dans une variable de session pour eviter de devoir refaire des requetes sql a chaque fois
}

function filtre($filtres, $nonfiltres, $filtre, $autre, $montres){

    $montresfiltrees = [];
    if ($montres != [] && $filtres != []){
        
        if ($autre){
            foreach($montres as $montre){
                if (!in_array($montre[$filtre], $nonfiltres)){
                    $montresfiltrees[] = $montre;
                }
            }
        } else {
            foreach($montres as $montre){
                if (in_array($montre[$filtre], $filtres)){
                    $montresfiltrees[] = $montre;
                }
            }
        }
    } elseif ($filtres == []){
        $montresfiltrees = $montres;
    }
    return $montresfiltrees;
}

function filter_watches(){
    global $data;
    //on recupere les valeurs dans les get
    $filters = [];
    $currentSort = '';
    foreach ($_GET as $key => $value) {
        if ($key != "action" && $key != "page" && $value != "" && $key != "sort"){
            if($key == "priceMin" || $key == "priceMax"){
                $filters[$key] = intval($value);
            } else {
                $filters[$key] = $value;
            }
        } elseif ($key == "sort"){
            $currentSort = $value;
        }
    }
    //on rapelle cette fonction pour que ca refasse une requete dans la base de données a chaque fois, si jamais une montre a été ajoutée ou supprimée en temps reel
    get_watches_sorted($currentSort);
    $watches = $_SESSION['watches'];
    //remplace tous les tirets du bas par des espaces dans les clés du tableau
    foreach ($filters as $key => $value){
        unset($filters[$key]);
        $key = str_replace("_", " ", $key);
        $filters[$key] = $value;
    }
    $etats = [];
    $materiaux = [];
    $marques = [];
    $watchesfilter = [];
    $stop = false;
    foreach ($filters as $key => $value){
        //on recupere les valeurs ou il y a des filtres multiples
        if ($value == "on"){
            if (in_array($key, $data["etat"], true)){
                $etats[] = $key;
            } elseif (in_array($key, $data["marque"], true)){
                $marques[] = $key;
            } elseif (in_array($key, $data["materiaux"], true)){
                $materiaux[] = $key;
            }
            //on traite les filtres uniques
        } else {
            foreach ($watches as $watch){
                if ($key == "buy"){
                    if($watch["buy"] == $value){
                        $watchesfilter[] = $watch;
                    }
                } elseif ($key == "priceMin"){
                    if($watch["prix"] >= $value){
                        $watchesfilter[] = $watch;
                    }
                } elseif ($key == "priceMax"){
                    if($watch["prix"] <= $value){
                        $watchesfilter[] = $watch;
                    }
                }
            }
            if ($watchesfilter == []){
                $stop = true;
            } 
            $watches = $watchesfilter;
            $watchesfilter = [];
        }
    }

    //on traite les filtres multiples sauf si le tableau est vide
    if (!$stop){ 
        
        //d'abord on crée des variables dans lesquels il ne faut pas que les montres soient s'il y a un parametre "autres" de coché par l'utilisateur
        $autremat = false;
        $autremarque = false;
        $nonMarques = [];
        $nonMateriaux = [];
        if (in_array("Autres matériaux", $materiaux)){
            $nonMateriaux = array_diff($data['materiaux'], $materiaux);
            $autremat = true;
        }
        if (in_array("Autres marques", $marques)){
            $nonMarques = array_diff($data['marque'], $marques);
            $autremarque = true;
        } 
        //on passe la montre dans des filtres successifs
        $watches = filtre($etats, [], "etat", false, $watches);
        $watches = filtre($marques, $nonMarques, "marque", $autremarque, $watches);
        $watches = filtre($materiaux, $nonMateriaux, "materiaux", $autremat, $watches);
    }
    $_SESSION['watches'] = $watches;
}

function display_prices(){
    global $bdd;
    $priceMin = $bdd->query('SELECT MIN(prix) FROM watches')->fetch(PDO::FETCH_ASSOC);
    $priceMin = $priceMin['MIN(prix)'];
    $priceMax = $bdd->query('SELECT MAX(prix) FROM watches')->fetch(PDO::FETCH_ASSOC);
    $priceMax = $priceMax['MAX(prix)'];
    echo "<div><label>Prix min. </label>";
    echo "<input type='number' name='priceMin' class='priceFilter' min='" . $priceMin . "' max='" . $priceMax . "' placeholder='" . $priceMin . "'></div>";
    echo "<div><label>Prix max. </label>";
    echo "<input type='number' name='priceMax' class='filter priceFilter' min='" . $priceMin . "' max='" . $priceMax . "' placeholder='" . $priceMax . "'></div>";
}

function display_multiple_filters($choice){
    global $data;
    foreach ($data[$choice] as $value) {
        echo "<input type='checkbox' name='" . $value . "'/>" . $value . "<br/>";
    }
    
}

function display_watch()
{
    $watches = $_SESSION['watches'];
    $idheart = 0;
    if ($watches == []){
        echo "<h1 id='noResults'>Aucun résultat</h1>";
    } else {
        foreach ($watches as $watch) {
            echo "<article class='watchtosell'>";
            echo "<h1>" . $watch['name'] . "</h1>";
            echo "<img src='images/watchesPics/" . $watch['image_token']."' alt='Image Montre'/>";
            echo "<div class='bandeau'><p>" . $watch['marque'] . "</p>";
            echo "<form method='post' action='.'>";
            echo" <input type='hidden' name='action' value='like'>";
            echo" <input type='hidden' name='token' value='" . $watch['token'] . "'>";
            echo" <input type='submit' value='♥'>";
            echo" </form>";
            echo "<div class ='likes'><p>" . $watch['likes'] . "</p>";
            echo "<button name='" . $watch['token'] . "' class='heart' id='heart" . $idheart . "' onclick='coeur(heart" . $idheart . ")'></button></div></div>";
    
            if ($watch['buy']) { //Si le vendeur a décidé de vendre la montre de suite et pas aux enchères
                echo "<h2>" . $watch['prix'] . " €</h2>";
                echo "<form method='post' action='.'>";
                echo" <input type='hidden' name='action' value='suppr'>";
                echo" <input type='hidden' name='token' value='" . $watch['token'] . "'>";
                echo" <input type='submit' class='buy buy2' value='Acheter'>";
                echo" </form>";
            } else {
                echo "<h2>Meilleure enchère : " . $watch['prix'] . " €</h2>";
                echo "<form action='.' method='post'><input type='hidden' name='action' value='enchere'>";
                echo "<div><label>Enchère rapide : </label><input type='number' class='bid' name='bid' placeholder='" . $watch['prix'] + 1 . "' required='required' autocomplete='off' min='" . $watch['prix'] + 1 . "'>";
                echo "<input type='submit' class='buy' value='Confirmer'></form></div>";
            }
            echo "</article>";
            $idheart = $idheart + 1;
        }
    }
}

//----------------- FIN Partie recherche montres ------------------- 

//----------------- Fonctions pour la gestion de comptes ------------------- 

function inscription($pseudo1, $mail2, $password3, $retypedPassword4)
{
    global $bdd;
    // Si les variables existent et qu'elles ne sont pas vides
    if (!empty($pseudo1) && !empty($mail2) && !empty($password3) && !empty($retypedPassword4)) {
        // Patch XSS
        $pseudo = htmlspecialchars($pseudo1);
        $email = htmlspecialchars($mail2);
        $password = htmlspecialchars($password3);
        $password_retype = htmlspecialchars($retypedPassword4);

        // On vérifie si l'utilisateur existe
        // $database_instance = new database;
        // $database_instance->users_connect();
        $check = $bdd->prepare('SELECT pseudo, email, password FROM utilisateurs WHERE email = ?');
        $check->execute(array($email));
        $check->fetch();
        $row = $check->rowCount();

        $email = strtolower($email); // on transforme toute les lettres majuscule en minuscule pour éviter que Foo@gmail.com et foo@gmail.com soient deux compte différents 

        // Si la requete renvoie un 0 alors l'utilisateur n'existe pas 
        if ($row == 0) {
            if (strlen($pseudo) <= 100) { // On verifie que la longueur du pseudo <= 100
                if (strlen($email) <= 100) { // On verifie que la longueur du mail <= 100
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) { // Si l'email est de la bonne forme
                        if ($password === $password_retype) { // si les deux mdp saisis sont bon

                            // On hash le mot de passe avec Bcrypt, via un coût de 12
                            $cost = ['cost' => 12];
                            $password = password_hash($password, PASSWORD_BCRYPT, $cost);

                            // On stock l'adresse IP
                            $ip = $_SERVER['REMOTE_ADDR'];

                            // On insère dans la base de données
                            $insert = $bdd->prepare('INSERT INTO utilisateurs(pseudo, email, password, ip, token) VALUES(:pseudo, :email, :password, :ip, :token)');
                            $insert->execute(array(
                                'pseudo' => $pseudo,
                                'email' => $email,
                                'password' => $password,
                                'ip' => $ip,
                                'token' => bin2hex(openssl_random_pseudo_bytes(64))
                            ));
                            // On redirige avec le message de succès
                            $_SESSION['flash'] = 'success';
                            header('Location: .?page=connect');
                            die();
                        } else {
                            $_SESSION['flash'] = 'passwordCorresponding';
                            header('Location: .?page=inscription');
                            die();
                        }
                    } else {
                        $_SESSION['flash'] = 'emailValidity';
                        header('Location: .?page=inscription');
                        die();
                    }
                } else {
                    $_SESSION['flash'] = 'email_length';
                    header('Location: .?page=inscription');
                    die();
                }
            } else {
                $_SESSION['flash'] = 'pseudo_length';
                header('Location: .?page=inscription');
                die();
            }
        } else {
            $_SESSION['flash'] = 'already';
            header('Location:.?page=inscription');
            die();
        }
    }
}

function connexion($mail1, $password2)
{

    global $bdd;
    if (!empty($mail1) && !empty($password2)) // Si il existe les champs email, password et qu'ils ne sont pas vides
    {

        // Patch XSS
        $email = htmlspecialchars($mail1);
        $password = htmlspecialchars($password2);

        $email = strtolower($email); // email transformé en minuscule

        // On regarde si l'utilisateur est inscrit dans la table utilisateurs
        $check = $bdd->prepare('SELECT password, token FROM utilisateurs WHERE email = ?');
        $check->execute(array($email));
        $data = $check->fetch();
        $row = $check->rowCount();

        // Si > à 0 alors l'utilisateur existe
        if ($row > 0) {
            // Si le mot de passe est le bon
            if (password_verify($password, $data['password'])) {
                // On crée la session 
                $req = $bdd->prepare('SELECT * FROM utilisateurs WHERE token = ?');
                $req->execute(array($data['token']));
                $_SESSION['user'] = $req->fetch();

                //si l'on vient de la page de vente sans y etre connecté par exemple, on y est redirigé ensuite
                if (isset($_GET['redirect'])) {
                    header('Location: .?page=' . $_GET['redirect']);
                    die();
                } else {
                    header('Location: .?page=landing');
                    die();
                }

                die();
            } else {
                $_SESSION['flash'] = 'password';
                header('Location: .?page=connect');
                die();
            }
        } else {
            $_SESSION['flash'] = 'notExisting';
            header('Location: .?page=connect');
            die();
        }
    }
}

function welcome()
{
    $data = $_SESSION['user'];
    echo '<img src="images/profilePics/' . $data['picture'] . '" alt="profil picture" id="landimg">';
    echo '<h1>Bonjour ' . $data['pseudo'] . ', vous êtes bien connecté !</h1>';
}

function deconnexion()
{
    unset($_SESSION['user']); //on efface tout ce qui concerne l'utilisateur dans la variable superglobale $_SESSION
    header('Location: .');
    die;
}

//Traitement erreurs & succès

function errors_accounts()
{
    if (isset($_SESSION['flash'])) {
        $err = htmlspecialchars($_SESSION['flash']);
        unset($_SESSION['flash']);
        switch ($err) {
                //pour les connexions
            case 'password':
                echo '<div class="alert"><p><strong>Erreur</strong>, mot de passe incorrect</p></div>';
                break;
            case 'notExisting':
                echo '<div class="alert"><p><strong>Erreur</strong>, le compte n\'existe pas</p></div>';
                break;
                //pour l'inscription
            case 'success':
                echo '<div id="success"><h1>Inscription effectuée avec succès !</h1></div>';
                break;
            case 'passwordCorresponding':
                echo '<div class="alert"><p><strong>Erreur</strong>, les mots de passe ne correspondent pas</p></div>';
                break;
            case 'emailValidity':
                echo '<div class="alert"><p><strong>Erreur</strong>, l\'email n\'est pas valide</p></div>';
                break;
            case 'email_length':
                echo '<div class="alert"><p><strong>Erreur</strong>, l\'email est trop long</p></div>';
                break;
            case 'pseudo_length':
                echo '<div class="alert"><p><strong>Erreur</strong>, le pseudo est trop long</p></div>';
                break;
            case 'already':
                echo '<div class="alert"><p><strong>Erreur</strong>, adresse email déjà utilisée</p></div>';
                break;
        }
    }
}

function forbidden_access()
{
    echo "<script>alert('Accès refusé, veuillez vous connecter');</script>";
    header("Refresh:0.1; url=.?page=home");
    die();
}

function redirection_sell()
{
    header("Location: .?page=connect&redirect=sell");
    die();
}

function apply_redirect()
{
    if (isset($_GET['redirect'])) {
        echo "<form action='.?redirect=" . $_GET['redirect'] . "' method='post'>";
    } else {
        echo "<form action='.' method='post'>";
    }
}

//Affichage du menu de naviguation en fonction de si l'utilisateur est connecté
function profil_connected()
{
    if (isset($_SESSION['user'])) {
        $picture = $_SESSION['user']['picture'];
        echo "<li><a href='.?page=sell'>Vendre</a></li>";
        echo "<div class='dropdown'>";
        echo '<img onclick="menuderou()" src="images/profilePics/' . $picture . '" alt="profil picture" id="profilePic" class="dropbtn">';
        echo "<div id='myDropdown' class='dropdown-content'>";
        echo "<a href='.?page=profile'>Profil</a>";
        echo "<a href='.?page=likes'>Coups de coeur</a>";
        echo "<a href='.?page=deco'>Déconnexion</a>";
        echo "</div></div>";
    } else {
        echo "<div class='dropdown'>";
        echo "<button id='profiltxt' onclick='menuderou()' class='dropbtn'>Profil</button>";
        echo "<div id='myDropdown' class='dropdown-content'>";
        echo "<a href='.?page=connect'>Connexion</a>";
        echo "<a href='.?page=inscription'>Inscription</a>";
        echo "</div></div>";
    }
    echo "</ul></li>";
}

//-----------------------------------------------------------------

function add_watches($user, $brand, $materiaux, $name, $prix, $buy, $etat, $tokenForImage, $dateFinEnchere)
{
   
    if ($buy == "buynowtrue") {
        $buy = 1;
    } else {
        $buy = 0;
    }
    $date = date("Y-m-d");
    if(empty($dateFinEnchere)){
        $dateFinEnchere = date('Y-m-d', strtotime($date. ' + 5 days'));
    }
    global $bdd;
    $sql = "INSERT INTO watches(user, marque, materiaux, name, prix, buy, image_token, token, etat, fin_enchere)
    VALUES (:user, :marque, :materiaux, :name, :prix, :buy, :imagetoken, :token, :etat, :date)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute(array(
        'user' => $user,
        'marque' => $brand,
        'materiaux' => $materiaux,
        'name' => $name,
        'prix' => $prix,
        'buy' => $buy,
        'token' => bin2hex(openssl_random_pseudo_bytes(64)),
        'imagetoken' => $tokenForImage,
        'etat' => $etat,
        'date' => $dateFinEnchere
    ));
    header('Location: .?page=search');
}

function display_choices($choice)
{
    global $data;
    foreach ($data[$choice] as $value) {
        echo "<option value=" . $value . ">" . $value . "</option>";
    }
}

function register_image($files){

    $maxSize = 1000000;
    $validExt = array(".jpeg", ".png", ".jpg");
    $fileSize = $files['toUpload']['size'];
    $fileName = $files['toUpload']['name'];
    $fileExt = ".". strtolower(substr(strrchr($fileName, '.'), 1));
    $tmpName = $files['toUpload']['tmp_name'];
    $imageTokenAndExt = bin2hex(openssl_random_pseudo_bytes(64)).$fileExt;
    $uniqName = "./images/watchesPics/".$imageTokenAndExt;

    if ($fileSize > $maxSize){
        echo "fichier trop lourd";
            die;
    }
    

    $resultat = move_uploaded_file($tmpName, $uniqName);

    if ($resultat){
        echo "transfert terminé";
        return $imageTokenAndExt;
    }
    
    

}
function registerProfileImage($files){

    $maxSize = 1000000;
    $validExt = array(".jpeg", ".png", ".jpg");
    $fileSize = $files['toUpload']['size'];
    $fileName = $files['toUpload']['name'];
    $fileExt = ".". strtolower(substr(strrchr($fileName, '.'), 1));
    $tmpName = $files['toUpload']['tmp_name'];
    $imageTokenAndExt = bin2hex(openssl_random_pseudo_bytes(64)).$fileExt;
    $uniqName = "./images/profilePics/".$imageTokenAndExt;

    if ($fileSize > $maxSize){
        echo "fichier trop lourd";
            die;
    }
    

    $resultat = move_uploaded_file($tmpName, $uniqName);

    if ($resultat){
        echo "transfert terminé";
        return $imageTokenAndExt;
    }
    
    

}
//----------------- Debut Partie achat -------------------
function del($tok){
    global $bdd;
    if (isset($_SESSION['user'])) {
        $deletewatch = $bdd->prepare('DELETE FROM watches WHERE token = ?');
        $deletewatch->execute(array($tok));
        header('Location: .?page=profile');
        die();
    }
    else {
        header('Location: .?page=connect');
        die();
    }
}
//----------------- FIN Partie achat -------------------
//----------------- debut Partie profil montres ------------------- 



function personal_watches(){
    global $bdd;
    $me = $_SESSION['user']['pseudo'];
    $prswatche = $bdd->prepare('SELECT * FROM `watches` WHERE user = ?');
    $prswatche->execute(array($me));

    foreach($prswatche as $watch){
        echo "<article class='personal_watches'>";
        echo "<h1>" . $watch['name'] . "</h1>";
        echo "<img src='images/watchesPics/" . $watch['image_token'] . ".jpg' alt='Image Montre'>";
        echo "<div class='bandeau'><p>" . $watch['marque'] . "</p>";
        echo "<div class ='likes'><p>" . "Likes " .$watch['likes'] . "</p>";
        echo "</article>";
    }
}
//----------------- FIN Partie profil montres -------------------


function approvePost($table){
    $res = True;
    foreach($table as $string){
        if (preg_match('/[\[\]\'^£$%&*()}{@#~?><>,|=+¬]/', $string))
            {
                $res = false;
                
            }
    }
    if (!preg_match('/[0-9]/', $table['price'])) // making sure price is a number;
        {
            $res = false;
            
        }
    
    return $res;
}

function watchFormIncorrect(){ // incomplete 
    header("Location: .?page=sell");
    
}

function likepage(){
    global $bdd;
    $mlikes = $_SESSION['user']['likes'];
    if ($mlikes != "") {
        $mlikes = rtrim($mlikes, "¦");
        $tablikes = explode("¦",$mlikes);
        $idheart = 0;
        foreach($tablikes as $value){
            $prslikes = $bdd->prepare('SELECT * FROM `watches` WHERE token = ?');
            $prslikes->execute(array($value));
            $watch =$prslikes->fetch();
            echo "<article class='watchtosell'>";
            echo "<h1>" . $watch['name'] . "</h1>";
            echo "<img src='images/watchesPics/" . $watch['image_token']."' alt='Image Montre'/>";
            echo "<div class='bandeau'><p>" . $watch['marque'] . "</p>";
            echo "<form method='post' action='.'>";
            echo" <input type='hidden' name='action' value='like'>";
            echo" <input type='hidden' name='token' value='" . $watch['token'] . "'>";
            echo" <input type='submit' value='♥'>";
            echo" </form>";
            echo "<div class ='likes'><p>" . $watch['likes'] . "</p>";
            echo "<button name='" . $watch['token'] . "' class='heart' id='heart" . $idheart . "' onclick='coeur(heart" . $idheart . ")'></button></div></div>";

            if ($watch['buy']) { //Si le vendeur a décidé de vendre la montre de suite et pas aux enchères
                echo "<h2>" . $watch['prix'] . " €</h2>";
                echo "<form method='post' action='.'>";
                echo" <input type='hidden' name='action' value='suppr'>";
                echo" <input type='hidden' name='token' value='" . $watch['token'] . "'>";
                echo" <input type='submit' class='buy buy2' value='Acheter'>";
                echo" </form>";
            } else {
                echo "<h2>Meilleure enchère : " . $watch['prix'] . " €</h2>";
                echo "<form action='.' method='post'><input type='hidden' name='action' value='enchere'>";
                echo "<div><label>Enchère rapide : </label><input type='number' class='bid' name='bid' placeholder='" . $watch['prix'] + 1 . "' required='required' autocomplete='off' min='" . $watch['prix'] + 1 . "'>";
                echo "<input type='submit' class='buy' value='Confirmer'></form></div>";
            }
            echo "</article>";
            $idheart = $idheart + 1;
        }
    }
    
}

function like($watch){
    global $bdd;
    $watch = $watch . "¦";
    $temp = $bdd->prepare('SELECT likes FROM utilisateurs WHERE token = ?');
    $temp->execute(array($_SESSION['user']['token']));
    $tempo = $temp->fetch();
    if (strpos($tempo[0], $watch) || $tempo[0] == $watch){ //si la montre est deja dans la base de données alors on l'enleve
        $watch = str_replace($watch, "", $tempo[0]);
    } else { 
        $watch = $watch . $tempo[0];
    }
    $insert = $bdd->prepare('UPDATE utilisateurs SET likes = ? WHERE token = ? ;');
    $insert->execute(array($watch, $_SESSION['user']['token']));
    reload_session();
    header('Location: .?page=search');
    die();
}

function reload_session(){
    global $bdd;
    $req = $bdd->prepare('SELECT * FROM utilisateurs WHERE token = ?');
    $req->execute(array($_SESSION['user']['token']));
    $_SESSION['user'] = $req->fetch();
}

// function whatever(){
//     $date = date("Y-m-d");
//     $watches = $bdd->prepare('SELECT * FROM watches where USER )
//     if ()
// }

function dateInput(){
    
    echo "type='date' name='date' min='".date("Y-m-d")."'";
}

function updateProfile($image, $usertoken){
    global $bdd;
    $insert = $bdd->prepare('UPDATE utilisateurs SET picture = ? WHERE token = ? ;');
    $insert->execute(array($image, $usertoken));
                                

}