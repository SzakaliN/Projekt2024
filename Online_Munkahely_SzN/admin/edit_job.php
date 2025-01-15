<?php
require_once '../config/database.php';

if (isset($pdo)) {
    echo "Az adatbázis kapcsolat sikeresen inicializálva!";
} else {
    echo "Hiba: A \$pdo változó nem érhető el.";
}

if (isset($_GET['id'])) {
    $jobId = $_GET['id'];

    // Állás adatainak lekérdezése
    $stmt = $pdo->prepare('SELECT * FROM jobs WHERE id = ?');
    $stmt->execute([$jobId]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$job) {
        echo 'Állás nem található!';
        exit;
    }
}

// Frissítés logika
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare('UPDATE jobs SET title = ?, description = ? WHERE id = ?');
    $stmt->execute([$title, $description, $jobId]);

    header('Location: manage_jobs.php?message=updated');
    exit;
}
?>

<h1>Állás szerkesztése</h1>
<form method="post">
    <label for="title">Cím:</label>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($job['title']) ?>" required><br>

    <label for="description">Leírás:</label>
    <textarea name="description" id="description" required><?= htmlspecialchars($job['description']) ?></textarea><br>

    <button type="submit">Mentés</button>
</form>
