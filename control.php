<?php

$page = "home";
if (isset($_GET["page"])){
	$page = $_GET["page"];
}
	

$pages = ["home", "sell", "search", "profile", "messages", "cart", "sign"];

if (array_search($page, $pages) === FALSE){
	$page = "404";
}

?>