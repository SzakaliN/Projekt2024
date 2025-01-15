<?php
require_once '../config/database.php';

if (isset($pdo)) {
    echo "Az adatbázis kapcsolat sikeresen inicializálva!";
} else {
    echo "Hiba: A \$pdo változó nem érhető el.";
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Validáció
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        $errors[] = 'Minden mezőt ki kell tölteni!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Érvénytelen email cím!';
    } elseif (strlen($password) < 6) {
        $errors[] = 'A jelszónak legalább 6 karakter hosszúnak kell lennie!';
    }

    if (empty($errors)) {
        // Jelszó titkosítása
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Adatbázis mentés
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
        if ($stmt->execute([$username, $email, $hashedPassword, $role])) {
            header('Location: login.php');
            exit;
        } else {
            $errors[] = 'Hiba történt a regisztráció során.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Regisztráció</title>
</head>
<body>
<h1>Regisztráció</h1>
<?php foreach ($errors as $error): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>
<form method="post" action="">
    <label>Felhasználónév: <input type="text" name="username"></label><br>
    <label>Email: <input type="email" name="email"></label><br>
    <label>Jelszó: <input type="password" name="password"></label><br>
    <label>Szerepkör:
        <select name="role">
            <option value="employer">Munkaadó</option>
            <option value="employee">Munkavállaló</option>
        </select>
    </label><br>
    <button type="submit">Regisztráció</button>
</form>
</body>
</html>
