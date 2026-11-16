<?php
Flight::route('GET /classes', function () {
    Flight::json(Flight::class_service()->getAll());
});

Flight::route('GET /classes/upcoming', function () {
    Flight::json(Flight::class_service()->getUpcoming());
});

Flight::route('GET /classes/@id:[0-9]+', function ($id) {
    Flight::json(Flight::class_service()->getById($id));
});

Flight::route('POST /classes', function () {
    Flight::json(Flight::class_service()->add(Flight::request()->data->getData()), 201);
});

Flight::route('PUT /classes/@id:[0-9]+', function ($id) {
    Flight::json(Flight::class_service()->update($id, Flight::request()->data->getData()));
});

Flight::route('DELETE /classes/@id:[0-9]+', function ($id) {
    Flight::class_service()->delete($id);
    Flight::json(['message' => 'Class deleted.']);
});