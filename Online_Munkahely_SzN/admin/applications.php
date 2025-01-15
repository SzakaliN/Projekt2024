<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

$pdo = Database::getConnection(); // Adatbázis kapcsolat létrehozása

// Adminisztrátor jogosultság ellenőrzése
if ($_SESSION['role'] !== 'admin') {
    echo 'Nincs jogosultságod az adminisztrátori felülethez.';
    exit;
}

// Jelentkezések lekérdezése
$stmt = $pdo->prepare('SELECT applications.id AS application_id, applications.applied_at, 
                              jobs.title AS job_title, users.username AS applicant_name 
                       FROM applications 
                       JOIN jobs ON applications.job_id = jobs.id
                       JOIN users ON applications.applicant_id = users.id
                       ORDER BY applications.applied_at DESC');
$stmt->execute();
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <link rel="stylesheet" href="../styles/styles.css">
    <meta charset="UTF-8">
    <title>Jelentkezések kezelése</title>
</head>
<body>
<h1>Jelentkezések kezelése</h1>

<?php if (count($applications) > 0): ?>
    <table>
        <thead>
        <tr>
            <th>Állás címe</th>
            <th>Jelentkező neve</th>
            <th>Jelentkezés dátuma</th>
            <th>Akciók</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($applications as $application): ?>
            <tr>
                <td><?= htmlspecialchars($application['job_title']) ?></td>
                <td><?= htmlspecialchars($application['applicant_name']) ?></td>
                <td><?= htmlspecialchars($application['applied_at']) ?></td>
                <td>
                    <a href="../admin/view_application.php?id=<?= $application['application_id'] ?>">Részletek</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nincs egyetlen jelentkezés sem.</p>
<?php endif; ?>

</body>
</html>
