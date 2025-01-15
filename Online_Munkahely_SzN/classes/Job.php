<?php

require_once '../config/Database.php';

class Job
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getAllJobs()
    {
        $stmt = $this->pdo->query('SELECT * FROM jobs ORDER BY posted_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createJob($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO jobs (title, description, location, type, employer_id, posted_at) VALUES (?, ?, ?, ?, ?, NOW())');
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['location'],
            $data['type'],
            $data['employer_id'],
        ]);
    }

    public function deleteJob($jobId)
    {
        $stmt = $this->pdo->prepare('DELETE FROM jobs WHERE id = ?');
        return $stmt->execute([$jobId]);
    }
}
