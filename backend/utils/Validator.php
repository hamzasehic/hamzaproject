<?php
require_once __DIR__ . '/exceptions.php';


class Validator
{
    public static function required($data, $fields)
    {
        foreach ($fields as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                throw new ValidationException("Field '$field' is required.");
            }
        }
    }

    public static function length($value, $field, $min, $max)
    {
        $length = mb_strlen(trim((string) $value));
        if ($length < $min || $length > $max) {
            throw new ValidationException("Field '$field' must have between $min and $max characters.");
        }
    }

    public static function integer($value, $field, $min = null, $max = null)
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            throw new ValidationException("Field '$field' must be a whole number.");
        }
        $number = (int) $value;
        if ($min !== null && $number < $min) {
            throw new ValidationException("Field '$field' must be at least $min.");
        }
        if ($max !== null && $number > $max) {
            throw new ValidationException("Field '$field' must be at most $max.");
        }
    }

    public static function number($value, $field, $min = null)
    {
        if (!is_numeric($value)) {
            throw new ValidationException("Field '$field' must be a number.");
        }
        if ($min !== null && $value < $min) {
            throw new ValidationException("Field '$field' must be at least $min.");
        }
    }

    public static function email($value, $field = 'email')
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException("Field '$field' must be a valid email address.");
        }
    }

    public static function in_list($value, $field, $allowed)
    {
        if (!in_array($value, $allowed, true)) {
            throw new ValidationException("Field '$field' must be one of: " . implode(', ', $allowed) . '.');
        }
    }

    public static function datetime($value, $field)
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', (string) $value);
        if (!$date || $date->format('Y-m-d H:i:s') !== $value) {
            throw new ValidationException("Field '$field' must be a date and time like 2026-12-01 18:00:00.");
        }
    }

    public static function date($value, $field)
    {
        $date = DateTime::createFromFormat('Y-m-d', (string) $value);
        if (!$date || $date->format('Y-m-d') !== $value) {
            throw new ValidationException("Field '$field' must be a date like 2026-12-01.");
        }
    }
}