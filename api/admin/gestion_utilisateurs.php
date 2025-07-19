<?php
// facelone/api/admin/gestion_utilisateurs.php
header('Content-Type: application/json');
session_start();
require __DIR__ . '/../config.php';

// Vérification rôle
if (empty($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'moderateur'])) {
    http_response_code(403);
    exit(json_encode(['success' => false, 'message' => 'Accès refusé']));
}

$action = $_GET['action'] ?? 'liste';

try {
    switch ($action) {
        case 'liste':
            $stmt = $pdo->query("SELECT id, nom, prenom, email, role, created_at FROM users ORDER BY created_at DESC");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'users' => $users]);
            break;

        case 'changer_role':
            $data = json_decode(file_get_contents('php://input'), true);
            $uid  = (int)($data['user_id'] ?? 0);
            $role = $data['role'] ?? '';
            if (!$uid || !in_array($role, ['utilisateur', 'moderateur', 'admin'])) {
                throw new Exception('Données invalides');
            }
            $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
            $ok   = $stmt->execute([$role, $uid]);
            echo json_encode(['success' => (bool)$ok]);
            break;

        case 'supprimer':
            $data = json_decode(file_get_contents('php://input'), true);
            $uid  = (int)($data['user_id'] ?? 0);
            if (!$uid) {
                throw new Exception('ID utilisateur manquant');
            }
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $ok   = $stmt->execute([$uid]);
            echo json_encode(['success' => (bool)$ok]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Action inconnue']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
