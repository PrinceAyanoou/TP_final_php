<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
try {
    $pdo = new PDO(
        'mysql:host=sql102.infinityfree.com;dbname=if0_39506114_facelone;charset=utf8mb4',
        'if0_39506114', // identifiant MySQL
        'WldUKNemHiHpbei', // remplace ici aussi
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die('Erreur BDD : ' . $e->getMessage());
}
