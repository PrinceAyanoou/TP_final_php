<?php
// facelone/api/get_feed.php
require_once __DIR__ . '/config.php';
checkAuth();

// Préparation du filtre user_id optionnel
$userFilter = '';
$params     = [];
if (isset($_GET['user_id']) && (int)$_GET['user_id'] > 0) {
    $userFilter = ' WHERE p.user_id = ?';
    $params[]   = (int)$_GET['user_id'];
}

// Requête principale avec totaux like/dislike depuis post_reactions
$sql = "
    SELECT
      p.id,
      p.contenu,
      p.image,
      p.created_at,
      u.nom,
      u.prenom,
      u.avatar,
      COALESCE(lc.likes,   0) AS likes,
      COALESCE(dc.dislikes,0) AS dislikes
    FROM posts p
    JOIN users u ON u.id = p.user_id
    LEFT JOIN (
      SELECT post_id, COUNT(*) AS likes
      FROM post_reactions
      WHERE reaction = 'like'
      GROUP BY post_id
    ) lc ON lc.post_id = p.id
    LEFT JOIN (
      SELECT post_id, COUNT(*) AS dislikes
      FROM post_reactions
      WHERE reaction = 'dislike'
      GROUP BY post_id
    ) dc ON dc.post_id = p.id
    {$userFilter}
    ORDER BY p.created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$feed = $stmt->fetchAll();

sendJson([
    'success' => true,
    'feed'    => $feed
]);
