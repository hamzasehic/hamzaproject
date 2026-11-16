<?php
use OpenApi\Attributes as OA;

class UserPaths
{
    #[OA\Get(path: '/users', tags: ['users'], summary: 'List all users', responses: [
        new OA\Response(response: 200, description: 'List of users', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/User')))
    ])]
    public function getUsers() {}

    #[OA\Get(path: '/users/{id}', tags: ['users'], summary: 'Get one user',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 2, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'The user', content: new OA\JsonContent(ref: '#/components/schemas/User')),
            new OA\Response(response: 404, description: 'User not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function getUserById() {}

    #[OA\Post(path: '/users', tags: ['users'], summary: 'Create a user (password is stored only as a bcrypt hash)',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UserInput')),
        responses: [
            new OA\Response(response: 201, description: 'Created user', content: new OA\JsonContent(ref: '#/components/schemas/User')),
            new OA\Response(response: 400, description: 'Invalid data or the email is already in use', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function addUser() {}

    #[OA\Put(path: '/users/{id}', tags: ['users'], summary: 'Update a user (empty password keeps the current one)',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 2, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UserInput')),
        responses: [
            new OA\Response(response: 200, description: 'Updated user', content: new OA\JsonContent(ref: '#/components/schemas/User')),
            new OA\Response(response: 400, description: 'Invalid data or the email is already in use', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'User not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function updateUser() {}

    #[OA\Delete(path: '/users/{id}', tags: ['users'], summary: 'Delete a user (the last admin cannot be deleted)',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 4, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'User deleted', content: new OA\JsonContent(ref: '#/components/schemas/Message')),
            new OA\Response(response: 400, description: 'The last admin cannot be deleted', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'User not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function deleteUser() {}
}