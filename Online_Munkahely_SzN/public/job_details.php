<?php
require_once '../config/database.php';
include '../public/index.php';

$pdo = Database::getConnection(); // Adatbázis kapcsolat létrehozása
if (isset($pdo)) {
    echo " ";
} else {
    echo "Hiba: A \$pdo változó nem érhető el.";
}

$id = $_GET['id'] ?? 0;

// Ellenőrizzük, hogy a hirdetés ID-ja érvényes
if ($id) {
    // Álláshirdetés adatainak lekérése
    $stmt = $pdo->prepare('SELECT jobs.*, users.username AS employer_name FROM jobs 
                           JOIN users ON jobs.employer_id = users.id 
                           WHERE jobs.id = ?');
    $stmt->execute([$id]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$job) {
        echo 'Az álláshirdetés nem található!';
        exit;
    }
} else {
    echo 'Érvénytelen álláshirdetés!';
    exit;
}

// Ellenőrizzük, hogy a bejelentkezett felhasználó munkáltató-e, és a hirdetés az ő tulajdonában van
$isEmployer = isset($_SESSION['id']) && $_SESSION['id'] == $job['employer_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Munkavállalói jelentkezés kezelése
    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
        $job_id = $job['id'];

        // Ellenőrizzük, hogy már jelentkezett-e a felhasználó
        $stmt = $pdo->prepare('SELECT * FROM applications WHERE applicant_id = ? AND job_id = ?');
        $stmt->execute([$user_id, $job_id]);
        $application = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($application) {
            $error = 'Már jelentkeztél erre az állásra!';
        } else {
            // Jelentkezés mentése az adatbázisba
            $stmt = $pdo->prepare('INSERT INTO applications (applicant_id, job_id) VALUES (?, ?)');
            if ($stmt->execute([$user_id, $job_id])) {
                $success = 'Sikeresen jelentkeztél erre az állásra!';
            } else {
                $error = 'Hiba történt a jelentkezés során.';
            }
        }
    } else {
        $error = 'Be kell jelentkezned a jelentkezéshez!';
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <link rel="stylesheet" href="../styles/styles.css">
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($job['title']) ?></title>
</head>
<body>
<h1><?= htmlspecialchars($job['title']) ?></h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php elseif (isset($success)): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<p><strong>Leírás:</strong> <?= nl2br(htmlspecialchars($job['description'])) ?></p>
<p><strong>Helyszín:</strong> <?= htmlspecialchars($job['location']) ?></p>
<p><strong>Típus:</strong> <?= htmlspecialchars($job['type']) ?></p>
<p><strong>Munkaadó:</strong> <?= htmlspecialchars($job['employer_name']) ?></p>

<?php if ($isEmployer): ?>
    <!-- Ha a bejelentkezett felhasználó munkáltató, akkor legyen elérhető a szerkesztés -->
    <p><a href="../public/edit_job.php?id=<?= $job['id'] ?>">Álláshirdetés szerkesztése</a></p>
<?php endif; ?>

<?php if (isset($_SESSION['id']) && $_SESSION['role'] == 'employee'): ?>
    <!-- Ha a felhasználó munkavállaló és be van jelentkezve, akkor lehetősége van jelentkezni -->
    <form method="post">
        <button type="submit">Jelentkezés</button>
    </form>
<?php elseif (!isset($_SESSION['id'])): ?>
    <p><a href="../actions/login.php">Jelentkezz be a jelentkezéshez</a></p>
<?php endif; ?>

</body>
</html>
