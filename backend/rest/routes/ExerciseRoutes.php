<?php
Flight::route('GET /exercises/options', function () {
    Flight::json(Flight::exercise_service()->getOptions());
});

Flight::route('GET /exercises', function () {
    $query = Flight::request()->query;
    Flight::json(Flight::exercise_service()->search($query['muscle'], $query['difficulty']));
});