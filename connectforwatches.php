<?php
$connect = mysqli_connect("localhost", "scgllydo_proplayer", "360noscopeblazeit", "scgllydo_Watches");
mysqli_set_charset($connect, "utf8");

$sql = "select * from watches";
$result = mysqli_query($connect, $sql);

?>