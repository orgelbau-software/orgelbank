<?php

interface IDirectionsService extends IGeolocationConstants
{

    /**
     * Startet die Berechnung andhand der angegbeen Origin und Destination.
     *
     * @param boolean $https            
     * @param boolean $raw            
     * @return int eine Konstante von IGeolocationConstants
     */
    function getDirections($https = false, $raw = false);

    /**
     *
     * @param String $mOrigin
     *            Formatierte Adress-String
     */
    function setOrigin($mOrigin);

    /**
     *
     * @param String $mDestination
     *            Formatierter Adress-String
     */
    function setDestination($mDestination);

    /**
     *
     * @return DirectionsBean
     */
    function getResult();
}