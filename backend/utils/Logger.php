<?php

class Logger
{
    private static function write($file, $level, $message)
    {
        $dir = __DIR__ . '/../logs';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $line = '[' . date('Y-m-d H:i:s') . '] ' . $level . ' ' . $message . PHP_EOL;
        file_put_contents($dir . '/' . $file, $line, FILE_APPEND | LOCK_EX);
    }

    public static function info($message)
    {
        self::write('info.log', 'INFO', $message);
    }

    public static function error($message)
    {
        self::write('error.log', 'ERROR', $message);
    }
}