<?php
require_once '../config/database.php';

$pdo = Database::getConnection(); // Adatbázis kapcsolat létrehozása

if (isset($pdo)) {
    echo "Az adatbázis kapcsolat sikeresen inicializálva!";
} else {
    echo "Hiba: A \$pdo változó nem érhető el.";
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$userId]);

    header('Location: manage_users.php?message=deleted');
    exit;
}
?>
