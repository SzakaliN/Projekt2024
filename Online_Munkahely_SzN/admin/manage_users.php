<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$pdo = Database::getConnection(); // Adatbázis kapcsolat létrehozása

if (isset($pdo)) {
    echo "Az adatbázis kapcsolat sikeresen inicializálva!";
} else {
    echo "Hiba: A \$pdo változó nem érhető el.";
}

// Összes felhasználó lekérdezése
$stmt = $pdo->query('SELECT * FROM users ORDER BY created_at DESC');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Felhasználók kezelése</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Név</th>
        <th>Email</th>
        <th>Szerepkör</th>
        <th>Műveletek</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
                <a href="../admin/delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Biztosan törlöd?');">Törlés</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once '../includes/footer.php'; ?>
