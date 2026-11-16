<?php
require_once __DIR__ . '/../../utils/exceptions.php';
require_once __DIR__ . '/../../utils/Validator.php';


class BaseService
{
    protected $dao;
    protected $entity_name = 'Entity';
    protected $allowed_fields = [];

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    public function getAll()
    {
        return $this->dao->getAll();
    }

    public function getById($id)
    {
        $entity = $this->dao->getById($id);
        if (!$entity) {
            throw new NotFoundException("{$this->entity_name} not found.");
        }
        return $entity;
    }

    public function add($data)
    {
        $data = $this->prepare($data);
        $this->validate($data);
        $id = $this->dao->insert($data);
        return $this->dao->getById($id);
    }

    public function update($id, $data)
    {
        $this->getById($id);              
        $data = $this->prepare($data);
        $this->validate($data, $id);
        $this->dao->update($id, $data);
        return $this->dao->getById($id);
    }

    public function delete($id)
    {
        $this->getById($id);
        $this->dao->delete($id);
    }

    protected function prepare($data)
    {
        if (!is_array($data)) {
            throw new ValidationException('Request body must be a JSON object.');
        }
        return array_intersect_key($data, array_flip($this->allowed_fields));
    }

    protected function validate($data, $id = null)
    {
    }
}