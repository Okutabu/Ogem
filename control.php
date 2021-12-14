<?php
unset($_SESSION['watches']);
//Traitement des pages pour structure MVC

$page = "home";
if (isset($_GET["page"])){
	$page = $_GET["page"];
}
	
$pages = ["home", "inscription", "sell", "search", "profile", "likes", "connect", "landing", "deco", "watch", "enchere"];

if (array_search($page, $pages) === FALSE){
	$page = "404";
}

//Gestion des accès
if (($page == "profile" || $page == "messages" || $page == "landing") && (!isset($_SESSION['user']))){ //on interdit l'accès a certaines pages aux utilisateurs non connectés
    forbidden_access();
} 

//Gestion des redirections

if ($page == "sell" && !isset($_SESSION['user'])){
    redirection_sell();
} 

//Affichage des montres par défaut lorsque l'on entre sur la page search.php
elseif ($page == "search" && !isset($_SESSION['watches'])){
    get_watches_sorted("views");
}

// traitement formulaires

if (isset($_POST["action"])){
    if ($_POST["action"] == "connexion"){
        connexion($_POST['email'], $_POST['password']);
    }
	if ($_POST["action"] == "inscription"){
        inscription($_POST['pseudo'], $_POST['email'], $_POST['password'], $_POST['password_retype']);
    }
    if ($_POST["action"] == "add_watches"){
        if ($_FILES != null){
            $imgToken = register_image($_FILES);
        }
        else{
            $imgToken = "watch.png";
        }
        
        if (approvePost($_POST)){
            add_watches($_SESSION['user']['token'], $_POST['brand'], $_POST['materiaux'], $_POST['name'], $_POST['price'], $_POST['buy'], $_POST['etat'], $imgToken);
        }else
        {
            echo "something went wrong";
        }
        
    }
    if ($_POST["action"] == "suppr"){
        del($_POST['token']);
    }
}

if (isset($_GET["action"])){
    if ($_GET["action"] == "filtrer"){
        filter_watches();
    }
}
