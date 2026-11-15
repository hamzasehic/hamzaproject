<?php
use OpenApi\Attributes as OA;

class UserMembershipPaths
{
    #[OA\Get(path: '/user-memberships', tags: ['user-memberships'], summary: 'List all purchased memberships', responses: [
        new OA\Response(response: 200, description: 'List of purchases', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/UserMembership')))
    ])]
    public function getUserMemberships() {}

    #[OA\Get(path: '/user-memberships/{id}', tags: ['user-memberships'], summary: 'Get one purchase',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 1, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'The purchase', content: new OA\JsonContent(ref: '#/components/schemas/UserMembership')),
            new OA\Response(response: 404, description: 'Purchase not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function getUserMembershipById() {}

    #[OA\Post(path: '/user-memberships', tags: ['user-memberships'], summary: 'Buy a membership for a user',
        description: 'The end date is calculated from the plan duration. A user can have only one active membership at a time.',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UserMembershipInput')),
        responses: [
            new OA\Response(response: 201, description: 'Created purchase', content: new OA\JsonContent(ref: '#/components/schemas/UserMembership')),
            new OA\Response(response: 400, description: 'Invalid data or the user already has an active membership', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function addUserMembership() {}

    #[OA\Put(path: '/user-memberships/{id}', tags: ['user-memberships'], summary: 'Change the status of a purchase',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 1, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UserMembershipStatus')),
        responses: [
            new OA\Response(response: 200, description: 'Updated purchase', content: new OA\JsonContent(ref: '#/components/schemas/UserMembership')),
            new OA\Response(response: 400, description: 'Status change not allowed', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'Purchase not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function updateUserMembership() {}

    #[OA\Delete(path: '/user-memberships/{id}', tags: ['user-memberships'], summary: 'Delete a purchase (an active membership must be cancelled first)',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 1, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Purchase deleted', content: new OA\JsonContent(ref: '#/components/schemas/Message')),
            new OA\Response(response: 400, description: 'The membership is still active', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'Purchase not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function deleteUserMembership() {}

    #[OA\Get(path: '/users/{id}/memberships', tags: ['user-memberships'], summary: 'Purchase history of one user',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 2, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'List of purchases', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/UserMembership'))),
            new OA\Response(response: 404, description: 'User not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function getUserMembershipHistory() {}

    #[OA\Get(path: '/users/{id}/membership', tags: ['user-memberships'], summary: 'The current active membership of a user',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 2, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'The active membership', content: new OA\JsonContent(ref: '#/components/schemas/UserMembership')),
            new OA\Response(response: 404, description: 'User not found or the user has no active membership', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function getActiveUserMembership() {}
}