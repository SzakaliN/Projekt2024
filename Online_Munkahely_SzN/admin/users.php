<?php
require_once '../classes/User.php';
require_once '../includes/header.php';

// User osztály példányosítása
$userHandler = new User();

// Felhasználók lekérdezése
$users = $userHandler->getAllUsers();
?>

<h1>Felhasználók kezelése</h1>
<table border="1">
    <thead>
    <tr>
        <th>ID</th>
        <th>Név</th>
        <th>E-mail</th>
        <th>Szerepkör</th>
        <th>Műveletek</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
                <form action="delete_user.php" method="post" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <button type="submit">Törlés</button>
                </form>
                <?php if ($user['role'] !== 'admin'): ?>
                    <form action="update_role.php" method="post" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <button type="submit">Adminná tesz</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../includes/footer.php'; ?>
