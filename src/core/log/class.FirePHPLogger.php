<?php

/**
 * Zum Loggen über die FireBug Konsole
 *
 * @author swatermeyer
 * @since 12.07.2009
 */
class FirePHPLogger implements Logger
{

    protected static $logEnabled = LOGENABLED;

    public static function sql($string)
    {
        if (FirePHPLogger::$logEnabled)
            FB::log($string, "SQL");
    }

    public static function debug($string)
    {
        if (FirePHPLogger::$logEnabled) {
            FB::log($string, "DEBUG");
        }
    }

    public static function error($string)
    {
        if (FirePHPLogger::$logEnabled) {
            FB::error($string);
        }
    }

    public static function warn($string)
    {
        if (FirePHPLogger::$logEnabled) {
            FB::warn($string);
        }
    }
}
?>