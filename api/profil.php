<?php
// facelone/api/profil.php
header('Content-Type: application/json');
session_start();
require __DIR__ . '/config.php';

if (empty($_SESSION['user'])) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Non authentifié']));
}

$id     = (int)($_POST['id'] ?? 0);
$nom    = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
if (!$id || $id !== $_SESSION['user']['id'] || !$nom || !$prenom) {
    http_response_code(400);
    exit(json_encode(['success' => false, 'message' => 'Données invalides']));
}

// Gestion de l’avatar
$avatar = $_SESSION['user']['avatar'];
if (!empty($_FILES['avatar']['name'])) {
    $fn = uniqid() . '_' . basename($_FILES['avatar']['name']);
    if (move_uploaded_file($_FILES['avatar']['tmp_name'], __DIR__ . '/../assets/images/' . $fn)) {
        $avatar = $fn;
    }
}

// Mise à jour en base
$stmt = $pdo->prepare("
    UPDATE users
    SET nom = ?, prenom = ?, avatar = ?
    WHERE id = ?
");
$ok = $stmt->execute([$nom, $prenom, $avatar, $id]);

if ($ok) {
    // Mettre à jour la session
    $_SESSION['user']['nom']    = $nom;
    $_SESSION['user']['prenom'] = $prenom;
    $_SESSION['user']['avatar'] = $avatar;
    echo json_encode(['success' => true, 'message' => 'Profil mis à jour', 'user' => $_SESSION['user']]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
}
