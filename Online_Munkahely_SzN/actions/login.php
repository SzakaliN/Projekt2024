<?php
session_start();
require_once '../config/Database.php';

$pdo = Database::getConnection(); // Adatbázis kapcsolat létrehozása

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Felhasználó ellenőrzése az adatbázisban e-mail cím alapján
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Ha a felhasználó létezik és a jelszó megegyezik
    if ($user && password_verify($password, $user['password'])) {
        // Bejelentkezés sikeres
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Szerepkör tárolása

        if ($user['role'] === 'admin') {
            header('Location: ../admin/applications.php'); // Admin oldal
            exit;
        } else {
            header('Location: ../public/index.php'); // Normál felhasználói oldal
            exit;
        }
    } else {
        $error = 'Helytelen e-mail vagy jelszó!';
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <link rel="stylesheet" href="../styles/styles.css">
    <meta charset="UTF-8">
    <title>Bejelentkezés</title>
</head>
<body>
<h1>Bejelentkezés</h1>
<form action="login.php" method="POST">
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="password" placeholder="Jelszó" required>
    <button type="submit">Bejelentkezés</button>
</form>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>
</body>
</html>
