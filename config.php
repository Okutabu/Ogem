<?php
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=scgllydo_main;charset=utf8', 'root', '');
    } catch(PDOException $e) {
        die('Erreur : '.$e->getMessage());
    }
    // try {
    //     $bdd = new PDO('mysql:host=localhost;dbname=scgllydo_main;charset=utf8', 'scgllydo_enzo', 'EGcJAPF?baPO');
    // } catch(PDOException $e) {
    //     die('Erreur : '.$e->getMessage());
    // }
?>