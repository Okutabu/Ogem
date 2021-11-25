<?php 
    session_start();
   // si la session existe pas soit si l'on est pas connecté on redirige
    if(!isset($_SESSION['user'])){
        header('Location: .');
        die();
    }

    // On récupere les données de l'utilisateur
    $req = $bdd->prepare('SELECT * FROM utilisateurs WHERE token = ?');
    $req->execute(array($_SESSION['user']));
    $data = $req->fetch();
    
?>

<main id="landdiv">
    <img src="images/good.png" id="landimg" >
    <?php  echo('<h1> Bonjour '.$data['pseudo'].', vous êtes bien connecté</h1>'); ?>
    <a href=".">Page d'Accueil</a>
</main>    