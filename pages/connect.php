<main class="login-form">
            <?php 
                if(isset($_GET['login_err'])){
                    $err = htmlspecialchars($_GET['login_err']);

                    switch($err)
                    {
                        case 'password':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> mot de passe incorrect
                            </div>
                        <?php
                        break;

                        case 'email':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> email incorrect
                            </div>
                        <?php
                        break;

                        case 'already':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> compte non existant
                            </div>
                        <?php
                        break;
                    }
                }
            ?> 

    <h2 class="text-center">Connexion</h2>
    <form action="index.php" method="post">
        <input type="hidden" name="action" value="connexion">
        <input type="email" name="email" class="form-control" placeholder="Email" required="required" autocomplete="off">
        <input type="password" name="password" class="form-control" placeholder="Mot de passe" required="required" autocomplete="off">
        <button type="submit" class="btn btn-primary btn-block">Connexion</button>  
    </form>
</main>

        