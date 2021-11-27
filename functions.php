<?php

//function display_watch(sortBy = 'views', views = False, pricemin = 0, pricemax = , flag4 = False, sort){
    function display_watch(){
    //future fonction qui affiche les montres sur la page search, a modif
    global $result;
    
    while($row = mysqli_fetch_assoc($result)){
        echo "<div class='col-lg-3'>";
        echo "<img src='./images/".$row["user"]."_".$row["brand"]."_".$row["name"]."_image"."png'>";
        echo "</div>";
    }
}

//----------------- Fonctions pour la gestion de comptes ------------------- 

function insciption($pseudo1, $mail2, $password3, $retypedPassword4){
    // Si les variables existent et qu'elles ne sont pas vides
    if(!empty($pseudo1) && !empty($mail2) && !empty($password3) && !empty($retypedPassword4))
    {
        // Patch XSS
        $pseudo = htmlspecialchars($pseudo1);
        $email = htmlspecialchars($mail2);
        $password = htmlspecialchars($password3);
        $password_retype = htmlspecialchars($retypedPassword4);

        // On vérifie si l'utilisateur existe
        $check = $bdd->prepare('SELECT pseudo, email, password FROM utilisateurs WHERE email = ?');
        $check->execute(array($email));
        $data = $check->fetch();
        $row = $check->rowCount();

        $email = strtolower($email); // on transforme toute les lettres majuscule en minuscule pour éviter que Foo@gmail.com et foo@gmail.com soient deux compte différents ..
        
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
                            header('Location: .?page=home&amp;acc_err=success');
                            die();
                        } else{ 
                            header('Location: .?page=inscription&amp;acc_err=passwordCorresponding'); 
                            die();}
                    } else{ 
                        header('Location: .?page=inscription&amp;acc_err=emailValidity'); 
                        die();}
                } else{ 
                    header('Location: .?page=inscription&amp;acc_err=email_length'); 
                    die();}
            } else{ 
                header('Location: .?page=inscription&amp;acc_err=pseudo_length'); 
                die();}
        } else{ 
            header('Location:.?page=inscription&amp;acc_err=already'); 
            die();}
    }
}

function connexion($mail1, $password2){
    
    session_start(); // Démarrage de la session

    if(!empty($mail1) && !empty($password2)) // Si il existe les champs email, password et qu'ils ne sont pas vides
    {
        // Patch XSS
        $email = htmlspecialchars($mail1); 
        $password = htmlspecialchars($password2);
        
        $email = strtolower($email); // email transformé en minuscule
        
        // On regarde si l'utilisateur est inscrit dans la table utilisateurs
        $check = $bdd->prepare('SELECT pseudo, email, password, token FROM utilisateurs WHERE email = ?');
        $check->execute(array($email));
        $data = $check->fetch();
        $row = $check->rowCount();
        
        

        // Si > à 0 alors l'utilisateur existe
        if($row > 0) {
            // Si le mail est bon niveau format
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Si le mot de passe est le bon
                if(password_verify($password, $data['password'])) {
                    // On crée la session 
                    $_SESSION['user'] = $data['token'];
                    // On redirige sur landing.php
                    header('Location: .?page=landing');
                    die();
                }
                else{ 
                    header('Location: .?acc_err=password'); die(); }
            }
            else{ 
                header('Location: .?acc_err=email'); die(); }
        }
        else{ 
            header('Location: .?acc_err=notExisting'); die(); }
    }
}

function landProperly(){

    session_start();
   // si on arrive ici sans être connecté on redirige
    if(!isset($_SESSION['user'])){
        header('Location: .');
        die();
    }

    // Sinon on récupere les données de l'utilisateur pour afficher la belle page de landing
    $req = $bdd->prepare('SELECT * FROM utilisateurs WHERE token = ?');
    $req->execute(array($_SESSION['user']));
    $data = $req->fetch();

    echo('<h1> Bonjour '.$data['pseudo'].', vous êtes bien connecté</h1>');
    
}

function deconnection(){
    session_destroy();
    header('Location: .?page=home');
    die();
}

//Traitement erreurs

function errors_accounts($error){
    
    $err = htmlspecialchars($error);
    if ($err == 'success') {
            echo '<div class="alert alert-success">';
    } else {
        echo '<div class="alert alert-danger">';
    }
    switch($err) {
        //pour les connexions
        case 'password': echo '<strong>Erreur</strong>, mot de passe incorrect';
        case 'email': echo'<strong>Erreur</strong>, email incorrect';
        case 'notExisting': echo'<strong>Erreur</strong>, le compte n\'existe pas';
        //pour l'inscription
        case 'success': echo'<strong>Erreur</strong>, les mots de passe ne correspondent pas';
        case 'passwordCorresponding': echo'<strong>Erreur</strong>, les mots de passe ne correspondent pas';
        case 'emailValidity': echo'<strong>Erreur</strong>, l\'email n\'est pas valide';
        case 'email_length': echo'<strong>Erreur</strong>, l\'email est trop long';
        case 'pseudo_length': echo'<strong>Erreur</strong>, le pseudo est trop long';
        case 'already': echo'<strong>Erreur</strong>, adresse email déjà utilisée';
    }
    echo '</div>';
}

//-----------------------------------------------------------------

?>