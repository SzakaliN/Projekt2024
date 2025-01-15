<?php
require_once '../config/database.php';
include '../includes/header.php';
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'employer') {
    header('Location: ../public/index.php');
    exit;
}
if (isset($pdo)) {
    echo " ";
} else {
    echo "Hiba: A \$pdo változó nem érhető el.";
}

// Ellenőrizzük, hogy van-e ID a GET-ben
if (!isset($_GET['id'])) {
    echo "Nincs kiválasztott álláshirdetés!";
    exit;
}

$jobId = $_GET['id'];

// Ellenőrizzük, hogy a bejelentkezett munkáltatóhoz tartozik-e az álláshirdetés
$stmt = $pdo->prepare('SELECT * FROM jobs WHERE id = ? AND employer_id = ?');
$stmt->execute([$jobId, $_SESSION['id']]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    echo "Nem található az álláshirdetés, vagy nem jogosult a szerkesztésére.";
    exit;
}

// Ha az űrlap elküldésre került
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $type = $_POST['type'];

    // Álláshirdetés frissítése
    $stmt = $pdo->prepare('UPDATE jobs SET title = ?, description = ?, location = ?, type = ? WHERE id = ?');
    $stmt->execute([$title, $description, $location, $type, $jobId]);

    header('Location: ../public/index.php?message=updated');
    exit;
}

// Ha törölni szeretné az álláshirdetést
if (isset($_POST['delete'])) {
    // Ellenőrzés után töröljük a kapcsolódó jelentkezéseket
    $stmt = $pdo->prepare('DELETE FROM applications WHERE job_id = ?');
    $stmt->execute([$jobId]);

// Majd töröljük az álláshirdetést
    $stmt = $pdo->prepare('DELETE FROM jobs WHERE id = ?');
    $stmt->execute([$jobId]);

    // Átirányítjuk a főoldalra a sikeres törlés után
    header('Location: ../public/index.php?message=deleted');
    exit;
}
/*if (isset($_POST['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM jobs WHERE id = ?');
    $stmt->execute([$jobId]);

    header('Location: ../public/index.php?message=deleted');
    exit;
}*/
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <link rel="stylesheet" href="../styles/styles.css">
    <meta charset="UTF-8">
    <title>Álláshirdetés szerkesztése</title>
</head>
<body>
<h1>Álláshirdetés szerkesztése: <?= htmlspecialchars($job['title']) ?></h1>

<form method="post">
    <label for="title">Cím:</label>
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($job['title']) ?>" required><br>

    <label for="description">Leírás:</label>
    <textarea id="description" name="description" required><?= htmlspecialchars($job['description']) ?></textarea><br>

    <label for="location">Helyszín:</label>
    <input type="text" id="location" name="location" value="<?= htmlspecialchars($job['location']) ?>" required><br>

    <label for="type">Típus:</label>
    <select id="type" name="type" required>
        <option value="Teljes munkaidő" <?= $job['type'] === 'Teljes munkaidő' ? 'selected' : '' ?>>Teljes munkaidő</option>
        <option value="Részmunkaidő" <?= $job['type'] === 'Részmunkaidő' ? 'selected' : '' ?>>Részmunkaidő</option>
        <option value="Távmunka" <?= $job['type'] === 'Távmunka' ? 'selected' : '' ?>>Távmunka</option>
    </select><br>

    <button type="submit">Módosítás mentése</button>
</form>

<!-- Törlés lehetőség -->
<form method="post" onsubmit="return confirm('Biztos, hogy törölni szeretnéd ezt az álláshirdetést?');">
    <button type="submit" name="delete">Álláshirdetés törlése</button>
</form>

<a href="../public/index.php">Vissza az álláshirdetésekhez</a>
</body>
</html>
