<?php

class OrgelbankGoogleMapsDirectionsService extends GoogleMapsDirectionsService implements IDirectionsService
{

    const ADDRESS_NOT_UNIQUE = "ROUTE_NOT_UNIQUE";

    const ADDRESS_NOT_FOUND = "ADDRESS_NOT_FOUND";
    
    const ROUTE_NOT_FOUND = "ROUTE_NOT_FOUND";

    private $result = null;

    public function __construct($format = self::FORMAT_JSON, $sensor = false)
    {
        parent::__construct($format, $sensor);
        $this->setLanguage("de");
        $this->setApiKey(GOOGLE_MAPS_API_KEY);
    }

    public function getDirections($https = false, $raw = false)
    {
        try {
            // return $this->doGeocode($https, $raw);
            return $this->doGetDirections($https, $raw);
        } catch (Exception $e) {
            return self::SERVICE_NOT_AVAILABLE;
        }
    }

    protected function replaceUmlauts($pAddress)
    {
        $theString = str_replace("ä", "ae", $pAddress);
        $theString = str_replace("ü", "ue", $theString);
        $theString = str_replace("ö", "oe", $theString);
        $theString = str_replace("ß", "ss", $theString);
        return $theString;
    }

    public function setDestination($mDestination)
    {
        return parent::setDestination($this->replaceUmlauts($mDestination));
    }

    public function setOrigin($mOrigin)
    {
        return parent::setOrigin($this->replaceUmlauts($mOrigin));
    }

    /**
     *
     * @param string $https            
     * @param string $raw            
     * @return string StatusCode
     */
    public function doGetDirections($https = false, $raw = false)
    {
        $retVal = false;
        $response = parent::getDirections($https, $raw);
        if (null == $response) {
            $retVal = self::RESPONSE_IS_NULL;
        } elseif (isset($response['status'])) {
            if ($response['status'] == GoogleMapsGeocoder::STATUS_SUCCESS) {
                $this->resultCount = count($response['routes']);
                if (0 == $this->resultCount) {
                    $retVal = self::ROUTE_NOT_FOUND;
                    $this->result = null;
                } elseif (1 == $this->resultCount) {
                    $this->plainResult = $response['routes'][0];
                    $this->result = $this->mapResponseToResult($this->plainResult);
                    $retVal = self::OK;
                } else {
                    $retVal = self::ROUTE_NOT_UNIQUE;
                    $this->result = null;
                }
            } else {
                if ($response['status'] == GoogleMapsGeocoder::STATUS_NO_RESULTS) {
                    $retVal = self::ROUTE_NOT_FOUND;
                    $this->result = null;
                } elseif ($response['status'] == IGeolocationConstants::NOT_FOUND) {
                    $retVal = IGeolocationConstants::NOT_FOUND;
                } elseif ($response['status'] == GoogleMapsGeocoder::STATUS_INVALID_REQUEST) {
                    // echo "-->".$this->getAddress()."<br/>";
                    return GoogleMapsGeocoder::STATUS_INVALID_REQUEST;
                } else {
//                     echo "-->" . $response['status'];
//                     pre($response);
                    $retVal = IGeolocationConstants::SERVICE_NOT_OK;
                    $this->result = null;
                }
            }
        } else {
            $retVal = self::SERVICE_STATUS_FAILED;
        }
        // } else {
        // pre($response);
        // $retVal = self::SERVICE_INVALID_RESPONSE;
        // }
        return $retVal;
    }

    /**
     *
     * @param array $pResponse            
     * @return DirectionsBean
     */
    function mapResponseToResult($pResponse)
    {
        // print_r($pResponse);
        $retVal = new DirectionsBean();
        if (isset($pResponse['legs'])) {
            if (isset($pResponse['legs'][0])) {
                $retVal->setDistance(round($pResponse['legs'][0]['distance']['value'] / 1000, 0));
                $retVal->setDuration(round($pResponse['legs'][0]['duration']['value'] / 3600, 1));
                $retVal->setRC(0);
            } else {
                $retVal->setMessage("Unable to find leg 0: " . $pResponse);
                $retVal->setRC(1);
            }
        } else {
            $retVal->setMessage("Unable to find legs: " . $pResponse);
            $retVal->setRC(2);
        }
        return $retVal;
    }

    /**
     *
     * @return DirectionsBean
     */
    public function getResult()
    {
        return $this->result;
    }
}