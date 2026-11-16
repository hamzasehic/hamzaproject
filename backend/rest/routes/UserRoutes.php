<?php
Flight::route('GET /users', function () {
    Flight::json(Flight::user_service()->getAll());
});

Flight::route('GET /users/@id:[0-9]+', function ($id) {
    Flight::json(Flight::user_service()->getById($id));
});

Flight::route('POST /users', function () {
    Flight::json(Flight::user_service()->add(Flight::request()->data->getData()), 201);
});

Flight::route('PUT /users/@id:[0-9]+', function ($id) {
    Flight::json(Flight::user_service()->update($id, Flight::request()->data->getData()));
});

Flight::route('DELETE /users/@id:[0-9]+', function ($id) {
    Flight::user_service()->delete($id);
    Flight::json(['message' => 'User deleted.']);
});