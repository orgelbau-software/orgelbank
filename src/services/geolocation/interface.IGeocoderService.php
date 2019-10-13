<?php

interface IGeocoderService extends IGeolocationConstants
{

    /**
     * Führt die Suche nach der unter setSearchAddress gegebenen Adresse durch.
     *
     * @param bool $https            
     * @param bool $raw            
     * @return int eine Konstante von IGeolocationConstants
     */
    function geocode($https = false, $raw = false);

    /**
     * Setzt die Adresse die gesucht werden soll.
     *
     * @param Adresse $searchAddress
     *            die zu lokalisierende Adresse
     */
    function setSearchAddress(Adresse $searchAddress);

    /**
     *
     * @return AdressBean das gefundene AdressBean
     */
    function getAdresse();
}

?>