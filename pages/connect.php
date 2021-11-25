<main class="login-form">
    <h2 class="text-center">Connectez-vous</h2>
    <form action="../index.php" method="post">
        <input type="hidden" name="action" value="connexion">
        <input type="email" name="email" class="form-control" placeholder="Email" required="required" autocomplete="off">
        <input type="password" name="password" class="form-control" placeholder="Mot de passe" required="required" autocomplete="off">
        <input type="submit" class="btn" value="Connexion">
    </form>
</main>