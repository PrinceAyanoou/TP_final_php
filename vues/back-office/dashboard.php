<?php
// facelone/vues/back-office/dashboard.php
session_start();
if (empty($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
?>
 <?php
if (isset($_GET['ajax']) && $_GET['ajax']=='1') {  ?>
  <main class="content" id="app-content">
    <h2>Statistiques générales</h2>
    <div id="stats" class="stats-container"></div>
    <h2>Gestion des utilisateurs</h2>
    <table id="users-table">
      <thead>
        <tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Actions</th></tr>
      </thead>
      <tbody></tbody>
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
  <title>Tableau de bord – Facelone Admin</title>
  <link rel="stylesheet" href="/facelone/assets/css/style.css">
</head>
<body>
  <?php include __DIR__ . '/../../inc/header.php'; ?>
 <main class="content" id="app-content">
    <h2>Statistiques générales</h2>
    <div id="stats" class="stats-container"></div>
    <h2>Gestion des utilisateurs</h2>
    <table id="users-table">
      <thead>
        <tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Actions</th></tr>
      </thead>
      <tbody></tbody>
    </table>
  </main>
  <?php include __DIR__ . '/../../inc/footer.php'; ?>
  <script src="/facelone/assets/js/main.js" defer></script>
</body>
</html>
