<?php
if(session_status()===PHP_SESSION_NONE) session_start();
header('Content-Type:application/json; charset=utf-8');
try {
  $pdo = new PDO(
    'mysql:host=localhost;dbname=facelone;charset=utf8mb4',
    'root','',
    [
      PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES=>false
    ]
  );
} catch(PDOException $e) {
  http_response_code(500);
  echo json_encode(['success'=>false,'error'=>'BDD : '.$e->getMessage()]);
  exit;
}
function checkAuth(){
  if(empty($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['success'=>false,'error'=>'Non authentifié']);
    exit;
  }
}
function sendJson($d){ echo json_encode($d,JSON_UNESCAPED_UNICODE); exit; }
function sendError($c,$m){ http_response_code($c); echo json_encode(['success'=>false,'error'=>$m],JSON_UNESCAPED_UNICODE); exit; }
