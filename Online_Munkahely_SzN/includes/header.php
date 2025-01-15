
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Munkahely Portál</title>
    <link rel="stylesheet" href="../styles/styles.css"> <!-- Ha van CSS fájlod -->
</head>
<body>
<header>
    <h1>Online Munkahely Portál</h1>



    <?php if (isset($_SESSION['id'])): ?>
        <!-- Ha be van jelentkezve a felhasználó -->

        <?php if ($_SESSION['role'] ==='admin'): ?>
            <!-- Navigációs menü -->
            <nav>
                <ul>
                    <li><a href="../admin/manage_users.php">Felhasználók kezelése</a></li>
                    <li><a href="../admin/pending_jobs.php">Jóváhagyásra váró álláshirdetések</a></li>
                    <li><a href="../admin/applications.php">Jelentkezések</a></li>


                </ul>
            </nav>
        <?php endif; ?>

    <?php endif; ?>


    <nav>
        <ul>
            <li><a href="../public/index.php">Főoldal</a></li>

            <?php if (isset($_SESSION['id'])): ?>
                <!-- Ha be van jelentkezve a felhasználó -->
                <li><a href="../public/index.php">Álláshirdetések</a></li>
                <?php if ($_SESSION['role'] == 'employer'): ?>
                    <!-- Csak munkáltatók számára elérhető menüpont -->
                    <li><a href="../public/create_job.php">Új álláshirdetés létrehozása</a></li>
                <?php endif; ?>
                <li><a href="../actions/logout.php">Kijelentkezés</a></li>
            <?php else: ?>
                <!-- Ha nincs bejelentkezve a felhasználó -->
                <li><a href="../actions/login.php">Bejelentkezés</a></li>
            <?php endif; ?>
        </ul>
    </nav>


</header>