<?php

$page = "home";
if (isset($_GET["page"])){
	$page = $_GET["page"];
}
	

$pages = ["home","rolex","main","inscription"];

if (array_search($page, $pages) === FALSE){
	$page = "404";
}


function display_watch(){

    global $result;
    while($row = mysqli_fetch_assoc($result)){
        echo "<div class='col-lg-3'>";
        echo "<img src='./images/".$row["user"]."_".$row["brand"]."_".$row["name"]."_image"."png'>";
        echo "</div>";
        }
    }

function display_images($folder){
    foreach($folder as $image){
        if($image != ".." && $image !="."){
        echo "<img src=./images/".$image." width='500px' alt=\"Une image qu'on aime bien\" >";}
    }
}


?>