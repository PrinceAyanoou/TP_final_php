<?php
// facelone/api/admin.php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config.php';

// Vérifie qu’on est admin
if (empty($_SESSION['admin'])) {
    http_response_code(403);
    exit(json_encode(['success'=>false,'message'=>'Accès refusé']));
}

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'stats':
            $stats = [];
            $queries = [
                'nb_users'    => "SELECT COUNT(*) FROM users",
                'nb_posts'    => "SELECT COUNT(*) FROM posts",
                'nb_comments' => "SELECT COUNT(*) FROM commentaires",
                'nb_mods'     => "SELECT COUNT(*) FROM users WHERE role='moderateur'"
            ];
            foreach ($queries as $k=>$sql) {
                $stats[$k] = (int)$pdo->query($sql)->fetchColumn();
            }
            echo json_encode(['success'=>true,'stats'=>$stats]);
            break;

        case 'list_users':
            $stmt = $pdo->query("SELECT id, nom, prenom, email, role FROM users ORDER BY created_at DESC");
            echo json_encode(['success'=>true,'users'=>$stmt->fetchAll(PDO::FETCH_ASSOC)]);
            break;

        case 'changer_role':
            $data = json_decode(file_get_contents('php://input'), true);
            $uid  = (int)($data['user_id'] ?? 0);
            $role = $data['role'] ?? '';
            if (!$uid || !in_array($role,['utilisateur','moderateur','admin'])) {
                throw new Exception('Paramètres invalides');
            }
            $pdo->prepare("UPDATE users SET role=? WHERE id=?")->execute([$role,$uid]);
            echo json_encode(['success'=>true]);
            break;

        case 'supprimer':
            $data = json_decode(file_get_contents('php://input'), true);
            $uid  = (int)($data['user_id'] ?? 0);
            if (!$uid) throw new Exception('ID manquant');
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$uid]);
            echo json_encode(['success'=>true]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['success'=>false,'message'=>'Action inconnue']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
