<?php

function display_watch(){
    //future fonction qui affiche les montres sur la page search, a modif

    global $result;
    while($row = mysqli_fetch_assoc($result)){
        echo "<div class='col-lg-3'>";
        echo "<img src='./images/".$row["user"]."_".$row["brand"]."_".$row["name"]."_image"."png'>";
        echo "</div>";
    }
}

?>