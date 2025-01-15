<?php
require_once '../config/database.php';
require_once '../includes/header.php';

if (isset($pdo)) {
    echo "Az adatbázis kapcsolat sikeresen inicializálva!";
} else {
    echo "Hiba: A \$pdo változó nem érhető el.";
}

// Összes állás lekérdezése
$stmt = $pdo->query('SELECT * FROM jobs ORDER BY posted_at DESC');
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Álláshirdetések kezelése</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Cím</th>
        <th>Leírás</th>
        <th>Létrehozva</th>
        <th>Műveletek</th>
    </tr>
    <?php foreach ($jobs as $job): ?>
        <tr>
            <td><?= htmlspecialchars($job['id']) ?></td>
            <td><?= htmlspecialchars($job['title']) ?></td>
            <td><?= htmlspecialchars(substr($job['description'], 0, 50)) ?>...</td>
            <td><?= htmlspecialchars($job['created_at']) ?></td>
            <td>
                <a href="edit_job.php?id=<?= $job['id'] ?>">Szerkesztés</a> |
                <a href="delete_job.php?id=<?= $job['id'] ?>" onclick="return confirm('Biztosan törlöd?');">Törlés</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once '../includes/footer.php'; ?>
