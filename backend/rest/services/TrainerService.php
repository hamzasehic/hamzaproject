<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/TrainerDao.php';
require_once __DIR__ . '/../dao/ClassDao.php';

class TrainerService extends BaseService
{
    protected $entity_name = 'Trainer';
    protected $allowed_fields = ['full_name', 'specialty', 'bio', 'experience_years'];
    private $class_dao;

    public function __construct($dao = null, $class_dao = null)
    {
        parent::__construct($dao ?? new TrainerDao());
        $this->class_dao = $class_dao ?? new ClassDao();
    }

    protected function prepare($data)
    {
        $data = parent::prepare($data);
        if (!isset($data['experience_years']) || $data['experience_years'] === '') {
            $data['experience_years'] = 0;
        }
        return $data;
    }

    protected function validate($data, $id = null)
    {
        Validator::required($data, ['full_name', 'specialty']);
        Validator::length($data['full_name'], 'full_name', 2, 100);
        Validator::length($data['specialty'], 'specialty', 2, 100);
        Validator::integer($data['experience_years'], 'experience_years', 0, 60);
    }

    public function delete($id)
    {
        $this->getById($id);
        $count = count($this->class_dao->getByTrainer($id));
        if ($count > 0) {
            throw new ValidationException("This trainer has $count class(es) and cannot be deleted.");
        }
        $this->dao->delete($id);
    }
}