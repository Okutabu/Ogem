<?php

//Traitement des pages pour structure MVC

$page = "home";
if (isset($_GET["page"])){
	$page = $_GET["page"];
}
	
$pages = ["home", "inscription", "sell", "search", "profile", "messages", "likes", "connect", "landing", "deco", "watch"];

if (array_search($page, $pages) === FALSE){
	$page = "404";
}

//Gestion des accès
if (($page == "profile" || $page == "messages" || $page == "landing") && (!isset($_SESSION['user']))){ //on interdit l'accès a certaines pages aux utilisateurs non connectés
    forbidden_access();
} 

//Gestion des redirections

if ($page == "sell" && !isset($_SESSION['user'])){
    redirection();
} 

//Affichage des montres par défaut lorsque l'on entre sur la page search.php
elseif ($page == "search" && !isset($_SESSION['watches'])){
    get_watches_sorted();
}

// traitement formulaires inscription / connexion

if (isset($_POST["action"])){
    if ($_POST["action"] == "connexion"){
        connexion($_POST['email'], $_POST['password']);
    }
	if ($_POST["action"] == "inscription"){
        inscription($_POST['pseudo'], $_POST['email'], $_POST['password'], $_POST['password_retype']);
    }
    if ($_POST["action"] == "add_watches"){
        add_watches($_POST['user'], $_POST['brand'], $_POST['materiaux'], $_POST['name'], $_POST['price'], $_POST['buy']);
    }
    if ($_POST["action"] == "search"){
        get_watches();
    }

}

?>