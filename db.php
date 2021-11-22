<?php
    $connect = mysqli_connect("localhost", "scgllydo_main", "root", "");
    mysqli_set_charset($connect, "utf8");

    $sql = "select * from watches";
    $result = mysqli_query($connect, $sql);
?>