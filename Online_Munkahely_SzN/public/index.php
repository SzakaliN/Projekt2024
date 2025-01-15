<?php
session_start();
require_once '../config/database.php';
include '../includes/header.php';

$pdo = Database::getConnection(); // Adatbázis kapcsolat létrehozása

if (isset($pdo)) {
    echo " ";
} else {
    echo "Hiba: A \$pdo változó nem érhető el.";
}

// Ellenőrizzük, hogy van-e üzenet az URL-ben
if (isset($_GET['message']) && $_GET['message'] === 'created') {
    echo '<p style="color: green;">Az álláshirdetés sikeresen létrehozva!</p>';
}

// Csak az 'approved' állapotú álláshirdetések lekérdezése
$stmt = $pdo->query('SELECT * FROM jobs WHERE status = "approved" ORDER BY posted_at DESC');
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <link rel="stylesheet" href="../styles/styles.css">
    <meta charset="UTF-8">
    <title>Álláshirdetések</title>
</head>
<body>
<h1>Elérhető állások</h1>



<?php if (count($jobs) > 0): ?>
    <ul>
        <?php foreach ($jobs as $job): ?>
            <li>
                <h2><?= htmlspecialchars($job['title']) ?></h2>
                <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>
                <p><strong>Helyszín:</strong> <?= htmlspecialchars($job['location']) ?></p>
                <p><strong>Típus:</strong> <?= htmlspecialchars($job['type']) ?></p>
                <p><strong>Feladó:</strong> <?= htmlspecialchars($job['employer_id']) ?></p>
                <a href="job_details.php?id=<?php echo $job['id']; ?>">Tovább</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Nincs elérhető álláshirdetés.</p>
<?php endif; ?>
</body>
</html>
