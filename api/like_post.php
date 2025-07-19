<?php
// facelone/api/like_post.php
require_once __DIR__.'/config.php';
checkAuth();
$input  = json_decode(file_get_contents('php://input'), true);
$pid    = (int)($input['id']     ?? 0);
$react  = $input['action']        ?? '';
$uid    = $_SESSION['user']['id'];

if (!$pid || !in_array($react, ['like','dislike'])) {
  sendError(400, 'Params invalides');
}

// 1. Vérifier s'il existe déjà une réaction de cet utilisateur sur ce post
$stmt = $pdo->prepare("
  SELECT reaction 
  FROM post_reactions 
  WHERE post_id = ? AND user_id = ?
");
$stmt->execute([$pid, $uid]);
$existing = $stmt->fetchColumn();

if ($existing === false) {
  // Pas de réaction précédente : on l'insère
  $stmt = $pdo->prepare("
    INSERT INTO post_reactions (post_id, user_id, reaction)
    VALUES (?, ?, ?)
  ");
  $stmt->execute([$pid, $uid, $react]);
} elseif ($existing === $react) {
  // Même réaction : on la retire (toggle off)
  $stmt = $pdo->prepare("
    DELETE FROM post_reactions
    WHERE post_id = ? AND user_id = ?
  ");
  $stmt->execute([$pid, $uid]);
} else {
  // Réaction différente : on met à jour
  $stmt = $pdo->prepare("
    UPDATE post_reactions 
    SET reaction = ?, created_at = CURRENT_TIMESTAMP
    WHERE post_id = ? AND user_id = ?
  ");
  $stmt->execute([$react, $pid, $uid]);
}

// 2. Comptages à jour
$counts = $pdo->query("
  SELECT
    SUM(reaction = 'like')    AS likes,
    SUM(reaction = 'dislike') AS dislikes
  FROM post_reactions
  WHERE post_id = {$pid}
")->fetch();

sendJson([
  'success'   => true,
  'likes'     => (int)$counts['likes'],
  'dislikes'  => (int)$counts['dislikes']
]);
