<?php

class WaehrungUtil
{

    /**
     * Formatiert einen Double Wert und formatiert in ein deutsches Währungsformat
     *
     * @param double $dbValue            
     * @return string
     */
    public static function formatDoubleToWaehrung($double)
    {
        $valueToFormat = (empty($double) ? 0: $double);
        return number_format($valueToFormat, 2, ',', '.');
    }

    /**
     * Formatiert eine Waehrungsangabe in eine Datenbank konforme Zahl
     *
     * @param string $waehrungsEinheit            
     * @return string
     */
    public static function formatWaehrungToDB($waehrungsEinheit)
    {
        if($waehrungsEinheit == null || $waehrungsEinheit == "") {
            return $waehrungsEinheit;
        }
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