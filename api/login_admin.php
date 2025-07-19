<?php
// facelone/api/login_admin.php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config.php';

$email = trim($_POST['email'] ?? '');
$pass  = $_POST['password'] ?? '';

if (!$email || !$pass) {
    http_response_code(400);
    exit(json_encode(['success'=>false,'message'=>'Champs manquants']));
}

// Cherche dans la table administrateurs
$stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE email = ?");
$stmt->execute([$email]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin && password_verify($pass, $admin['mot_de_passe'])) {
    // Marque la session admin
    $_SESSION['admin'] = ['id'=>$admin['id'],'email'=>$admin['email']];
    echo json_encode(['success'=>true,'message'=>'Connexion réussie']);
} else {
    http_response_code(401);
    echo json_encode(['success'=>false,'message'=>'Identifiants invalides']);
}
