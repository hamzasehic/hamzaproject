<?php
Flight::route('GET /user-memberships', function () {
    Flight::json(Flight::user_membership_service()->getAll());
});

Flight::route('GET /user-memberships/@id:[0-9]+', function ($id) {
    Flight::json(Flight::user_membership_service()->getById($id));
});

Flight::route('POST /user-memberships', function () {
    Flight::json(Flight::user_membership_service()->add(Flight::request()->data->getData()), 201);
});

Flight::route('PUT /user-memberships/@id:[0-9]+', function ($id) {
    Flight::json(Flight::user_membership_service()->update($id, Flight::request()->data->getData()));
});

Flight::route('DELETE /user-memberships/@id:[0-9]+', function ($id) {
    Flight::user_membership_service()->delete($id);
    Flight::json(['message' => 'User membership deleted.']);
});

Flight::route('GET /users/@id:[0-9]+/memberships', function ($id) {
    Flight::json(Flight::user_membership_service()->getByUser($id));
});

Flight::route('GET /users/@id:[0-9]+/membership', function ($id) {
    Flight::json(Flight::user_membership_service()->getActiveByUser($id));
});