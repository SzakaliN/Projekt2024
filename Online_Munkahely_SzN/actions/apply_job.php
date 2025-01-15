<?php
require_once '../config/database.php';
session_start();

if (isset($pdo)) {
    echo "Az adatbázis kapcsolat sikeresen inicializálva!";
} else {
    echo "Hiba: A \$pdo változó nem érhető el.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $job_id = $_POST['job_id'];
    $applicant_id = $_SESSION['user_id'];

    $sql = "INSERT INTO applications (job_id, applicant_id, status) VALUES (:job_id, :applicant_id, 'Függőben')";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':job_id' => $job_id,
            ':applicant_id' => $applicant_id,
        ]);
        header("Location: ../public/job_details.php?id=$job_id&message=Jelentkezés sikeres!");
        exit;
    } catch (PDOException $e) {
        echo "Hiba a jelentkezés során: " . $e->getMessage();
    }
} else {
    header("Location: ../public/login.php");
    exit;
}
?>
