<?php

/**
 * Zum Loggen über die FireBug Konsole
 *
 * @author swatermeyer
 * @since 12.07.2009
 */
class DoNothingLogger implements Logger
{

    protected static $logEnabled = LOGENABLED;

    public static function sql($string)
    {}

    public static function debug($string)
    {}

    public static function error($string)
    {}

    public static function warn($string)
    {}
}
?>