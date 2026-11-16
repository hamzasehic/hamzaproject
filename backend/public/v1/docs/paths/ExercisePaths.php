<?php
use OpenApi\Attributes as OA;


class ExercisePaths
{
    #[OA\Get(
        path: '/exercises/options',
        tags: ['exercises'],
        summary: 'Allowed muscle groups and difficulty levels (for the dropdowns)',
        responses: [
            new OA\Response(response: 200, description: 'The allowed values', content: new OA\JsonContent(ref: '#/components/schemas/ExerciseOptions'))
        ]
    )]
    public function getExerciseOptions() {}

    #[OA\Get(
        path: '/exercises',
        tags: ['exercises'],
        summary: 'Search exercises (data comes from the external API Ninjas Exercises API)',
        description: 'The backend calls the external API with a timeout and keeps the API key secret. If the external service is unavailable, the answer is 503 with a friendly message and the rest of the application keeps working.',
        parameters: [
            new OA\Parameter(
                name: 'muscle',
                in: 'query',
                required: true,
                example: 'biceps',
                schema: new OA\Schema(
                    type: 'string',
                    enum: ['abdominals', 'abductors', 'adductors', 'biceps', 'calves', 'chest', 'forearms', 'glutes', 'hamstrings', 'lats', 'lower_back', 'middle_back', 'neck', 'quadriceps', 'traps', 'triceps']
                )
            ),
            new OA\Parameter(
                name: 'difficulty',
                in: 'query',
                required: false,
                example: 'beginner',
                schema: new OA\Schema(type: 'string', enum: ['beginner', 'intermediate', 'expert'])
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of exercises', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Exercise'))),
            new OA\Response(response: 400, description: 'Missing or invalid muscle or difficulty', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 503, description: 'The external exercise service is not available', content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function searchExercises() {}
}