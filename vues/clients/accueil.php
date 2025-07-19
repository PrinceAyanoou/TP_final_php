<?php
// facelone/vues/clients/accueil.php
session_start();
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$user = $_SESSION['user'];

// 👉 Si requête AJAX (SPA), on ne renvoie QUE le <main>
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    ?>
    <main id="app-content">
      <section id="nouveau-post" class="card">
        <h3>Exprimez‑vous, <?= htmlspecialchars($user['prenom']) ?> !</h3>
        <form id="form-post" action="/facelone/api/post.php" method="POST" enctype="multipart/form-data">
          <textarea name="contenu" placeholder="Quoi de neuf ?" required></textarea>
          <input type="file" name="image" accept="image/*">
          <button type="submit" class="btn">Publier</button>
        </form>
      </section>
      <section id="feed" class="content">
        <h2>Flux d’actualités</h2>
        <div id="posts-container"></div>
      </section>
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
  <title>Accueil – Facelone</title>
  <link rel="stylesheet" href="/facelone/assets/css/style.css">
</head>
<body>

  <!-- Header & nav UNIQUEMENT en chargement normal -->
  <?php include __DIR__ . '/../../inc/header.php'; ?>

  <!-- Main complet -->
  <main id="app-content">
    <section id="nouveau-post" class="card">
      <h3>Exprimez‑vous, <?= htmlspecialchars($user['prenom']) ?> !</h3>
      <form id="form-post" action="/facelone/api/post.php" method="POST" enctype="multipart/form-data">
        <textarea name="contenu" placeholder="Quoi de neuf ?" required></textarea>
        <input type="file" name="image" accept="image/*">
        <button type="submit" class="btn">Publier</button>
      </form>
    </section>
    <section id="feed" class="content">
      <h2>Flux d’actualités</h2>
      <div id="posts-container"></div>
    </section>
  </main>

  <!-- Footer UNIQUEMENT en chargement normal -->
  <?php include __DIR__ . '/../../inc/footer.php'; ?>

  <script>window.Facelone={userId:<?= (int)$user['id'] ?>};</script>
  <script src="/facelone/assets/js/main.js" defer></script>
</body>
</html>
