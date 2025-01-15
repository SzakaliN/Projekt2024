<?php
require_once '../config/database.php';

if (isset($pdo)) {
    echo "Az adatbázis kapcsolat sikeresen inicializálva!";
} else {
    echo "Hiba: A \$pdo változó nem érhető el.";
}

if (isset($_GET['id'])) {
    $jobId = $_GET['id'];

    $stmt = $pdo->prepare('DELETE FROM jobs WHERE id = ?');
    $stmt->execute([$jobId]);

    header('Location: manage_jobs.php?message=deleted');
    exit;
}
?>
