<?php
/*require_once '../config/database.php';

$pdo = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $jobId = $_POST['job_id'];

    $stmt = $pdo->prepare('UPDATE jobs SET status = ? WHERE id = ?');
    if ($stmt->execute(['approved', $jobId])) {
        header('Location: pending_jobs.php?message=approved');
        exit;
    } else {
        echo 'Hiba történt a jóváhagyás során.';
    }
}
*/?>
<?php
require_once '../config/database.php';
$pdo = Database::getConnection();

if (isset($_GET['id'])) {
    $jobId = $_GET['id'];

    $stmt = $pdo->prepare('UPDATE jobs SET status = "approved" WHERE id = ?');
    if ($stmt->execute([$jobId])) {
        header('Location: ../admin/pending_jobs.php?message=approved');
        exit;
    } else {
        echo "Hiba történt az álláshirdetés jóváhagyása során.";
    }
}
?>
