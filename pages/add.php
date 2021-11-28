<?php


function redirect(){
    header("Location: .?page=add_marketplace");
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $data_is_correct = True;

    $user = $_POST["user"]; //session holds user later
    $brand = $_POST["brand"];
    $name = $_POST["name"];
    $date = $_POST["date"]; //session holds date later
    $price = $_POST["price"];
    $buynow = $_POST["buynow"];
    $description = $_POST["description"];

    if($data_is_correct){
        // $database_watches = new database;
        // $database_watches->watches_connect();
        $sql = "insert into scgllydo_watches (user, brand, name, date, price, buynow, description)
         values (:user, :brand, :name, :date, :price, :buynow, :description)";
        // $prep = $database_watches->prepare($sql);
        // $prep->execute(['user' => $user, 'brand' => $brand, 'name' => $name, 'date' => $date,
        //  'price' => $price, 'buynow' => $buynow, 'description' => $description])

        mysqli_query($connect, $sql);
        redirect();
    }
}


?>