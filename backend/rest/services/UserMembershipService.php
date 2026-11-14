<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserMembershipDao.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/MembershipDao.php';

class UserMembershipService extends BaseService
{
    protected $entity_name = 'User membership';
    protected $allowed_fields = ['user_id', 'membership_id', 'start_date', 'status'];
    private $user_dao;
    private $membership_dao;

    public function __construct($dao = null, $user_dao = null, $membership_dao = null)
    {
        parent::__construct($dao ?? new UserMembershipDao());
        $this->user_dao = $user_dao ?? new UserDao();
        $this->membership_dao = $membership_dao ?? new MembershipDao();
    }

    public function getAll()
    {
        return $this->dao->getAllDetailed();
    }

    public function getByUser($userId)
    {
        $this->require_user($userId);
        return $this->dao->getByUser($userId);
    }

    public function getActiveByUser($userId)
    {
        $this->require_user($userId);
        $active = $this->dao->getActiveByUser($userId);
        if (!$active) {
            throw new NotFoundException('This user has no active membership.');
        }
        return $active;
    }

    public function add($data)
    {
        $data = $this->prepare($data);
        if (!isset($data['start_date']) || $data['start_date'] === '') {
            $data['start_date'] = date('Y-m-d');      // default: starts today
        }
        Validator::required($data, ['user_id', 'membership_id']);
        Validator::integer($data['user_id'], 'user_id', 1);
        Validator::integer($data['membership_id'], 'membership_id', 1);
        Validator::date($data['start_date'], 'start_date');

        if (!$this->user_dao->getById($data['user_id'])) {
            throw new ValidationException('The selected user does not exist.');
        }
        $plan = $this->membership_dao->getById($data['membership_id']);
        if (!$plan) {
            throw new ValidationException('The selected membership plan does not exist.');
        }

        if ($data['start_date'] < date('Y-m-d')) {
            throw new ValidationException('The start date cannot be in the past.');
        }

        $active = $this->dao->getActiveByUser($data['user_id']);
        if ($active) {
            throw new ValidationException('This user already has an active membership until ' . $active['end_date'] . '.');
        }

        $end = (new DateTime($data['start_date']))
            ->modify('+' . (int) $plan['duration_days'] . ' days')
            ->format('Y-m-d');

        $id = $this->dao->insert([
            'user_id' => $data['user_id'],
            'membership_id' => $data['membership_id'],
            'start_date' => $data['start_date'],
            'end_date' => $end,
            'status' => 'active',
        ]);
        return $this->dao->getById($id);
    }

    public function update($id, $data)
    {
        $membership = $this->getById($id);
        $data = $this->prepare($data);
        Validator::required($data, ['status']);
        Validator::in_list($data['status'], 'status', ['active', 'expired', 'cancelled']);

        if ($data['status'] === $membership['status']) {
            throw new ValidationException('The membership is already ' . $data['status'] . '.');
        }
        if ($data['status'] === 'active') {
            if ($membership['end_date'] < date('Y-m-d')) {
                throw new ValidationException('An expired membership cannot be reactivated. Create a new one.');
            }
            $active = $this->dao->getActiveByUser($membership['user_id']);
            if ($active && (int) $active['id'] !== (int) $id) {
                throw new ValidationException('This user already has another active membership.');
            }
        }

        $this->dao->update($id, ['status' => $data['status']]);
        return $this->dao->getById($id);
    }

    public function delete($id)
    {
        $membership = $this->getById($id);
        if ($membership['status'] === 'active') {
            throw new ValidationException('An active membership cannot be deleted. Cancel it first.');
        }
        $this->dao->delete($id);
    }

    private function require_user($userId)
    {
        if (!$this->user_dao->getById($userId)) {
            throw new NotFoundException('User not found.');
        }
    }
}