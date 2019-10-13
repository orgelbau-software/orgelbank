<?php

/**
 * Zeichnet den Methoden Ein/Ausgang beim Aufruf in TRACEFILE auf.
 * 
 * @since 01.12.2006
 * @author swatermeyer
 * @version $Revision: 1.5 $
 *
 */
class Trace
{

    private $fwFile;

    public function __construct()
    {}

    public function __destruct()
    {}

    public static function add($strKlasse, $strMessage)
    {
        if (TRACEENABLED) {
            $fwFile = new FileWriter(TRACEFILE);
            $fwFile->write(date("d.m.Y H:i:s") . substr(microtime(), 0, 10) . " \t " . $strKlasse . "\t" . $strMessage . "\r\n");
            $fwFile->close();
        }
    }

    public static function addStart($methode)
    {
        // echo $methode."<br>";
        if (TRACEENABLED) {
            $fwFile = new FileWriter(TRACEFILE);
            $fwFile->write(date("d.m.Y H:i:s") . substr(microtime(), 0, 10) . " \t " . $methode . "\t START \r\n");
            $fwFile->close();
        }
    }

    public static function addExit($methode)
    {
        if (TRACEENABLED) {
            $fwFile = new FileWriter(TRACEFILE);
            $fwFile->write(date("d.m.Y H:i:s") . substr(microtime(), 0, 10) . " \t " . $methode . "\t ENDE \r\n");
            $fwFile->close();
        }
    }
}
?>