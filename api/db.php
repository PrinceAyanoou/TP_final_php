<?php
// facelone/api/db.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=facelone;charset=utf8mb4',
        'root','',
        [
          PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
          PDO::ATTR_EMULATE_PREPARES=>false,
        ]
    );
} catch (PDOException $e) {
    // en vue, on peut soit die(), soit afficher un message d’erreur plus sympa
    die('Erreur BDD : '.$e->getMessage());
}
