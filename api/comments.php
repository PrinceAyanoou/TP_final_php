<?php
require_once __DIR__.'/config.php';
$method = $_SERVER['REQUEST_METHOD'];
if($method==='GET'){
  $pid=(int)($_GET['post_id']??0);
  if($pid<=0) sendError(400,'post_id manquant');
  $s=$pdo->prepare("
    SELECT c.id,c.contenu,c.created_at,
           u.id AS user_id,u.nom,u.prenom,u.avatar
    FROM commentaires c
    JOIN users u ON u.id=c.user_id
    WHERE c.post_id=?
    ORDER BY c.created_at ASC
  ");
  $s->execute([$pid]);
  sendJson(['success'=>true,'comments'=>$s->fetchAll()]);
}
elseif($method==='POST'){
  checkAuth();
  $i=json_decode(file_get_contents('php://input'),true);
  $pid=(int)($i['post_id']??0);
  $co = trim($i['contenu']??'');
  if($pid<=0||$co==='') sendError(400,'Params invalides');
  $pdo->prepare('INSERT INTO commentaires(post_id,user_id,contenu)VALUES(?,?,?)')
      ->execute([$pid,$_SESSION['user']['id'],$co]);
  sendJson(['success'=>true]);
}
else sendError(405,'Méthode non autorisée');
