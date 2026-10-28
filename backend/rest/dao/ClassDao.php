<?php
require_once __DIR__ . '/BaseDao.php';

class ClassDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('classes');
    }

    public function getAllWithTrainer()
    {
        $stmt = $this->connection->prepare(
            'SELECT c.*, t.full_name AS trainer_name
             FROM classes c
             JOIN trainers t ON t.id = c.trainer_id
             ORDER BY c.start_time'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUpcoming()
    {
        $stmt = $this->connection->prepare(
            'SELECT c.*, t.full_name AS trainer_name
             FROM classes c
             JOIN trainers t ON t.id = c.trainer_id
             WHERE c.start_time > NOW()
             ORDER BY c.start_time'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByTrainer($trainerId)
    {
        $stmt = $this->connection->prepare('SELECT * FROM classes WHERE trainer_id = :trainer_id ORDER BY start_time');
        $stmt->execute(['trainer_id' => $trainerId]);
        return $stmt->fetchAll();
    }
}