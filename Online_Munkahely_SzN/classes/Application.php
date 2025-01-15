<?php

require_once '../config/Database.php';

class Application
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getAllApplications()
    {
        $stmt = $this->pdo->query('SELECT applications.*, users.username AS applicant_name, jobs.title AS job_title 
                                   FROM applications 
                                   JOIN users ON applications.user_id = users.id
                                   JOIN jobs ON applications.job_id = jobs.id
                                   ORDER BY applications.applied_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function applyForJob($jobId, $userId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO applications (job_id, user_id, applied_at) VALUES (?, ?, NOW())');
        return $stmt->execute([$jobId, $userId]);
    }
}
