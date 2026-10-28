<?php
require_once __DIR__ . '/rest/dao/BaseDao.php';

$dao = new BaseDao('users');
print_r($dao->getAll());