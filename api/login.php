<?php
require_once __DIR__.'/config.php';
$ct = $_SERVER['CONTENT_TYPE'] ?? '';
if(strpos($ct,'application/json')!==false){
  $i = json_decode(file_get_contents('php://input'),true);
  $email = trim($i['email']   ?? '');
  $pass  = $i['password']     ?? '';
} else {
  $email = trim($_POST['email']  ?? '');
  $pass  = $_POST['password']    ?? '';
}
if(!$email||!$pass) sendError(400,'Email ou mot de passe manquant');
$stmt = $pdo->prepare('SELECT * FROM users WHERE email=?');
$stmt->execute([$email]);
$user = $stmt->fetch();
if($user && password_verify($pass,$user['password'])){
  unset($user['password']);
  $_SESSION['user'] = $user;
  sendJson(['success'=>true,'user'=>$user]);
}
sendError(401,'Identifiants invalides');
