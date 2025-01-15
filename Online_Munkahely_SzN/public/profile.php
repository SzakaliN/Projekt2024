<?php
session_start();
require_once '../config/database.php';

$pdo = Database::getConnection(); // Adatbázis kapcsolat létrehozása
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role === 'employer') {
    $sql = "SELECT * FROM jobs WHERE employer_id = :employer_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':employer_id' => $user_id]);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sql = "SELECT applications.*, jobs.title FROM applications 
            JOIN jobs ON applications.job_id = jobs.id 
            WHERE applications.applicant_id = :applicant_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':applicant_id' => $user_id]);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php include '../includes/header.php'; ?>

<h1>Üdvözöljük a profil oldalán!</h1>

<?php if ($role === 'employer'): ?>
    <h2>Álláshirdetései</h2>
    <ul>
        <?php foreach ($jobs as $job): ?>
            <li><?= htmlspecialchars($job['title']) ?> - <?= htmlspecialchars($job['location']) ?></li>
        <?php endforeach; ?>
    </ul>
    <h3>Új álláshirdetés létrehozása</h3>
    <form action="create_job.php" method="POST">
        <input type="text" name="title" placeholder="Cím" required>
        <textarea name="description" placeholder="Leírás" required></textarea>
        <input type="text" name="location" placeholder="Helyszín" required>
        <select name="type">
            <option value="Teljes munkaidő">Teljes munkaidő</option>
            <option value="Részmunkaidő">Részmunkaidő</option>
        </select>
        <button type="submit">Létrehozás</button>
    </form>
<?php else: ?>
    <h2>Jelentkezései</h2>
    <ul>
        <?php foreach ($applications as $application): ?>
            <li><?= htmlspecialchars($application['title']) ?> - Állapot: <?= htmlspecialchars($application['status']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
