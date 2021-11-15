<?php

$page = "home";
if (isset($_GET["page"])){
	$page = $_GET["page"];
}
	

$pages = ["home","rolex","main","inscription"];

if (array_search($page, $pages) === FALSE){
	$page = "404";
}

?>