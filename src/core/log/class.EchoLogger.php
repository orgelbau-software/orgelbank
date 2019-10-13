<?php

/**
 * Zum Loggen an der Oberflaeche
 *
 * @author swatermeyer
 * @since 28.02.2009
 */
class EchoLogger implements Logger
{

    private static $logSQL = true;

    private static $logDebug = true;

    private static $logWarn = true;

    private static $logError = true;

    private static $logEnabled = LOGENABLED;

    public static function sql($string)
    {
        if (EchoLogger::$logSQL)
            EchoLogger::out("<div class='logSQL'><div class='logDesc'>SQL:</div>" . $string . "<br/></div>");
    }

    public static function debug($string)
    {
        if (EchoLogger::$logDebug) {
            $s = "<div class='logDebug'><div class='logDesc'>DEBUG:</div>";
            if (! is_array($string)) {
                $s .= $string;
            } else {
                $s .= "<pre>";
                $s .= print_r($string, true);
                $s .= "</pre>";
            }
            $s .= "<br/></div>";
            EchoLogger::out($s);
        }
    }

    public static function error($string)
    {
        if (EchoLogger::$logError)
            EchoLogger::out("<div class='logError'><div class='logDesc'>DEBUG:</div>" . $string . "<br/></div>");
    }

    public static function warn($string)
    {
        if (EchoLogger::$logWarn)
            EchoLogger::out("<div class='logWarn'><div class='logDesc'>DEBUG:</div>" . $string . "<br/></div>");
    }

    private static function out($string)
    {
        if (EchoLogger::$logEnabled)
            echo $string;
    }
}
?>