<?php
// facelone/vues/clients/register.php
session_start();
if (!empty($_SESSION['user'])) {
    header('Location: accueil.php');
    exit;
}
?>
<?php
if (isset($_GET['ajax']) && $_GET['ajax']=='1') {  ?>
  <main class="auth" id="app-content">
    <h2>Créer un compte</h2>
    <form id="register-form" action="/facelone/api/register.php" method="POST" enctype="multipart/form-data">
      <input type="text"    name="nom"     placeholder="Nom"       required>
      <input type="text"    name="prenom"  placeholder="Prénom"    required>
      <input type="email"   name="email"   placeholder="Email"     required>
      <input type="password"name="password"placeholder="Mot de passe" required>
      <input type="password"name="confirm" placeholder="Confirmer mot de passe" required>
      <input type="file"    name="avatar"  accept="image/*">
      <button type="submit">S’inscrire</button>
    </form>
    <p>Déjà un compte ? <a href="login.php">Connectez-vous</a></p>
  </main>

  
<?php
    exit;
}?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Inscription – Facelone</title>
  <link rel="stylesheet" href="/facelone/assets/css/style.css">
</head>
<body>

  <?php include __DIR__ . '/../../inc/header.php'; ?>
 <main class="auth" id="app-content">
    <h2>Créer un compte</h2>
    <form id="register-form" action="/facelone/api/register.php" method="POST" enctype="multipart/form-data">
      <input type="text"    name="nom"     placeholder="Nom"       required>
      <input type="text"    name="prenom"  placeholder="Prénom"    required>
      <input type="email"   name="email"   placeholder="Email"     required>
      <input type="password"name="password"placeholder="Mot de passe" required>
      <input type="password"name="confirm" placeholder="Confirmer mot de passe" required>
      <input type="file"    name="avatar"  accept="image/*">
      <button type="submit">S’inscrire</button>
    </form>
    <p>Déjà un compte ? <a href="login.php">Connectez-vous</a></p>
  </main>
  <?php include __DIR__ . '/../../inc/footer.php'; ?>

  <script src="/facelone/assets/js/auth.js" defer></script>
</body>
</html>
