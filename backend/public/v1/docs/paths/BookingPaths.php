<?php
use OpenApi\Attributes as OA;

class BookingPaths
{
    #[OA\Get(path: '/bookings', tags: ['bookings'], summary: 'List all bookings (with user and class names)', responses: [
        new OA\Response(response: 200, description: 'List of bookings', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Booking')))
    ])]
    public function getBookings() {}

    #[OA\Get(path: '/bookings/{id}', tags: ['bookings'], summary: 'Get one booking',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 1, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'The booking', content: new OA\JsonContent(ref: '#/components/schemas/Booking')),
            new OA\Response(response: 404, description: 'Booking not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function getBookingById() {}

    #[OA\Post(path: '/bookings', tags: ['bookings'], summary: 'Book a class',
        description: 'Rules: the user and the class must exist, the class must not have started, the user needs an active membership, the class must not be full, and the same class cannot be booked twice. A cancelled booking is reactivated.',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/BookingInput')),
        responses: [
            new OA\Response(response: 201, description: 'Created booking', content: new OA\JsonContent(ref: '#/components/schemas/Booking')),
            new OA\Response(response: 400, description: 'A booking rule is broken (class full, already booked, no active membership...)', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function addBooking() {}

    #[OA\Put(path: '/bookings/{id}', tags: ['bookings'], summary: 'Change the status of a booking (booked -> cancelled or completed)',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 1, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/BookingStatus')),
        responses: [
            new OA\Response(response: 200, description: 'Updated booking', content: new OA\JsonContent(ref: '#/components/schemas/Booking')),
            new OA\Response(response: 400, description: 'Status change not allowed', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'Booking not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function updateBooking() {}

    #[OA\Post(path: '/bookings/{id}/cancel', tags: ['bookings'], summary: 'Cancel a booking',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 1, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Cancelled booking', content: new OA\JsonContent(ref: '#/components/schemas/Booking')),
            new OA\Response(response: 400, description: 'The booking cannot be cancelled (already cancelled, completed or the class has started)', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'Booking not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function cancelBooking() {}

    #[OA\Delete(path: '/bookings/{id}', tags: ['bookings'], summary: 'Delete a booking',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 1, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Booking deleted', content: new OA\JsonContent(ref: '#/components/schemas/Message')),
            new OA\Response(response: 404, description: 'Booking not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function deleteBooking() {}

    #[OA\Get(path: '/users/{id}/bookings', tags: ['bookings'], summary: 'All bookings of one user',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 2, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'List of the user\'s bookings', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Booking'))),
            new OA\Response(response: 404, description: 'User not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function getUserBookings() {}
}