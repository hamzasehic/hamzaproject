<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/BookingDao.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/ClassDao.php';
require_once __DIR__ . '/../dao/UserMembershipDao.php';

class BookingService extends BaseService
{
    protected $entity_name = 'Booking';
    protected $allowed_fields = ['user_id', 'class_id', 'status'];
    private $user_dao;
    private $class_dao;
    private $user_membership_dao;

    public function __construct($dao = null, $user_dao = null, $class_dao = null, $user_membership_dao = null)
    {
        parent::__construct($dao ?? new BookingDao());
        $this->user_dao = $user_dao ?? new UserDao();
        $this->class_dao = $class_dao ?? new ClassDao();
        $this->user_membership_dao = $user_membership_dao ?? new UserMembershipDao();
    }

    /** The list includes the user's name and the class name and time. */
    public function getAll()
    {
        return $this->dao->getAllDetailed();
    }

    public function getByUser($userId)
    {
        if (!$this->user_dao->getById($userId)) {
            throw new NotFoundException('User not found.');
        }
        return $this->dao->getByUser($userId);
    }

    public function add($data)
    {
        $data = $this->prepare($data);
        Validator::required($data, ['user_id', 'class_id']);
        Validator::integer($data['user_id'], 'user_id', 1);
        Validator::integer($data['class_id'], 'class_id', 1);

        if (!$this->user_dao->getById($data['user_id'])) {
            throw new ValidationException('The selected user does not exist.');
        }
        $class = $this->class_dao->getById($data['class_id']);
        if (!$class) {
            throw new ValidationException('The selected class does not exist.');
        }

        if ($class['start_time'] <= date('Y-m-d H:i:s')) {
            throw new ValidationException('This class has already started, so it cannot be booked.');
        }

        if (!$this->user_membership_dao->getActiveByUser($data['user_id'])) {
            throw new ValidationException('You need an active membership to book a class.');
        }

        $existing = $this->dao->getByUserAndClass($data['user_id'], $data['class_id']);
        if ($existing && $existing['status'] === 'booked') {
            throw new ValidationException('This class is already booked.');
        }
        if ($existing && $existing['status'] === 'completed') {
            throw new ValidationException('This class has already been completed.');
        }

        if ($this->dao->countBooked($class['id']) >= $class['capacity']) {
            throw new ValidationException('This class is full.');
        }

        if ($existing) {
            $this->dao->update($existing['id'], ['status' => 'booked', 'created_at' => date('Y-m-d H:i:s')]);
            return $this->dao->getById($existing['id']);
        }

        $id = $this->dao->insert([
            'user_id' => $data['user_id'],
            'class_id' => $data['class_id'],
            'status' => 'booked',
        ]);
        return $this->dao->getById($id);
    }

    public function update($id, $data)
    {
        $booking = $this->getById($id);
        $data = $this->prepare($data);
        Validator::required($data, ['status']);
        Validator::in_list($data['status'], 'status', ['booked', 'cancelled', 'completed']);
        $new = $data['status'];

        if ($new === $booking['status']) {
            throw new ValidationException("The booking is already $new.");
        }
        if ($booking['status'] !== 'booked') {
            throw new ValidationException('A ' . $booking['status'] . ' booking cannot be changed. Create a new booking instead.');
        }

        $class = $this->class_dao->getById($booking['class_id']);
        $started = $class['start_time'] <= date('Y-m-d H:i:s');
        if ($new === 'cancelled' && $started) {
            throw new ValidationException('A class that has already started cannot be cancelled.');
        }
        if ($new === 'completed' && !$started) {
            throw new ValidationException('A booking cannot be completed before the class starts.');
        }

        $this->dao->update($id, ['status' => $new]);
        return $this->dao->getById($id);
    }

    public function cancel($id)
    {
        return $this->update($id, ['status' => 'cancelled']);
    }
}