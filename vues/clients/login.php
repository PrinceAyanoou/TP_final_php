<?php
// facelone/vues/clients/login.php
session_start();
if (!empty($_SESSION['user'])) {
    header('Location: accueil.php');
    exit;
}

// Si requête SPA/AJAX, on ne renvoie que le <main>
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    ?>
    <main id="app-content" class="auth">
      <h2>Connexion</h2>
      <form id="login-form" action="/facelone/api/login.php" method="POST">
        <input type="email" name="email" placeholder="Adresse email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit" class="btn">Se connecter</button>
      </form>
      <p>Pas encore de compte ? <a href="/facelone/vues/clients/register.php" class="spa-link">Inscription</a></p>
    </main>
    <?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Connexion – Facelone</title>
  <link rel="stylesheet" href="/facelone/assets/css/style.css">
</head>
<body>

  <!-- Header chargé UNE SEULE FOIS en mode normal -->
  <?php include __DIR__ . '/../../inc/header.php'; ?>

  <!-- Main complet -->
  <main id="app-content" class="auth">
    <h2>Connexion</h2>
    <form id="login-form" action="/facelone/api/login.php" method="POST">
      <input type="email" name="email" placeholder="Adresse email" required>
      <input type="password" name="password" placeholder="Mot de passe" required>
      <button type="submit" class="btn">Se connecter</button>
    </form>
    <p>Pas encore de compte ? <a href="/facelone/vues/clients/register.php" class="spa-link">Inscription</a></p>
  </main>

  <?php include __DIR__ . '/../../inc/footer.php'; ?>
  <script src="/facelone/assets/js/auth.js" defer></script>
</body>
</html>
