<?php
use OpenApi\Attributes as OA;


#[OA\Info(
    title: 'GymHub API',
    description: 'REST API of GymHub, a gym management application: trainers, membership plans, classes, bookings and an external exercises API. Errors are returned as {"error": "..."}. Unexpected failures return 500 with a generic message.',
    version: '1.0'
)]
#[OA\Server(url: API_BASE_URL, description: 'This server')]
#[OA\Schema(
    schema: 'Error',
    properties: [new OA\Property(property: 'error', type: 'string', example: 'Field \'full_name\' is required.')]
)]
#[OA\Schema(
    schema: 'Message',
    properties: [new OA\Property(property: 'message', type: 'string', example: 'Deleted.')]
)]
#[OA\Schema(
    schema: 'TrainerInput',
    required: ['full_name', 'specialty'],
    properties: [
        new OA\Property(property: 'full_name', type: 'string', example: 'Amina Hadzic'),
        new OA\Property(property: 'specialty', type: 'string', example: 'Yoga & Pilates'),
        new OA\Property(property: 'bio', type: 'string', example: 'Focused on flexibility and recovery.'),
        new OA\Property(property: 'experience_years', type: 'integer', example: 8)
    ]
)]
#[OA\Schema(
    schema: 'Trainer',
    allOf: [new OA\Schema(ref: '#/components/schemas/TrainerInput')],
    properties: [new OA\Property(property: 'id', type: 'integer', example: 1)]
)]
#[OA\Schema(
    schema: 'MembershipInput',
    required: ['name', 'price', 'duration_days'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: '3 Months'),
        new OA\Property(property: 'price', type: 'number', example: 75),
        new OA\Property(property: 'duration_days', type: 'integer', example: 90),
        new OA\Property(property: 'description', type: 'string', example: 'Gym access and group classes')
    ]
)]
#[OA\Schema(
    schema: 'Membership',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 2),
        new OA\Property(property: 'name', type: 'string', example: '3 Months'),
        new OA\Property(property: 'price', type: 'string', example: '75.00'),
        new OA\Property(property: 'duration_days', type: 'integer', example: 90),
        new OA\Property(property: 'description', type: 'string', example: 'Gym access and group classes')
    ]
)]
#[OA\Schema(
    schema: 'ClassInput',
    required: ['trainer_id', 'name', 'start_time', 'capacity'],
    properties: [
        new OA\Property(property: 'trainer_id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Morning Yoga'),
        new OA\Property(property: 'start_time', type: 'string', example: '2030-01-01 10:00:00', description: 'YYYY-MM-DD HH:MM:SS, must be in the future'),
        new OA\Property(property: 'capacity', type: 'integer', example: 15),
        new OA\Property(property: 'description', type: 'string', example: 'Gentle start of the day')
    ]
)]
#[OA\Schema(
    schema: 'GymClass',
    allOf: [new OA\Schema(ref: '#/components/schemas/ClassInput')],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'trainer_name', type: 'string', example: 'Amina Hadzic')
    ]
)]
#[OA\Schema(
    schema: 'UserInput',
    required: ['full_name', 'email'],
    properties: [
        new OA\Property(property: 'full_name', type: 'string', example: 'Emma Brown'),
        new OA\Property(property: 'email', type: 'string', example: 'emma@example.com'),
        new OA\Property(property: 'password', type: 'string', example: 'Password123', description: 'Required when creating (8-72 characters). When updating, empty means keep the current password.'),
        new OA\Property(property: 'role', type: 'string', enum: ['admin', 'member'], example: 'member')
    ]
)]
#[OA\Schema(
    schema: 'User',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 2),
        new OA\Property(property: 'full_name', type: 'string', example: 'Emma Brown'),
        new OA\Property(property: 'email', type: 'string', example: 'emma@example.com'),
        new OA\Property(property: 'role', type: 'string', enum: ['admin', 'member'], example: 'member'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-11-14 12:50:13')
    ]
)]
#[OA\Schema(
    schema: 'UserMembershipInput',
    required: ['user_id', 'membership_id'],
    properties: [
        new OA\Property(property: 'user_id', type: 'integer', example: 1),
        new OA\Property(property: 'membership_id', type: 'integer', example: 2),
        new OA\Property(property: 'start_date', type: 'string', example: '2026-11-20', description: 'YYYY-MM-DD, optional (default: today). The end date is calculated from the plan.')
    ]
)]
#[OA\Schema(
    schema: 'UserMembership',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'user_id', type: 'integer', example: 2),
        new OA\Property(property: 'membership_id', type: 'integer', example: 2),
        new OA\Property(property: 'start_date', type: 'string', example: '2026-10-05'),
        new OA\Property(property: 'end_date', type: 'string', example: '2027-01-03'),
        new OA\Property(property: 'status', type: 'string', enum: ['active', 'expired', 'cancelled'], example: 'active'),
        new OA\Property(property: 'user_name', type: 'string', example: 'Emma Brown'),
        new OA\Property(property: 'membership_name', type: 'string', example: '3 Months')
    ]
)]
#[OA\Schema(
    schema: 'UserMembershipStatus',
    required: ['status'],
    properties: [new OA\Property(property: 'status', type: 'string', enum: ['active', 'expired', 'cancelled'], example: 'cancelled')]
)]
#[OA\Schema(
    schema: 'BookingInput',
    required: ['user_id', 'class_id'],
    properties: [
        new OA\Property(property: 'user_id', type: 'integer', example: 2),
        new OA\Property(property: 'class_id', type: 'integer', example: 4)
    ]
)]
#[OA\Schema(
    schema: 'BookingStatus',
    required: ['status'],
    properties: [new OA\Property(property: 'status', type: 'string', enum: ['booked', 'cancelled', 'completed'], example: 'cancelled')]
)]
#[OA\Schema(
    schema: 'Booking',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'user_id', type: 'integer', example: 2),
        new OA\Property(property: 'class_id', type: 'integer', example: 4),
        new OA\Property(property: 'status', type: 'string', enum: ['booked', 'cancelled', 'completed'], example: 'booked'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-11-14 12:50:13'),
        new OA\Property(property: 'user_name', type: 'string', example: 'Emma Brown'),
        new OA\Property(property: 'class_name', type: 'string', example: 'Boxing'),
        new OA\Property(property: 'start_time', type: 'string', example: '2026-11-17 19:00:00'),
        new OA\Property(property: 'trainer_name', type: 'string', example: 'Damir Mujic')
    ]
)]
#[OA\Schema(
    schema: 'Exercise',
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Hammer Curls'),
        new OA\Property(property: 'type', type: 'string', example: 'strength'),
        new OA\Property(property: 'muscle', type: 'string', example: 'biceps'),
        new OA\Property(property: 'difficulty', type: 'string', example: 'beginner'),
        new OA\Property(property: 'equipment', type: 'string', example: 'dumbbell'),
        new OA\Property(property: 'instructions', type: 'string', example: 'Stand with a dumbbell in each hand and curl them up...')
    ]
)]
#[OA\Schema(
    schema: 'ExerciseOptions',
    properties: [
        new OA\Property(property: 'muscles', type: 'array', items: new OA\Items(type: 'string'), example: ['biceps', 'chest', 'lats']),
        new OA\Property(property: 'difficulties', type: 'array', items: new OA\Items(type: 'string'), example: ['beginner', 'intermediate', 'expert'])
    ]
)]
class DocSetup
{
}