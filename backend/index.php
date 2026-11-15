<?php
date_default_timezone_set('Europe/Sarajevo');
if (PHP_SAPI === 'cli-server' && str_starts_with(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/public/')) {
    return false;
}
require __DIR__ . '/vendor/autoload.php';

if (!file_exists(__DIR__ . '/config/config.php')) {
    http_response_code(500);
    header('Content-Type: application/json');
    exit(json_encode(['error' => 'Missing backend/config/config.php (copy config.example.php).']));
}

require_once __DIR__ . '/utils/exceptions.php';
require_once __DIR__ . '/rest/services/TrainerService.php';
require_once __DIR__ . '/rest/services/MembershipService.php';
require_once __DIR__ . '/rest/services/ClassService.php';
require_once __DIR__ . '/rest/services/UserService.php';
require_once __DIR__ . '/rest/services/BookingService.php';
require_once __DIR__ . '/rest/services/UserMembershipService.php';
require_once __DIR__ . '/rest/services/ExerciseService.php';

Flight::register('trainer_service', 'TrainerService');
Flight::register('membership_service', 'MembershipService');
Flight::register('class_service', 'ClassService');
Flight::register('user_service', 'UserService');
Flight::register('booking_service', 'BookingService');
Flight::register('user_membership_service', 'UserMembershipService');
Flight::register('exercise_service', 'ExerciseService');


Flight::map('error', function (Throwable $e) {
    if ($e instanceof HttpException) {
        $status = $e->getStatus();
        $message = $e->getMessage();
    } else {
        $status = 500;
        $message = 'Something went wrong on the server. Please try again later.';
        error_log($e);   
    }
    Flight::response()
        ->clearBody()
        ->status($status)
        ->header('Content-Type', 'application/json')
        ->write(json_encode(['error' => $message]))
        ->send();
});

Flight::map('notFound', function () {
    Flight::response()
        ->clearBody()
        ->status(404)
        ->header('Content-Type', 'application/json')
        ->write(json_encode(['error' => 'Endpoint not found.']))
        ->send();
});

Flight::route('GET /', function () {
    Flight::json(['app' => 'GymHub API', 'message' => 'The backend is running.']);
});

require_once __DIR__ . '/rest/routes/TrainerRoutes.php';
require_once __DIR__ . '/rest/routes/MembershipRoutes.php';
require_once __DIR__ . '/rest/routes/ClassRoutes.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/BookingRoutes.php';
require_once __DIR__ . '/rest/routes/UserMembershipRoutes.php';
require_once __DIR__ . '/rest/routes/ExerciseRoutes.php';

Flight::start();