<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/exceptions.php';
require_once __DIR__ . '/../../utils/Validator.php';
require_once __DIR__ . '/../../utils/Logger.php';


class ExerciseService
{
    private const URL = 'https://api.api-ninjas.com/v1/exercises';
    private const CONNECT_TIMEOUT = 3;   
    private const TIMEOUT = 5;           

    public const MUSCLES = [
        'abdominals', 'abductors', 'adductors', 'biceps', 'calves', 'chest', 'forearms',
        'glutes', 'hamstrings', 'lats', 'lower_back', 'middle_back', 'neck',
        'quadriceps', 'traps', 'triceps',
    ];
    public const DIFFICULTIES = ['beginner', 'intermediate', 'expert'];

    public function getOptions()
    {
        return ['muscles' => self::MUSCLES, 'difficulties' => self::DIFFICULTIES];
    }

    public function search($muscle, $difficulty = null)
    {
        Validator::required(['muscle' => $muscle], ['muscle']);
        Validator::in_list($muscle, 'muscle', self::MUSCLES);
        if ($difficulty !== null && $difficulty !== '') {
            Validator::in_list($difficulty, 'difficulty', self::DIFFICULTIES);
        }

        $key = Config::EXTERNAL_API_KEY();
        if ($key === '') {
            Logger::error('External API (exercises): API key is not configured.');
            throw new HttpException('Exercise search is temporarily unavailable.', 503);
        }

        $params = ['muscle' => $muscle];
        if ($difficulty) {
            $params['difficulty'] = $difficulty;
        }
        $url = self::URL . '?' . http_build_query($params);

        $started = microtime(true);
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => self::CONNECT_TIMEOUT,
            CURLOPT_TIMEOUT => self::TIMEOUT,
            CURLOPT_HTTPHEADER => ['X-Api-Key: ' . $key, 'Accept: application/json'],
        ]);
        $body = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($curl);
        curl_close($curl);
        $ms = round((microtime(true) - $started) * 1000);

        if ($body === false) {
            Logger::error("External API (exercises) failed after {$ms}ms: $curl_error");
            throw new HttpException('The exercise service is not reachable right now. Please try again later.', 503);
        }
        if ($status < 200 || $status >= 300) {
            Logger::error("External API (exercises) returned HTTP $status after {$ms}ms");
            throw new HttpException('The exercise service is not available right now. Please try again later.', 503);
        }

        $data = json_decode($body, true);
        if (!is_array($data)) {
            Logger::error('External API (exercises) returned an unreadable response.');
            throw new HttpException('The exercise service returned an unexpected answer. Please try again later.', 503);
        }

        Logger::info('External API call: exercises ' . http_build_query($params) . ' -> ' . count($data) . " result(s) in {$ms}ms");

        return array_map(function ($item) {
            $equipment = $item['equipments'] ?? $item['equipment'] ?? '';
            if (is_array($equipment)) {
                $equipment = implode(', ', $equipment);
            }
            return [
                'name' => $item['name'] ?? '',
                'type' => $item['type'] ?? '',
                'muscle' => $item['muscle'] ?? '',
                'difficulty' => $item['difficulty'] ?? '',
                'equipment' => $equipment,
                'instructions' => $item['instructions'] ?? '',
            ];
        }, $data);
    }
}