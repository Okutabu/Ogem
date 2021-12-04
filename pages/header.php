<header id="navbar">
    <a href=".?page=home"><img src="images/logo.png" id="logo"></a>
</header>
<nav>
    <ul>
        <li><a href=".?page=home">Accueil</a></li>
        <li><a href=".?page=search">Acheter</a></li>
        <li><a href=".?page=sell">Vendre</a></li>
        <li><a href=".?page=connect">Connexion/Inscription</a></li>
        <li id="menuderoulant"><?php profil_connected(); ?>
            <ul id="sousmenu">
                <li><a href=".?page=inscription">Inscription</a></li>
                <li><a href=".?page=connect">Connexion</a></li>
                <li><a href=".?page=likes">Coups de coeur</a></li>
                <li><a href=".?page=deco">Déconnexion</a></li>
            </ul>
        </li>
    </ul>
</nav>