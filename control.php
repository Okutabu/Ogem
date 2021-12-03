<?php

//Traitement des pages pour structure MVC

$page = "home";
if (isset($_GET["page"])){
	$page = $_GET["page"];
}
	
$pages = ["home", "inscription", "sell", "search", "profile", "messages", "cart", "connect", "landing", "deco"];

if (array_search($page, $pages) === FALSE){
	$page = "404";
}


if (($page == "sell" || $page == "profile" || $page == "messages" || $page == "landing") && (!isset($_SESSION['user']))){ //on interdit l'accès a certaines pages aux utilisateurs non connectés
    echo "<script>alert('Accès refusé, veuillez vous connecter');</script>";
    header("Refresh:0.1; url=.?page=home");
    die();
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

}

?>