<?php

class WaehrungUtil
{

    /**
     * Formatiert einen Double Wert und formatiert in ein deutsches Währungsformat
     *
     * @param double $dbValue            
     * @return String
     */
    public static function formatDoubleToWaehrung($double)
    {
        return number_format($double, 2, ',', '.');
    }

    /**
     * Formatiert eine Waehrungsangabe in eine Datenbank konforme Zahl
     *
     * @param String $waehrungsEinheit            
     * @return double
     */
    public static function formatWaehrungToDB($waehrungsEinheit)
    {
        $waehrungsEinheit = str_replace(".", "", $waehrungsEinheit);
        $waehrungsEinheit = str_replace(",", ".", $waehrungsEinheit);
        $retVal = number_format($waehrungsEinheit, 2, '.', '');
        return $retVal;
    }

    public static function KommaToPunkt($s)
    {
        return str_replace(",", ".", $s);
    }
}
?>