<?php
require_once __DIR__ . '/BaseDao.php';

class TrainerDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('trainers');
    }
}