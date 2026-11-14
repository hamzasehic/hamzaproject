<?php
require_once __DIR__ . '/../rest/services/TrainerService.php';

$service = new TrainerService();

function attempt($label, $callback)
{
    try {
        $result = $callback();
        echo "$label: OK\n";
        return $result;
    } catch (HttpException $e) {
        echo "$label: " . $e->getStatus() . ' ' . $e->getMessage() . "\n";
    }
}

$created = attempt('Add valid trainer', fn() => $service->add([
    'full_name' => 'Test Trainer', 'specialty' => 'Yoga', 'evil_column' => 'x'
]));
print_r($created);

attempt('Add without name', fn() => $service->add(['specialty' => 'Yoga']));
attempt('Add with bad experience', fn() => $service->add(['full_name' => 'Abc', 'specialty' => 'Yoga', 'experience_years' => 'many']));
attempt('Get missing trainer', fn() => $service->getById(9999));
attempt('Delete trainer with classes', fn() => $service->delete(1));
attempt('Delete test trainer', fn() => $service->delete($created['id']));