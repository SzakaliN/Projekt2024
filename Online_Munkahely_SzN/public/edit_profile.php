<?php
require_once '../config/database.php';
session_start();

if (isset($pdo)) {
    echo "Az adatbázis kapcsolat sikeresen inicializálva!";
} else {
    echo "Hiba: A \$pdo változó nem érhető el.";
}

// Ellenőrizzük, hogy be van-e jelentkezve a felhasználó
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Felhasználó adatainak lekérdezése
$stmt = $pdo->prepare('SELECT username, email FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Adatok frissítése
    $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ? WHERE id = ?');
    $stmt->execute([$username, $email, $userId]);

    $message = 'Profil frissítve!';
}

?>

<?php require_once '../includes/header.php'; ?>

<h1>Profil szerkesztése</h1>

<?php if (isset($message)): ?>
    <p style="color: green;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="post">
    <label for="username">Felhasználónév:</label>
    <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required><br>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

    <button type="submit">Mentés</button>
</form>

<?php require_once '../includes/footer.php'; ?>
