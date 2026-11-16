<?php
use OpenApi\Attributes as OA;

class MembershipPaths
{
    #[OA\Get(path: '/memberships', tags: ['memberships'], summary: 'List all membership plans', responses: [
        new OA\Response(response: 200, description: 'List of plans', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Membership')))
    ])]
    public function getMemberships() {}

    #[OA\Get(path: '/memberships/{id}', tags: ['memberships'], summary: 'Get one membership plan',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 2, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'The plan', content: new OA\JsonContent(ref: '#/components/schemas/Membership')),
            new OA\Response(response: 404, description: 'Plan not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function getMembershipById() {}

    #[OA\Post(path: '/memberships', tags: ['memberships'], summary: 'Create a membership plan',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/MembershipInput')),
        responses: [
            new OA\Response(response: 201, description: 'Created plan', content: new OA\JsonContent(ref: '#/components/schemas/Membership')),
            new OA\Response(response: 400, description: 'Invalid data', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function addMembership() {}

    #[OA\Put(path: '/memberships/{id}', tags: ['memberships'], summary: 'Update a membership plan',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 2, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/MembershipInput')),
        responses: [
            new OA\Response(response: 200, description: 'Updated plan', content: new OA\JsonContent(ref: '#/components/schemas/Membership')),
            new OA\Response(response: 400, description: 'Invalid data', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'Plan not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function updateMembership() {}

    #[OA\Delete(path: '/memberships/{id}', tags: ['memberships'], summary: 'Delete a plan (not possible once it was bought)',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, example: 2, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Plan deleted', content: new OA\JsonContent(ref: '#/components/schemas/Message')),
            new OA\Response(response: 400, description: 'The plan was already bought', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'Plan not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ])]
    public function deleteMembership() {}
}