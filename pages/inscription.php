<main class="login-form">
    <h2 class="text-center">Inscription</h2>
    <form action="../index.php" method="post">
        <input type="hidden" name="action" value="inscription">
        <input type="text" name="pseudo" class="form-control" placeholder="Pseudo" required="required" autocomplete="off">
        <input type="password" name="password" class="form-control" placeholder="Mot de passe" required="required" autocomplete="off">
        <input type="password" name="password_retype" class="form-control" placeholder="Re-tapez le mot de passe" required="required" autocomplete="off">
        <input type="submit" class="btn" value="Inscription">
    </form>
</main>