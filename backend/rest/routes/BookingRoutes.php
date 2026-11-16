<?php
Flight::route('GET /bookings', function () {
    Flight::json(Flight::booking_service()->getAll());
});

Flight::route('GET /bookings/@id:[0-9]+', function ($id) {
    Flight::json(Flight::booking_service()->getById($id));
});

Flight::route('POST /bookings', function () {
    Flight::json(Flight::booking_service()->add(Flight::request()->data->getData()), 201);
});

Flight::route('PUT /bookings/@id:[0-9]+', function ($id) {
    Flight::json(Flight::booking_service()->update($id, Flight::request()->data->getData()));
});

Flight::route('POST /bookings/@id:[0-9]+/cancel', function ($id) {
    Flight::json(Flight::booking_service()->cancel($id));
});

Flight::route('DELETE /bookings/@id:[0-9]+', function ($id) {
    Flight::booking_service()->delete($id);
    Flight::json(['message' => 'Booking deleted.']);
});

Flight::route('GET /users/@id:[0-9]+/bookings', function ($id) {
    Flight::json(Flight::booking_service()->getByUser($id));
});