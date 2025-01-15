<?php
session_start();
require_once '../config/database.php';
include '../includes/header.php';


$pdo = Database::getConnection(); // Adatbázis kapcsolat létrehozása

if ($_SESSION['role'] !== 'admin') {
    echo 'Nincs jogosultságod az adminisztrátori felülethez.';
    exit;
}

if (isset($pdo)) {
    echo " ";
} else {
    echo "Hiba: A \$pdo változó nem érhető el.";
}

$id = $_GET['id'] ?? 0;

// Jelentkezés adatainak lekérése
if ($id) {
    $stmt = $pdo->prepare('SELECT applications.*, users.username AS applicant_name, jobs.title AS job_title 
                           FROM applications 
                           JOIN users ON applications.applicant_id = users.id
                           JOIN jobs ON applications.job_id = jobs.id 
                           WHERE applications.id = ?');
    $stmt->execute([$id]);
    $application = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$application) {
        echo 'A jelentkezés nem található!';
        exit;
    }
} else {
    echo 'Érvénytelen jelentkezés!';
    exit;
}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <link rel="stylesheet" href="../styles/styles.css">
    <meta charset="UTF-8">
    <title>Jelentkezés részletei</title>
</head>
<body>
<h1>Jelentkezés részletei</h1>

<p><strong>Állás:</strong> <?= htmlspecialchars($application['job_title']) ?></p>
<p><strong>Jelentkező:</strong> <?= htmlspecialchars($application['applicant_name']) ?></p>
<p><strong>Jelentkezés dátuma:</strong> <?= htmlspecialchars($application['applied_at']) ?></p>

<!-- Itt megjelenítheted a jelentkező önéletrajzát, ha van -->
<!-- Például egy link a fájl letöltéséhez, ha azt feltöltötte -->

</body>
</html>
