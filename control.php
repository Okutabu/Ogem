<?php
include "connect.php";

function display_watch(){
    // in row container
    global $result;
    while($row = mysqli_fetch_assoc($result)){
        echo "<div class='col-lg-3'>";
        echo "<img src='./images/".$row["user"]."_".$row["brand"]."_".$row["name"]."_image"."png>";
        echo "</div>";
        }
    }

// function display_images($folder){
//     foreach($folder as $image){
//         if($image != ".." && $image !="."){
//         echo "<img src=./images/".$image." width='500px' alt=\"Une image qu'on aime bien\" >";}
//     }
// }

?>