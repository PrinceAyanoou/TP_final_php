<?php
require_once __DIR__.'/config.php';
checkAuth();
if($_SERVER['REQUEST_METHOD']!=='POST') sendError(405,'Méthode non autorisée');
$contenu = trim($_POST['contenu'] ?? '');
if($contenu==='') sendError(400,'Contenu vide');
$image=null;
if(!empty($_FILES['image']['name'])){
  $fn=uniqid().'_'.basename($_FILES['image']['name']);
  if(move_uploaded_file($_FILES['image']['tmp_name'],__DIR__.'/../assets/images/'.$fn))
    $image=$fn;
}
$pdo->prepare('INSERT INTO posts(user_id,contenu,image)VALUES(?,?,?)')
    ->execute([$_SESSION['user']['id'],$contenu,$image]);
sendJson(['success'=>true]);
