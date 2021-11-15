<?php
// INDEX

$page = "home";
if (isset($_GET["page"]))
	$page = $_GET["page"];

$pages = ["home","rolex","main","inscription"];

if (array_search($page, $pages) === FALSE)
	$page = "404";

include "header.php";
include $page . ".php";
include "footer.php";



$f = fopen("stats.txt", "a+");
fputs($f, $_SERVER["REMOTE_ADDR"] . "\t" . $page ."\n");
fclose($f);


// http://10.7.254.63:8080



?>
