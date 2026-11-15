<?php
use OpenApi\Attributes as OA;

class ClassPaths
{
    #[OA\Get(path: '/classes', tags: ['classes'], summary: 'List all classes (with the trainer name)', responses: [
        new OA\Response(response: 200, description: 'List of classes', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/GymClass')))
    ])]
    public function getClasses() {}

    #[OA\Get(path: '/classes/upcoming', tags: ['classes'], summary: 'List only the classes that have not started yet', responses: [
        new OA\Response(response: 200, description: 'List of upcoming classes', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/GymClass')))
    ])]
    public function getUpcomingClasses() {}

    #[OA\Get(path: '/classes/{id}', tags: ['classes'], summary: 'Get one class',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 1, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'The class', content: new OA\JsonContent(ref: '#/components/schemas/GymClass')),
            new OA\Response(response: 404, description: 'Class not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function getClassById() {}

    #[OA\Post(path: '/classes', tags: ['classes'], summary: 'Create a class (must start in the future, the trainer must exist)',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ClassInput')),
        responses: [
            new OA\Response(response: 201, description: 'Created class', content: new OA\JsonContent(ref: '#/components/schemas/GymClass')),
            new OA\Response(response: 400, description: 'Invalid data', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function addClass() {}

    #[OA\Put(path: '/classes/{id}', tags: ['classes'], summary: 'Update a class (capacity cannot drop below the number of bookings)',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 1, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ClassInput')),
        responses: [
            new OA\Response(response: 200, description: 'Updated class', content: new OA\JsonContent(ref: '#/components/schemas/GymClass')),
            new OA\Response(response: 400, description: 'Invalid data', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'Class not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function updateClass() {}

    #[OA\Delete(path: '/classes/{id}', tags: ['classes'], summary: 'Delete a class (not possible while it has active bookings)',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 1, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Class deleted', content: new OA\JsonContent(ref: '#/components/schemas/Message')),
            new OA\Response(response: 400, description: 'The class has active bookings', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'Class not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function deleteClass() {}
}