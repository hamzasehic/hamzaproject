<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ClassDao.php';
require_once __DIR__ . '/../dao/TrainerDao.php';
require_once __DIR__ . '/../dao/BookingDao.php';

class ClassService extends BaseService
{
    protected $entity_name = 'Class';
    protected $allowed_fields = ['trainer_id', 'name', 'start_time', 'capacity', 'description'];
    private $trainer_dao;
    private $booking_dao;

    public function __construct($dao = null, $trainer_dao = null, $booking_dao = null)
    {
        parent::__construct($dao ?? new ClassDao());
        $this->trainer_dao = $trainer_dao ?? new TrainerDao();
        $this->booking_dao = $booking_dao ?? new BookingDao();
    }

    /** The list includes the trainer's name. */
    public function getAll()
    {
        return $this->dao->getAllWithTrainer();
    }

    public function getUpcoming()
    {
        return $this->dao->getUpcoming();
    }

    protected function prepare($data)
    {
        $data = parent::prepare($data);
        if (isset($data['description']) && trim($data['description']) === '') {
            $data['description'] = null;
        }
        return $data;
    }

    public function add($data)
    {
        $data = $this->prepare($data);
        $this->validate($data);

        if ($data['start_time'] <= date('Y-m-d H:i:s')) {
            throw new ValidationException('A new class must start in the future.');
        }

        $id = $this->dao->insert($data);
        return $this->dao->getById($id);
    }

    public function update($id, $data)
    {
        $this->getById($id);
        $data = $this->prepare($data);
        $this->validate($data, $id);

        $booked = $this->booking_dao->countBooked($id);
        if ($data['capacity'] < $booked) {
            throw new ValidationException("$booked spot(s) are already booked, so the capacity cannot be lower than that.");
        }

        $this->dao->update($id, $data);
        return $this->dao->getById($id);
    }

    public function delete($id)
    {
        $this->getById($id);
        $booked = $this->booking_dao->countBooked($id);
        if ($booked > 0) {
            throw new ValidationException("This class has $booked active booking(s) and cannot be deleted.");
        }
        $this->dao->delete($id);
    }

    protected function validate($data, $id = null)
    {
        Validator::required($data, ['trainer_id', 'name', 'start_time', 'capacity']);
        Validator::integer($data['trainer_id'], 'trainer_id', 1);
        Validator::length($data['name'], 'name', 2, 100);
        Validator::datetime($data['start_time'], 'start_time');
        Validator::integer($data['capacity'], 'capacity', 1, 200);
        if (!empty($data['description'])) {
            Validator::length($data['description'], 'description', 1, 255);
        }
        if (!$this->trainer_dao->getById($data['trainer_id'])) {
            throw new ValidationException('The selected trainer does not exist.');
        }
    }
}