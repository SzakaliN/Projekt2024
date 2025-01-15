
<?php
session_start();
require_once '../config/database.php';
include '../includes/header.php';

$pdo = Database::getConnection();

// Csak adminisztrátorok férhetnek hozzá
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo 'Nincs jogosultságod az oldal eléréséhez.';
    exit;
}

// Függőben lévő hirdetések lekérdezése
$stmt = $pdo->prepare('SELECT * FROM jobs WHERE status = ? ORDER BY posted_at DESC');
$stmt->execute(['pending']);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <link rel="stylesheet" href="../styles/styles.css">
    <meta charset="UTF-8">
    <title>Jóváhagyásra váró hirdetések</title>
</head>
<body>
<h1>Jóváhagyásra váró álláshirdetések</h1>

<?php if (count($jobs) > 0): ?>
    <table>
        <thead>
        <tr>
            <th>Cím</th>
            <th>Leírás</th>
            <th>Helyszín</th>
            <th>Típus</th>
            <th>Műveletek</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($jobs as $job): ?>
            <tr>
                <td><?= htmlspecialchars($job['title']) ?></td>
                <td><?= nl2br(htmlspecialchars($job['description'])) ?></td>
                <td><?= htmlspecialchars($job['location']) ?></td>
                <td><?= htmlspecialchars($job['type']) ?></td>
                <td>
                    <form method="post" action="approve_job.php" style="display:inline;">
                        <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                        <button type="submit">Jóváhagyás</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nincs jóváhagyásra váró hirdetés.</p>
<?php endif; ?>
</body>
</html>
