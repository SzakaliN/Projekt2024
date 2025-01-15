<?php
require_once '../config/database.php';

if (isset($pdo)) {
    echo "Az adatbázis kapcsolat sikeresen inicializálva!";
} else {
    echo "Hiba: A \$pdo változó nem érhető el.";
}

if (isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];

    $stmt = $pdo->prepare('UPDATE users SET role = "admin" WHERE id = ?');
    $stmt->execute([$userId]);

    header('Location: users.php');
    exit;
} else {
    die('Hibás kérelem!');
}
