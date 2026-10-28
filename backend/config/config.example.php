<?php

class Config
{
    public static function DB_HOST()          { return self::get_env('DB_HOST', '127.0.0.1'); }
    public static function DB_PORT()          { return self::get_env('DB_PORT', 3306); }
    public static function DB_NAME()          { return self::get_env('DB_NAME', 'gymhub'); }
    public static function DB_USER()          { return self::get_env('DB_USER', 'root'); }
    public static function DB_PASSWORD()      { return self::get_env('DB_PASSWORD', ''); }
    public static function JWT_SECRET()       { return self::get_env('JWT_SECRET', ''); }
    public static function EXTERNAL_API_KEY() { return self::get_env('EXTERNAL_API_KEY', ''); }

    public static function get_env($name, $default)
    {
        $value = getenv($name);
        return ($value !== false && trim($value) !== '') ? $value : $default;
    }
}