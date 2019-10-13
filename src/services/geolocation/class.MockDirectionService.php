<?php

class MockDirectionService implements IDirectionsService
{

    /*
     * (non-PHPdoc)
     * @see IDirectionsService::getDirections()
     */
    public function getDirections($https = false, $raw = false)
    {
        return IGeolocationConstants::OK;
    }

    public function setOrigin($mOrigin)
    {
        // Nothing
    }

    public function setDestination($mDestination)
    {
        // Nothing
    }

    public function getResult()
    {
        $retVal = new DirectionsBean();
        $retVal->setDistance(rand(0, 300));
        $retVal->setDuration(rand(1, 10));
        $retVal->setMessage("Ich bin ein Mock-Service");
        return $retVal;
    }
}

?>