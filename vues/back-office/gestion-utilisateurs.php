<?php
// facelone/vues/back-office/gestion-utilisateurs.php
session_start();
if (empty($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin','moderateur'])) {
    header('Location: login.php');
    exit;
}
?>
<?php
if (isset($_GET['ajax']) && $_GET['ajax']=='1') {  ?>
  <main id="app-content">
    <h2>Liste des utilisateurs</h2>
    <table id="users-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Email</th>
          <th>Rôle</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Lignes chargées dynamiquement en JS -->
      </tbody>
    </table>
  </main>
<?php
    exit;
}?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Gestion Utilisateurs – Facelone Admin</title>
  <link rel="stylesheet" href="/facelone/assets/css/style.css">
</head>
<body>

  <?php include __DIR__ . '/../../inc/header.php'; ?>
<main id="app-content">
    <h2>Liste des utilisateurs</h2>
    <table id="users-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Email</th>
          <th>Rôle</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Lignes chargées dynamiquement en JS -->
      </tbody>
    </table>
  </main>
  <?php include __DIR__ . '/../../inc/footer.php'; ?>

  <script src="/facelone/assets/js/main.js" defer></script>
</body>
</html>
