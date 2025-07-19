<?php
session_start();
if (!empty($_SESSION['user'])) {
    header('Location: vues/clients/accueil.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Facelone – Bienvenue</title>
  <link rel="stylesheet" href="/facelone/assets/css/style.css">
</head>
<body>
  <?php include __DIR__.'/inc/header.php'; ?>
  <main>
    <section class="intro">
      <h2>Bienvenue sur Facelone</h2>
      <p>Réseau social inspiré de Facebook, 100 % PHP & JS natif.</p>
      <div class="cta">
        <a href="/facelone/vues/clients/register.php" class="btn">Créer un compte</a>
        <a href="/facelone/vues/clients/login.php" class="btn">Se connecter</a>
      </div>
    </section>
  </main>
  <?php include __DIR__.'/inc/footer.php'; ?>
</body>
</html>
