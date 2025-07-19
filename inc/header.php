<?php
// facelone/inc/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
$user = $_SESSION['user'] ?? null;
?>
<header>
  <h1><a href="/facelone/index.php" class="spa-link">👥 Facelone</a></h1>
  <nav>
    <?php if ($user): ?>
      <a href="/facelone/vues/clients/accueil.php"    class="spa-link">Accueil</a>
      <a href="/facelone/vues/clients/profil.php"     class="spa-link">Profil</a>
      <a href="/facelone/vues/clients/amis.php"       class="spa-link">Amis</a>
      <a href="/facelone/vues/clients/chat.php"       class="spa-link">Messages</a>
      <?php if (in_array($user['role'], ['admin','moderateur'])): ?>
        <a href="/facelone/vues/back-office/dashboard.php" class="spa-link">Admin</a>
      <?php endif; ?>
      <a href="/facelone/api/logout.php">Déconnexion</a>
    <?php else: ?>
      <a href="/facelone/vues/clients/login.php"    class="spa-link">Connexion</a>
      <a href="/facelone/vues/clients/register.php" class="spa-link">Inscription</a>
    <?php endif; ?>
  </nav>
</header>
