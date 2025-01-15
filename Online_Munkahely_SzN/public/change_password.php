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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Jelszó ellenőrzése
    $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($currentPassword, $user['password'])) {
        $error = 'Helytelen jelenlegi jelszó!';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'Az új jelszavak nem egyeznek!';
    } else {
        // Jelszó frissítése
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->execute([$hashedPassword, $userId]);

        $message = 'Jelszó sikeresen megváltoztatva!';
    }
}

?>

<?php require_once '../includes/header.php'; ?>

<h1>Jelszó megváltoztatása</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (isset($message)): ?>
    <p style="color: green;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="post">
    <label for="current_password">Jelenlegi jelszó:</label>
    <input type="password" name="current_password" id="current_password" required><br>

    <label for="new_password">Új jelszó:</label>
    <input type="password" name="new_password" id="new_password" required><br>

    <label for="confirm_password">Új jelszó megerősítése:</label>
    <input type="password" name="confirm_password" id="confirm_password" required><br>

    <button type="submit">Mentés</button>
</form>

<?php require_once '../includes/footer.php'; ?>
