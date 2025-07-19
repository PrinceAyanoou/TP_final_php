<?php
// facelone/vues/clients/profil.php
session_start();
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$user = $_SESSION['user'];
?>
<?php
if (isset($_GET['ajax']) && $_GET['ajax']=='1') {  ?>
  <main id="app-content">
    <section id="infos-profil" class="card">
      <!-- ton formulaire de mise à jour de profil -->
      <form id="profil-form" action="/facelone/api/profil.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
        <input type="text"   name="nom"    value="<?= htmlspecialchars($user['nom']) ?>"    required>
        <input type="text"   name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
        <input type="email"  name="email"  value="<?= htmlspecialchars($user['email']) ?>"  disabled>
        <input type="file"   name="avatar" accept="image/*">
        <button type="submit">Mettre à jour</button>
      </form>
    </section>

    <section id="mes-publications">
      <h3>Mes publications</h3>
      <div id="posts-container">
        <!-- les posts seront chargés ici par JS -->
      </div>
    </section>
  </main>
<?php
    exit;
}?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Mon profil – Facelone</title>
  <link rel="stylesheet" href="/facelone/assets/css/style.css">
</head>
<body>

  <?php include __DIR__ . '/../../inc/header.php'; ?>
  <main id="app-content">
    <section id="infos-profil" class="card">
      <!-- ton formulaire de mise à jour de profil -->
      <form id="profil-form" action="/facelone/api/profil.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
        <input type="text"   name="nom"    value="<?= htmlspecialchars($user['nom']) ?>"    required>
        <input type="text"   name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
        <input type="email"  name="email"  value="<?= htmlspecialchars($user['email']) ?>"  disabled>
        <input type="file"   name="avatar" accept="image/*">
        <button type="submit">Mettre à jour</button>
      </form>
    </section>

    <section id="mes-publications">
      <h3>Mes publications</h3>
      <div id="posts-container">
        <!-- les posts seront chargés ici par JS -->
      </div>
    </section>
  </main>
  <?php include __DIR__ . '/../../inc/footer.php'; ?>

  <script>
    // Expose l'userId pour main.js
    window.Facelone = {
      userId: <?= (int)$user['id'] ?>
    };
  </script>
  <script src="/facelone/assets/js/main.js" defer></script>
</body>
</html>
