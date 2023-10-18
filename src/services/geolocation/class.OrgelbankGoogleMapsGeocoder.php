<?php

class OrgelbankGoogleMapsGeocoder extends GoogleMapsGeocoderService implements IGeocoderService
{

    private $resultCount = - 1;

    private $plainResult = null;

    private $result = null;

    /**
     *
     * @var Adresse
     */
    private $searchAddress = null;

    const ADDRESS_NOT_FOUND = "ADDRESS_NOT_FOUND";

    const PARTIAL_OK = "PARTIAL_OK";

    const ADDRESS_NOT_UNIQUE = "ADDRESS_NOT_UNIQUE";

    public function __construct($address = null, $format = self::FORMAT_JSON, $sensor = false)
    {
        parent::__construct($address, $format, $sensor);
        $this->setLanguage("de");
        $this->setApiKey(GOOGLE_MAPS_API_KEY);
    }

    public function geocode($https = false, $raw = false)
    {
        try {
            // return $this->doGeocode($https, $raw);
            return $this->doGeocodeWithRetry($https, $raw);
        } catch (Exception $e) {
             pre($e);
            return self::SERVICE_NOT_AVAILABLE;
        }
    }

    public function doGeocodeWithRetry($https = false, $raw = false)
    {
        $retVal = $this->doGeocode($https, $raw);
        if ($this->getSearchAddress() != null) {
            if ($retVal == self::ADDRESS_NOT_FOUND || $retVal == self::ADDRESS_NOT_FOUND || $retVal == GoogleMapsGeocoder::STATUS_INVALID_REQUEST) {
                // echo " " . $retVal . " " . $this->getAddress() . "\n";
                $this->setAddress($this->getSearchAddress()
                    ->getPlz() . " " . $this->getSearchAddress()
                    ->getOrt());
                $retVal = $this->doGeocode($https, $raw);
                if ($retVal == self::OK) {
                    $retVal = self::PARTIAL_OK;
                }
            }
        }
        return $retVal;
    }

    public function doGeocode($https = false, $raw = false)
    {
        $retVal = false;
        $response = parent::geocode($https, $raw);
        if (null == $response) {
            $retVal = self::RESPONSE_IS_NULL;
        } else {
            $length = count($response);
            // Warum 3 && = exclude_from_slo gesetzt ist manchmal, ist mir nicht klar
            if ($length == 2 || ($length == 3 && isset($response['exclude_from_slo']))) {
                if (isset($response['status'])) {
                    if ($response['status'] == GoogleMapsGeocoder::STATUS_SUCCESS) {
                        $this->resultCount = count($response['results']);
                        if (0 == $this->resultCount) {
                            $retVal = self::ADDRESS_NOT_FOUND;
                        } elseif (1 == $this->resultCount) {
                            $this->plainResult = $response['results'][0];
                            $this->result = $this->mapResponseToAdresse($this->plainResult);
                            $retVal = self::OK;
                        } else {
                            $retVal = self::ADDRESS_NOT_UNIQUE;
                        }
                    } else if ($response['status'] == GoogleMapsGeocoder::STATUS_NO_RESULTS) {
                        $retVal = self::ADDRESS_NOT_FOUND;
                    } elseif ($response['status'] == IGeolocationConstants::NOT_FOUND) {
                        $retVal = IGeolocationConstants::NOT_FOUND;
                    } elseif ($response['status'] == GoogleMapsGeocoder::STATUS_INVALID_REQUEST) {
                        // echo "-->".$this->getAddress()."<br/>";
                        return GoogleMapsGeocoder::STATUS_INVALID_REQUEST;
                    } else {
                        // echo "-->2" . $response['status'];
                        $retVal = self::SERVICE_NOT_OK;
                    }
                } else {
                    $retVal = self::SERVICE_STATUS_FAILED;
                }
            } else {
//                 echo count($response);
//                 pre($response);
                if(isset($response['error_message'])) {
                    throw new Exception("Orgelbank Geocoding failed with: " . $response['error_message']);
                } else {
                    $retVal = self::SERVICE_INVALID_RESPONSE;
                }
            }
            return $retVal;
        }
    }

    /**
     *
     * @return the $resultCount
     */
    public function getResultCount()
    {
        return $this->resultCount;
    }

    /**
     *
     * @return the $result
     */
    public function getPlainResult()
    {
        return $this->plainResult;
    }

    /**
     *
     * @return the $result
     */
    public function getAdresse()
    {
        return $this->result;
    }

    protected function mapResponseToAdresse($pResponse)
    {
        $retVal = new Adresse();
        if ($pResponse != null) {
            foreach ($pResponse["address_components"] as $currentComponent) {
                $value = $currentComponent['long_name'];
                foreach ($currentComponent['types'] as $currentType) {
                    if ($currentType == GoogleMapsGeocoder::TYPE_ROUTE) {
                        $retVal->setStrasse($value);
                    } elseif ($currentType == GoogleMapsGeocoder::TYPE_STREET_NUMBER) {
                        $retVal->setHausnummer($value);
                    } elseif ($currentType == GoogleMapsGeocoder::TYPE_POSTAL_CODE) {
                        $retVal->setPLZ($value);
                    } elseif ($currentType == GoogleMapsGeocoder::TYPE_LOCALITY) {
                        $retVal->setOrt($value);
                    } elseif ($currentType == GoogleMapsGeocoder::TYPE_COUNTRY) {
                        $retVal->setLand($value);
                    }
                }
            }
            
            $retVal->setLat($pResponse['geometry']['location']['lat']);
            $retVal->setLng($pResponse['geometry']['location']['lng']);
        }
        
        return $retVal;
    }

    public function setAddress($pAddress)
    {
        $theString = str_replace("ä", "ae", $pAddress);
        $theString = str_replace("ü", "ue", $theString);
        $theString = str_replace("ö", "oe", $theString);
        $theString = str_replace("ß", "ss", $theString);
        parent::setAddress($theString);
        return $this;
    }

    /**
     *
     * @return Adresse
     */
    public function getSearchAddress()
    {
        return $this->searchAddress;
    }

    public function setSearchAddress(Adresse $searchAddress)
    {
        $this->searchAddress = $searchAddress;
        $this->setAddress($searchAddress->getFormattedAdress(true));
    }
}

?>