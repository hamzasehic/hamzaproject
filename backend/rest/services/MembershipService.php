<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/MembershipDao.php';
require_once __DIR__ . '/../dao/UserMembershipDao.php';

class MembershipService extends BaseService
{
    protected $entity_name = 'Membership';
    protected $allowed_fields = ['name', 'price', 'duration_days', 'description'];
    private $user_membership_dao;

    public function __construct($dao = null, $user_membership_dao = null)
    {
        parent::__construct($dao ?? new MembershipDao());
        $this->user_membership_dao = $user_membership_dao ?? new UserMembershipDao();
    }

    protected function prepare($data)
    {
        $data = parent::prepare($data);
        if (isset($data['description']) && trim($data['description']) === '') {
            $data['description'] = null;
        }
        return $data;
    }

    protected function validate($data, $id = null)
    {
        Validator::required($data, ['name', 'price', 'duration_days']);
        Validator::length($data['name'], 'name', 2, 50);
        Validator::number($data['price'], 'price', 0.01);
        if ($data['price'] > 10000) {
            throw new ValidationException("Field 'price' must be at most 10000.");
        }
        Validator::integer($data['duration_days'], 'duration_days', 1, 3650);
        if (!empty($data['description'])) {
            Validator::length($data['description'], 'description', 1, 255);
        }
    }

    public function delete($id)
    {
        $this->getById($id);
        $count = $this->user_membership_dao->countByMembership($id);
        if ($count > 0) {
            throw new ValidationException("This plan was bought $count time(s) and cannot be deleted.");
        }
        $this->dao->delete($id);
    }
}