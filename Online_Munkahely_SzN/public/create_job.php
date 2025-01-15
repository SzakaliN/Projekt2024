<?php
session_start();
require_once '../config/database.php';
include '../includes/header.php';

$pdo = Database::getConnection();

// Ellenőrizzük, hogy a felhasználó munkáltató-e
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'employer') {
    header('Location: ../public/index.php');
    exit;
}

$success = $error = '';

// Űrlap feldolgozása
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $type = $_POST['type'] ?? '';

    if ($title && $description && $location && $type) {
        $employer_id = $_SESSION['id'];

        try {
            // Az új álláshirdetést `pending` státusszal hozzuk létre
            $stmt = $pdo->prepare('INSERT INTO jobs (employer_id, title, description, location, type, status) 
                                   VALUES (?, ?, ?, ?, ?, ?)');
            if ($stmt->execute([$employer_id, $title, $description, $location, $type, 'pending'])) {
                $success = 'Álláshirdetés létrehozva és jóváhagyásra elküldve.';
            } else {
                $error = 'Hiba történt az álláshirdetés mentése során.';
            }
        } catch (PDOException $e) {
            $error = 'Adatbázis hiba: ' . $e->getMessage();
        }
    } else {
        $error = 'Minden mezőt ki kell tölteni!';
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <link rel="stylesheet" href="../styles/styles.css">
    <meta charset="UTF-8">
    <title>Új álláshirdetés létrehozása</title>
</head>
<body>
<h1>Új álláshirdetés létrehozása</h1>

<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if ($success): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="post">
    <label for="title">Cím:</label>
    <input type="text" id="title" name="title" required><br><br>

    <label for="description">Leírás:</label>
    <textarea id="description" name="description" required></textarea><br><br>

    <label for="location">Helyszín:</label>
    <input type="text" id="location" name="location" required><br><br>

    <label for="type">Típus:</label>
    <select id="type" name="type" required>
        <option value="Teljes munkaidő">Teljes munkaidő</option>
        <option value="Részmunkaidő">Részmunkaidő</option>
        <option value="Távmunka">Távmunka</option>
    </select><br><br>

    <button type="submit">Létrehozás</button>
</form>
</body>
</html>
