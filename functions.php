<?php

function get_watches_sorted($sort1 = 'views', $sens = 'decroissant'){
    global $bdd;
    if ($sens = 'croissant'){
        $check = $bdd->prepare('SELECT * FROM watches ORDER BY ? ASC');
    } else {
        $check = $bdd->prepare('SELECT * FROM watches ORDER BY ? DESC');
    }
    $check->execute([$sort1]);
    $_SESSION['watches'] = $check->fetchAll(PDO::FETCH_ASSOC); // avec le PDO::FETCH_ASSOC nous précisons que nous voulons un tableau associatif (le fetchAll va bien si le nombre de montres n'est pas trop important, sinon il y aura beaucoup de latence)
    //On met le resultat dans une variable de session pour eviter de devoir refaire des requetes sql a chaque fois
}

function filter_watches(){
    null;
}

function display_watch(){
    $watches = $_SESSION['watches'];
    echo "<main>";
    foreach($watches as $watch){
        echo "<article>";
        echo "<h1>" . $watch['name'] . "</h1>";
        echo "<img src='images/watchesPics/" . $watch['image_token'] . ".jpg' alt='Image Montre'>";
        echo "<p>" . $watch['marque'] . "</p>";
        echo "<p>" . $watch['likes'] . "</p>";
        echo "<button name='likes' class='heart'></button>";
        echo "<h2>" . $watch['prix'] . " €</h2>";
        if ($watch['buy']){
            echo "";
        } else {
            echo "";
        }
        
        echo "</article>";
    }
    echo "</main>";
    
    
}

//----------------- Fonctions pour la gestion de comptes ------------------- 

function inscription($pseudo1, $mail2, $password3, $retypedPassword4){
    global $bdd;
    // Si les variables existent et qu'elles ne sont pas vides
    if(!empty($pseudo1) && !empty($mail2) && !empty($password3) && !empty($retypedPassword4))
    {
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
        $data = $check->fetch();
        $row = $check->rowCount();

        $email = strtolower($email); // on transforme toute les lettres majuscule en minuscule pour éviter que Foo@gmail.com et foo@gmail.com soient deux compte différents 
        
        // Si la requete renvoie un 0 alors l'utilisateur n'existe pas 
        if($row == 0){ 
            if(strlen($pseudo) <= 100){ // On verifie que la longueur du pseudo <= 100
                if(strlen($email) <= 100){ // On verifie que la longueur du mail <= 100
                    if(filter_var($email, FILTER_VALIDATE_EMAIL)){ // Si l'email est de la bonne forme
                        if($password === $password_retype){ // si les deux mdp saisis sont bon

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
                        } else{ 
                            $_SESSION['flash'] = 'passwordCorresponding';
                            header('Location: .?page=inscription'); 
                            die();}
                    } else{ 
                        $_SESSION['flash'] = 'emailValidity';
                        header('Location: .?page=inscription'); 
                        die();}
                } else{ 
                    $_SESSION['flash'] = 'email_length';
                    header('Location: .?page=inscription'); 
                    die();}
            } else{ 
                $_SESSION['flash'] = 'pseudo_length';
                header('Location: .?page=inscription'); 
                die();}
        } else{ 
            $_SESSION['flash'] = 'already';
            header('Location:.?page=inscription'); 
            die();}
    }
}

function connexion($mail1, $password2){
    
    global $bdd;
    if(!empty($mail1) && !empty($password2)) // Si il existe les champs email, password et qu'ils ne sont pas vides
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
        if($row > 0) {
            // Si le mot de passe est le bon
            if(password_verify($password, $data['password'])) {
                // On crée la session 
                $req = $bdd->prepare('SELECT * FROM utilisateurs WHERE token = ?');
                $req->execute(array($data['token']));
                $_SESSION['user'] = $req->fetch();
                
                // On redirige sur landing.php
                header('Location: .?page=landing');
                die();
            } else { 
                $_SESSION['flash'] = 'password';
                header('Location: .?page=connect'); die(); }
        } else { 
            $_SESSION['flash'] = 'notExisting';
            header('Location: .?page=connect'); die(); }
    }
}

function welcome(){
    $data = $_SESSION['user'];
    echo '<img src="images/profilePics/' . $data['picture'] . '" alt="profil picture" id="landimg">';
    echo('<h1>Bonjour '.$data['pseudo'].', vous êtes bien connecté !</h1>');
}

function deconnexion(){
    unset($_SESSION['user']); //on efface tout ce qui concerne l'utilisateur dans la variable superglobale $_SESSION
    header('Location: .');
    die;
}

//Traitement erreurs & succès

function errors_accounts(){
    if (isset($_SESSION['flash'])){
        $err = htmlspecialchars($_SESSION['flash']);
        unset($_SESSION['flash']);
        switch($err) {
            //pour les connexions
            case 'password': echo '<div class="alert"><p><strong>Erreur</strong>, mot de passe incorrect</p></div>'; break;
            case 'notExisting': echo'<div class="alert"><p><strong>Erreur</strong>, le compte n\'existe pas</p></div>'; break;
            //pour l'inscription
            case 'success': echo'<div id="success"><h1>Inscription effectuée avec succès !</h1></div>'; break;
            case 'passwordCorresponding': echo'<div class="alert"><p><strong>Erreur</strong>, les mots de passe ne correspondent pas</p></div>'; break;
            case 'emailValidity': echo'<div class="alert"><p><strong>Erreur</strong>, l\'email n\'est pas valide</p></div>'; break;
            case 'email_length': echo'<div class="alert"><p><strong>Erreur</strong>, l\'email est trop long</p></div>'; break;
            case 'pseudo_length': echo'<div class="alert"><p><strong>Erreur</strong>, le pseudo est trop long</p></div>'; break;
            case 'already': echo'<div class="alert"><p><strong>Erreur</strong>, adresse email déjà utilisée</p></div>'; break;
        }
    }
}

function profil_connected(){
    if(isset($_SESSION['user'])){
        $data = $_SESSION['user'];
        echo '<img src="images/profilePics/' . $data['picture'] . '" alt="profil picture" id="profilePic">';
    } else {
        echo '<p>Profil</p>';
    }
}

//-----------------------------------------------------------------

function add_watches($user, $brand, $materiaux, $name, $prix, $buy){

    global $bdd;
    $sql = "INSERT INTO watches(user, marque, materiaux, name, prix, buy, image_token)
    VALUES (:user, :marque, :materiaux, :name, :prix, :buy, :imagetoken)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute(array(
        'user' => $user,
        'marque' => $brand,
        'materiaux' => $materiaux,
        'name' => $name,
        'prix' => $prix,
        'buy' => $buy,
        'imagetoken' => bin2hex(openssl_random_pseudo_bytes(64))
    ));
    header('Location: .?page=search');
    
}

?>