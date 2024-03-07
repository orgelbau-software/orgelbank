<?php

interface IDirectionsService extends IGeolocationConstants
{

    /**
     * Startet die Berechnung andhand der angegbeen Origin und Destination.
     *
     * @param boolean $https            
     * @param boolean $raw            
     * @return string eine Konstante von IGeolocationConstants
     */
    function getDirections($https = false, $raw = false);

    /**
     *
     * @param string $mOrigin
     *            Formatierte Adress-String
     */
    function setOrigin($mOrigin);

    /**
     *
     * @param string $mDestination
     *            Formatierter Adress-String
     */
    function setDestination($mDestination);

    /**
     *
     * @return DirectionsBean
     */
    function getResult();
}