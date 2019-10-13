<?php

class Log implements Logger
{

    protected static $delegateLogger;

    public static function sql($string)
    {
        Log::$delegateLogger->sql($string);
    }

    public static function debug($string)
    {
        Log::$delegateLogger->debug($string);
    }

    public static function error($string)
    {
        Log::$delegateLogger->error($string);
    }

    public static function warn($string)
    {
        Log::$delegateLogger->warn($string);
    }

    public static function getLogger()
    {
        return Log::$delegateLogger;
    }

    public static function setLogger($logger)
    {
        Log::$delegateLogger = $logger;
    }
}
?>