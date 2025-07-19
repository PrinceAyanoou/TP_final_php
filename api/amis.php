<?php
// facelone/api/amis.php
header('Content-Type: application/json; charset=utf-8');
session_start();
if (empty($_SESSION['user'])) {
    http_response_code(401);
    exit(json_encode(['success'=>false,'message'=>'Non authentifié']));
}
require_once __DIR__ . '/config.php';

$me     = (int)$_SESSION['user']['id'];
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        // ────────────────
        // 1) Utilisateurs dispo (exclut uniquement les amis acceptés)
        case 'liste_users':
            $stmt = $pdo->prepare("
                SELECT u.id, u.nom, u.prenom, u.avatar
                FROM users u
                WHERE u.id <> ?
                  AND u.id NOT IN (
                    -- exclut uniquement les amis validés
                    SELECT to_id   FROM amis WHERE from_id = ? AND statut = 'accepte'
                    UNION
                    SELECT from_id FROM amis WHERE to_id   = ? AND statut = 'accepte'
                  )
                ORDER BY u.nom, u.prenom
            ");
            $stmt->execute([$me, $me, $me]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        // (les autres actions restent identiques)
        case 'add':
            $to = (int)($_POST['to_id'] ?? 0);
            if ($to <= 0) throw new Exception('ID destinataire manquant');
            $pdo->prepare("INSERT INTO amis (from_id,to_id,statut) VALUES (?, ?, 'en_attente')")
                ->execute([$me, $to]);
            echo json_encode(['success'=>true]);
            break;

        case 'pending':
            $stmt = $pdo->prepare("
                SELECT a.id AS relation_id, u.id, u.nom, u.prenom, u.avatar
                FROM amis a
                JOIN users u ON u.id = a.from_id
                WHERE a.to_id = ? AND a.statut = 'en_attente'
                ORDER BY a.demande_le DESC
            ");
            $stmt->execute([$me]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'accepter':
            $rid = (int)($_POST['relation_id'] ?? 0);
            if ($rid <= 0) throw new Exception('relation_id manquant');
            $pdo->prepare("UPDATE amis SET statut = 'accepte' WHERE id = ?")->execute([$rid]);
            echo json_encode(['success'=>true]);
            break;

        case 'refuser':
            $rid = (int)($_POST['relation_id'] ?? 0);
            if ($rid <= 0) throw new Exception('relation_id manquant');
            $pdo->prepare("DELETE FROM amis WHERE id = ?")->execute([$rid]);
            echo json_encode(['success'=>true]);
            break;

        case 'liste':
            $stmt = $pdo->prepare("
                SELECT u.id, u.nom, u.prenom, u.avatar
                FROM users u
                WHERE EXISTS (
                    SELECT 1 FROM amis a
                    WHERE a.statut = 'accepte'
                      AND (
                            (a.from_id = ? AND a.to_id   = u.id)
                         OR (a.to_id   = ? AND a.from_id = u.id)
                      )
                )
                ORDER BY u.nom, u.prenom
            ");
            $stmt->execute([$me, $me]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        default:
            http_response_code(400);
            echo json_encode(['success'=>false,'message'=>'Action inconnue']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
