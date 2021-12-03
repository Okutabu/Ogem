<main class="login-form">
    <?php errors_accounts(); ?>
    <h2 class="text-center">Connectez-vous</h2>
    <form action="." method="post">
        <input type="hidden" name="action" value="connexion">
        <input type="email" name="email" class="form-control" placeholder="Email" required="required" autocomplete="off">
        <input type="password" name="password" class="form-control" placeholder="Mot de passe" required="required" autocomplete="off">
        <input type="submit" class="btn" value="Connexion">
    </form>
    <p>Pas encore de compte ?</p>
    <a href=".?page=inscription">Inscrivez-vous</a>
</main>