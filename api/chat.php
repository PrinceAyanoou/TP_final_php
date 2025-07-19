<?php
// facelone/api/chat.php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config.php';
checkAuth();

$me = (int)$_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Envoi de message : JSON body { to, message }
    $data = json_decode(file_get_contents('php://input'), true);
    $to      = isset($data['to']) ? (int)$data['to'] : 0;
    $message = isset($data['message']) ? trim($data['message']) : '';

    if ($to <= 0 || $message === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO messages (from_id, to_id, message) VALUES (?, ?, ?)");
        $ok   = $stmt->execute([$me, $to, $message]);
        echo json_encode(['success' => (bool)$ok]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

} else {
    // Récupération de la conversation : GET ?to=ID
    $to = isset($_GET['to']) ? (int)$_GET['to'] : 0;
    if ($to <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Paramètre "to" manquant']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            SELECT m.*, u.nom, u.prenom, u.avatar
            FROM messages m
            JOIN users u ON u.id = m.from_id
            WHERE (m.from_id = ? AND m.to_id = ?)
               OR (m.from_id = ? AND m.to_id = ?)
            ORDER BY m.sent_at
        ");
        $stmt->execute([$me, $to, $to, $me]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($messages);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
