<?php

$page = "accueil";
if (isset($_GET["page"]))
    $page = $_GET["page"];

    include "header.php";
    include $page . ".php";
    include "footer.php";


?>

