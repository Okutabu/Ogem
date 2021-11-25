<?php

$page = "home";
if (isset($_GET["page"])){
	$page = $_GET["page"];
}
	

$pages = ["home", "inscription", "sell", "search", "profile", "messages", "cart", "connect"];

if (array_search($page, $pages) === FALSE){
	$page = "404";
}

// traitement formulaires inscription / connexion

if (isset($_POST["action"])){
    if ($_POST["action"] == "connexion"){
        connexion($_POST['email'], $_POST['password']);
    }
	if ($_POST["action"] == "inscription"){
        inscription($_POST['pseudo'], $_POST['email'], $_POST['password'], $_POST['password_retype']);
    }
}

// Erreurs

if (isset($_GET['acc_err'])){
	errors_account($_GET['acc_err']);
}


?>