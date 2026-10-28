<?php
require_once __DIR__ . '/BaseDao.php';

class UserMembershipDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('user_memberships');
    }

    public function getByUser($userId)
    {
        $stmt = $this->connection->prepare(
            'SELECT um.*, m.name AS membership_name, m.price
             FROM user_memberships um
             JOIN memberships m ON m.id = um.membership_id
             WHERE um.user_id = :user_id
             ORDER BY um.start_date DESC'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getActiveByUser($userId)
    {
        $stmt = $this->connection->prepare(
            "SELECT um.*, m.name AS membership_name
             FROM user_memberships um
             JOIN memberships m ON m.id = um.membership_id
             WHERE um.user_id = :user_id AND um.status = 'active' AND um.end_date >= CURDATE()
             ORDER BY um.end_date DESC
             LIMIT 1"
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch();
    }
}