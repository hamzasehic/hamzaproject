<?php
require_once __DIR__ . '/BaseDao.php';

class MembershipDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('memberships');
    }
}