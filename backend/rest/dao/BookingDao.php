<?php
require_once __DIR__ . '/BaseDao.php';

class BookingDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('bookings');
    }

    public function getByUser($userId)
    {
        $stmt = $this->connection->prepare(
            'SELECT b.*, c.name AS class_name, c.start_time, t.full_name AS trainer_name
             FROM bookings b
             JOIN classes c ON c.id = b.class_id
             JOIN trainers t ON t.id = c.trainer_id
             WHERE b.user_id = :user_id
             ORDER BY c.start_time DESC'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function countBooked($classId)
    {
        $stmt = $this->connection->prepare(
            "SELECT COUNT(*) AS total FROM bookings WHERE class_id = :class_id AND status = 'booked'"
        );
        $stmt->execute(['class_id' => $classId]);
        return (int) $stmt->fetch()['total'];
    }

        public function getByUserAndClass($userId, $classId)
    {
        $stmt = $this->connection->prepare(
            'SELECT * FROM bookings WHERE user_id = :user_id AND class_id = :class_id'
        );
        $stmt->execute(['user_id' => $userId, 'class_id' => $classId]);
        return $stmt->fetch();
    }

    public function getAllDetailed()
    {
        $stmt = $this->connection->prepare(
            'SELECT b.*, u.full_name AS user_name, c.name AS class_name, c.start_time
             FROM bookings b
             JOIN users u ON u.id = b.user_id
             JOIN classes c ON c.id = b.class_id
             ORDER BY c.start_time DESC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}