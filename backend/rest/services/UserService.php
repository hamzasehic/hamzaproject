<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';

class UserService extends BaseService
{
    protected $entity_name = 'User';
    protected $allowed_fields = ['full_name', 'email', 'password', 'role'];

    public function __construct($dao = null)
    {
        parent::__construct($dao ?? new UserDao());
    }

    private function strip($user)
    {
        if ($user) {
            unset($user['password_hash']);
        }
        return $user;
    }

    public function getAll()
    {
        return array_map(fn($user) => $this->strip($user), $this->dao->getAll());
    }

    public function getById($id)
    {
        return $this->strip(parent::getById($id));
    }

    public function add($data)
    {
        $data = $this->prepare($data);
        if (!isset($data['role']) || $data['role'] === '') {
            $data['role'] = 'member';
        }
        $this->validate_user($data, null);

        $id = $this->dao->insert([
            'full_name' => trim($data['full_name']),
            'email' => strtolower(trim($data['email'])),
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'],
        ]);
        return $this->getById($id);
    }

    public function update($id, $data)
    {
        $existing = parent::getById($id);          
        $data = $this->prepare($data);
        if (!isset($data['role']) || $data['role'] === '') {
            $data['role'] = $existing['role'];
        }
        $this->validate_user($data, $id);

        $row = [
            'full_name' => trim($data['full_name']),
            'email' => strtolower(trim($data['email'])),
            'role' => $data['role'],
        ];
        if (!empty($data['password'])) {
            $row['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $this->dao->update($id, $row);
        return $this->getById($id);
    }

    public function delete($id)
    {
        $user = parent::getById($id);
        if ($user['role'] === 'admin' && $this->dao->countByRole('admin') <= 1) {
            throw new ValidationException('The last admin cannot be deleted.');
        }
        $this->dao->delete($id);
    }

    private function validate_user($data, $id)
    {
        Validator::required($data, ['full_name', 'email', 'role']);
        Validator::length($data['full_name'], 'full_name', 2, 100);
        Validator::email($data['email']);
        Validator::in_list($data['role'], 'role', ['admin', 'member']);

        if ($id === null || !empty($data['password'])) {
            Validator::required($data, ['password']);
            Validator::length($data['password'], 'password', 8, 72);   
        }

        $existing = $this->dao->getByEmail(strtolower(trim($data['email'])));
        if ($existing && (int) $existing['id'] !== (int) $id) {
            throw new ValidationException('This email is already in use.');
        }
    }
}