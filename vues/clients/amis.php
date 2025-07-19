<?php
// facelone/vues/clients/amis.php
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
    <h2>Gestion des amis</h2>

    <nav class="tab-nav">
      <button data-tab="find"    class="tab-btn active">Trouver des amis</button>
      <button data-tab="pending" class="tab-btn">Demandes reçues</button>
      <button data-tab="list"    class="tab-btn">Mes amis</button>
    </nav>

    <section id="tab-find" class="tab active card">
      <h3>Trouver des amis</h3>
      <div id="utilisateurs-container" class="cards-container"></div>
    </section>

    <section id="tab-pending" class="tab card">
      <h3>Demandes reçues</h3>
      <div id="pending-container" class="cards-container"></div>
    </section>

    <section id="tab-list" class="tab card">
      <h3>Mes amis</h3>
      <div id="amis-container" class="cards-container"></div>
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
  <title>Mes amis – Facelone</title>
  <link rel="stylesheet" href="/facelone/assets/css/style.css">
</head>
<body>

  <?php include __DIR__ . '/../../inc/header.php'; ?>

  <main id="app-content">
    <h2>Gestion des amis</h2>

    <nav class="tab-nav">
      <button data-tab="find"    class="tab-btn active">Trouver des amis</button>
      <button data-tab="pending" class="tab-btn">Demandes reçues</button>
      <button data-tab="list"    class="tab-btn">Mes amis</button>
    </nav>

    <section id="tab-find" class="tab active card">
      <h3>Trouver des amis</h3>
      <div id="utilisateurs-container" class="cards-container"></div>
    </section>

    <section id="tab-pending" class="tab card">
      <h3>Demandes reçues</h3>
      <div id="pending-container" class="cards-container"></div>
    </section>

    <section id="tab-list" class="tab card">
      <h3>Mes amis</h3>
      <div id="amis-container" class="cards-container"></div>
    </section>
  </main>
<?php include __DIR__ . '/../../inc/footer.php'; ?>

  <script>window.Facelone = { userId: <?= (int)$user['id'] ?> };</script>
  <script src="/facelone/assets/js/main.js" defer></script>
</body>
</html>
