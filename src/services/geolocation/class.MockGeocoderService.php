<?php

class MockGeocoderService implements IGeocoderService
{

    /*
     * (non-PHPdoc)
     * @see IGeocoderService::geocode()
     */
    public function geocode($https = false, $raw = false)
    {
        return IGeolocationConstants::OK;
    }

    /*
     * (non-PHPdoc)
     * @see IGeocoderService::setSearchAddress()
     */
    public function setSearchAddress(Adresse $searchAddress)
    {
        // nothing
    }

    /**
     *
     * @return the $result
     */
    public function getAdresse()
    {
        $retVal = new Adresse();
        return $retVal;
    }
}

?>