<?php
// facelone/vues/clients/chat.php
session_start();
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$user = $_SESSION['user'];
?>
<?php
if (isset($_GET['ajax']) && $_GET['ajax']=='1') {  ?>
  <main class="chat-container" id="app-content">
    <aside id="contacts" class="card">
      <h3>Mes contacts</h3>
      <div id="contacts-list">
        <!-- Chargé par JS -->
      </div>
    </aside>

    <section id="conversation" class="card">
      <h3 id="conv-title">Sélectionnez un contact</h3>
      <div id="messages"></div>
      <form id="chat-form">
        <input type="text" id="message-input" placeholder="Votre message…" autocomplete="off">
        <button type="submit" class="btn">Envoyer</button>
      </form>
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
  <title>Messages – Facelone</title>
  <link rel="stylesheet" href="/facelone/assets/css/style.css">
</head>
<body>

  <?php include __DIR__ . '/../../inc/header.php'; ?>
 <main class="chat-container" id="app-content">
    <aside id="contacts" class="card">
      <h3>Mes contacts</h3>
      <div id="contacts-list">
        <!-- Chargé par JS -->
      </div>
    </aside>

    <section id="conversation" class="card">
      <h3 id="conv-title">Sélectionnez un contact</h3>
      <div id="messages"></div>
      <form id="chat-form">
        <input type="text" id="message-input" placeholder="Votre message…" autocomplete="off">
        <button type="submit" class="btn">Envoyer</button>
      </form>
    </section>
  </main>
  <?php include __DIR__ . '/../../inc/footer.php'; ?>

  <script>window.Facelone = { userId: <?= (int)$user['id'] ?> };</script>
  <script src="/facelone/assets/js/main.js" defer></script>
</body>
</html>
