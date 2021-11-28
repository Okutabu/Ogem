<?php

//Traitement des pages pour structure MVC

$page = "home";
if (isset($_GET["page"])){
	$page = $_GET["page"];
}
	

$pages = ["home", "inscription", "sell", "search", "profile", "messages", "cart", "connect", "landing"];

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

//Procédure d'entrée dans landing.php

if ($_GET["page"] = "landing"){
    landProperly();
}

// Erreurs

if (isset($_GET['acc_err'])){
	errors_accounts($_GET['acc_err']);
}


?>