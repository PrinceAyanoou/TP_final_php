<?php
// facelone/vues/back-office/login.php
session_start();
if (!empty($_SESSION['admin'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<?php
if (isset($_GET['ajax']) && $_GET['ajax']=='1') {  ?>
  <main class="auth" id="app-content">
    <h2>Administration Facelone</h2>
    <form action="/facelone/api/login_admin.php" method="POST">
      <input type="email" name="email" placeholder="Email admin" required>
      <input type="password" name="password" placeholder="Mot de passe" required>
      <button type="submit">Se connecter</button>
    </form>
    <p><a href="/facelone/index.php">Retour au site</a></p>
  </main>
  <?php
    exit;
}?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Connexion Admin – Facelone</title>
  <link rel="stylesheet" href="/facelone/assets/css/style.css">
</head>
<body>
  <main class="auth" id="app-content">
    <h2>Administration Facelone</h2>
    <form action="/facelone/api/login_admin.php" method="POST">
      <input type="email" name="email" placeholder="Email admin" required>
      <input type="password" name="password" placeholder="Mot de passe" required>
      <button type="submit">Se connecter</button>
    </form>
    <p><a href="/facelone/index.php">Retour au site</a></p>
  </main>
</body>
</html>
