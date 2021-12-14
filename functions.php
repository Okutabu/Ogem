<?php
$data = ['etat' => ["Neuf", "Très bon état", "Bon état", "Moyen"], 'materiaux' => ["Acier", "Argent", "Céramique", "Diamant", "Or", "Platine", "Tungstène", "Autres matériaux"], 'marque' => ["Audemars Piguet", "Breitling", "Grand Seiko", "Hublot", "IWC", "Jaeger-LeCoultre", "Longines", "Omega", "Patek Philippe", "Richard Mille", "Rolex", "Tag Heuer", "Tissot", "Tudor", "Vacheron Constantin", "Autres marques"]];
//----------------- Partie recherche montres ------------------- 

function get_watches_sorted($sort1)
{
    global $bdd;
    
    if ($sort1 == 'date' || $sort1 == 'prixcroissant') {
        if ($sort1 == 'prixcroissant') {
            $sort1 = 'prix';
        }
        $check = $bdd->prepare('SELECT * FROM watches ORDER BY ? ASC');
    } else {
        if ($sort1 == 'prixdecroissant') {
            $sort1 = 'prix';
        }
        $check = $bdd->prepare('SELECT * FROM watches ORDER BY ? DESC');
    }
    $check->execute([$sort1]);
    $_SESSION['watches'] = $check->fetchAll(PDO::FETCH_ASSOC); // avec le PDO::FETCH_ASSOC nous précisons que nous voulons un tableau associatif (le fetchAll va bien si le nombre de montres n'est pas trop important, sinon il y aura beaucoup de latence)
    //On met le resultat dans une variable de session pour eviter de devoir refaire des requetes sql a chaque fois
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
    $etats = [];
    $materiaux = [];
    $marques = [];
    $watchesfilter = [];
    $stop = false;
    foreach ($filters as $key => $value){
        //on recupere les valeurs ou il y a des filtres multiples
        if ($value == "on"){
            if (in_array($key, $data["etat"], true)){
                $etats[] = $value;
            } elseif (in_array($key, $data["marque"], true)){
                $marques[] = $value;
            } elseif (in_array($key, $data["materiaux"], true)){
                $materiaux[] = $value;
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
    unset($watchesfilter);
    //on traite les filtres multiples sauf si le tableau est vide
    if (!$stop){
        foreach ($watches as $watch){
            null;
        }
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
            echo "<img src='images/watchesPics/" . $watch['image_token']."' alt='Image Montre'  width='150px' height='150px'>";
            echo "<div class='bandeau'><p>" . $watch['marque'] . "</p>";
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
        echo "<li id='menuderoulant'>";
        echo '<img src="images/profilePics/' . $picture . '" alt="profil picture" id="profilePic">';
        echo "<ul id='sousmenu'>";
        echo "<li><a href='.?page=profile'>Profil</a></li>";
        echo "<li><a href='.?page=likes'>Coups de coeur</a></li>";
        echo "<li><a href='.?page=deco'>Déconnexion</a></li>";
    } else {
        echo "<li id='menuderoulant'>";
        echo '<p>Profil</p>';
        echo "<ul id='sousmenu'>";
        echo "<li><a href='.?page=connect'>Connexion</a></li>";
        echo "<li><a href='.?page=inscription'>Inscription</a></li>";
    }
    echo "</ul></li>";
}

//-----------------------------------------------------------------

function add_watches($user, $brand, $materiaux, $name, $prix, $buy, $etat, $tokenForImage)
{

    if ($buy == "buynowtrue") {
        $buy = 1;
    } else {
        $buy = 0;
    }
    global $bdd;
    $sql = "INSERT INTO watches(user, marque, materiaux, name, prix, buy, image_token, token, etat)
    VALUES (:user, :marque, :materiaux, :name, :prix, :buy, :imagetoken, :token, :etat)";
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
        'etat' => $etat
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

    $maxSize = 100000;
    $validExt = array(".jpeg", ".png", ".jpg");
    $fileSize = $files['toUpload']['size'];
    $fileName = $files['toUpload']['name'];
    $fileExt = ".". strtolower(substr(strrchr($fileName, '.'), 1));
    $tmpName = $files['toUpload']['tmp_name'];
    $imageTokenAndExt = bin2hex(openssl_random_pseudo_bytes(64)).$fileExt;
    $uniqName = "./images/watchesPics/".$imageTokenAndExt;

    // if ($fileSize > $maxSize){
    //     echo "fichier trop lourd";
            // die;
    // }
    // if (!in_array($fileExt, $validExt)){
    //     echo "Le fichier n'est une image adaptée";
    //     die;
    // }

    $resultat = move_uploaded_file($tmpName, $uniqName);

    if ($resultat){
        echo "transfert terminé";
        return $imageTokenAndExt;
    }
    
    

}
//----------------- Debut Partie achat -------------------
function del($tok){
    global $bdd;
    $deletewatch = $bdd->prepare('DELETE FROM watches WHERE token =?');
    $deletewatch->execute(array($tok));
    header('Location: .?page=profile');
    
}
//----------------- FIN Partie achat -------------------
//----------------- debut Partie profil montres ------------------- 



function personal_watches(){
    global $bdd;
    $me = $_SESSION['user']['pseudo'];
    $prswatche = $bdd->prepare('SELECT * FROM watches WHERE user = ?');
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
        if (preg_match('/[\[\]\'^£$%&*()}{@#~?><>,|=+¬-]/', $string) or empty($string))
            {
                $res = false;
                
            }
    }
    if(!is_int($table['price'])){
        $res = false;
    }
    
    return $res;
}

function watchFormIncorrect(){
    
}