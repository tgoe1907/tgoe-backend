<?php
declare(strict_types = 1);
namespace TgoeSrv\Tools;

class Logger
{

    private static function init(): void
    {
        // TODO: implement custom logging, with configurable log directory etc.
    }

    private static function log(string|\Stringable $message): void
    {
        error_log("TgoeSrv logging: " . $message);
    }

    public static function error(string|\Stringable $message): void
    {
        self::init();
        self::log("ERROR: " . $message);
    }

    public static function warning(string|\Stringable $message, array $context = []): void
    {
        self::init();
        self::log("WARNING: " . $message);
    }

    public static function info(string|\Stringable $message, array $context = []): void
    {
        self::init();
        self::log("INFO: " . $message);
        ;
    }
}
