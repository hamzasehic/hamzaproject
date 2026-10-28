<?php
require_once __DIR__ . '/../rest/dao/UserDao.php';
require_once __DIR__ . '/../rest/dao/MembershipDao.php';
require_once __DIR__ . '/../rest/dao/TrainerDao.php';
require_once __DIR__ . '/../rest/dao/UserMembershipDao.php';
require_once __DIR__ . '/../rest/dao/ClassDao.php';
require_once __DIR__ . '/../rest/dao/BookingDao.php';

$trainerDao = new TrainerDao();
$userDao = new UserDao();
$classDao = new ClassDao();
$bookingDao = new BookingDao();
$userMembershipDao = new UserMembershipDao();

echo "--- CRUD on trainers ---\n";
$id = $trainerDao->insert(['full_name' => 'Test Trainer', 'specialty' => 'Test', 'experience_years' => 1]);
echo "Inserted id: $id\n";
print_r($trainerDao->getById($id));
echo "Updated rows: " . $trainerDao->update($id, ['specialty' => 'Updated']) . "\n";
print_r($trainerDao->getById($id));
echo "Deleted rows: " . $trainerDao->delete($id) . "\n";

echo "\n--- Custom methods ---\n";
print_r($userDao->getByEmail('emma@example.com'));
print_r($classDao->getUpcoming());
print_r($bookingDao->getByUser(2));
echo "Booked in class 1: " . $bookingDao->countBooked(1) . "\n";
print_r($userMembershipDao->getActiveByUser(2));

echo "\n--- Constraint test (duplicate email) ---\n";
try {
    $userDao->insert(['full_name' => 'Dup', 'email' => 'emma@example.com', 'password_hash' => 'x']);
} catch (PDOException $e) {
    echo "Rejected by database: " . $e->getMessage() . "\n";
}