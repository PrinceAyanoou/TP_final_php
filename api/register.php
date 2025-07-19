<?php
// facelone/api/register.php
require 'config.php';

$nom    = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$email  = trim($_POST['email'] ?? '');
$pass   = $_POST['password'] ?? '';
$conf   = $_POST['confirm'] ?? '';
if (!$nom||!$prenom||!$email||!$pass||$pass!==$conf) {
    http_response_code(400);
    exit(json_encode(['success'=>false,'message'=>'Données invalides']));
}
$hash = password_hash($pass, PASSWORD_DEFAULT);
$avatar = 'default.png';
if (!empty($_FILES['avatar']['name'])) {
    $fn = uniqid().'_'.basename($_FILES['avatar']['name']);
    if (move_uploaded_file($_FILES['avatar']['tmp_name'], __DIR__.'/../assets/images/'.$fn)) {
        $avatar = $fn;
    }
}
$stmt = $pdo->prepare("INSERT INTO users (nom,prenom,email,password,avatar) VALUES (?,?,?,?,?)");
$ok = $stmt->execute([$nom,$prenom,$email,$hash,$avatar]);
echo json_encode(['success'=>(bool)$ok]);
