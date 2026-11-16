<?php
Flight::route('GET /memberships', function () {
    Flight::json(Flight::membership_service()->getAll());
});

Flight::route('GET /memberships/@id:[0-9]+', function ($id) {
    Flight::json(Flight::membership_service()->getById($id));
});

Flight::route('POST /memberships', function () {
    Flight::json(Flight::membership_service()->add(Flight::request()->data->getData()), 201);
});

Flight::route('PUT /memberships/@id:[0-9]+', function ($id) {
    Flight::json(Flight::membership_service()->update($id, Flight::request()->data->getData()));
});

Flight::route('DELETE /memberships/@id:[0-9]+', function ($id) {
    Flight::membership_service()->delete($id);
    Flight::json(['message' => 'Membership deleted.']);
});