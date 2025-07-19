<?php
// facelone/api/admin/statistiques.php
header('Content-Type: application/json');
session_start();
require __DIR__ . '/../config.php';

// Vérification du rôle
if (empty($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'moderateur'])) {
    http_response_code(403);
    exit(json_encode(['success' => false, 'message' => 'Accès refusé']));
}

try {
    $stats = [];

    $queries = [
        'nb_users'    => "SELECT COUNT(*) FROM users",
        'nb_posts'    => "SELECT COUNT(*) FROM posts",
        'nb_comments' => "SELECT COUNT(*) FROM commentaires",
        'nb_mods'     => "SELECT COUNT(*) FROM users WHERE role = 'moderateur'"
    ];

    foreach ($queries as $key => $sql) {
        $stmt = $pdo->query($sql);
        $stats[$key] = (int)$stmt->fetchColumn();
    }

    echo json_encode(['success' => true, 'stats' => $stats]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
