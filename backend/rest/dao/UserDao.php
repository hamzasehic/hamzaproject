<?php
require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('users');
    }

    public function getByEmail($email)
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    public function countByRole($role)
    {
        $stmt = $this->connection->prepare('SELECT COUNT(*) AS total FROM users WHERE role = :role');
        $stmt->execute(['role' => $role]);
        return (int) $stmt->fetch()['total'];
    }
}