<?php

class GemeindeGeocodeAction implements GetRequestHandler
{

    public function __construct()
    {}

    public function validateGetRequest()
    {
        return isset($_GET['gid']);
    }

    public function handleInvalidGet()
    {
        $htmlStatus = new HTMLRedirect();
        $htmlStatus->setLink("index.php?page=1&do=1");
        return $htmlStatus;
    }

    public function prepareGet()
    {
        $_GET['gid'] = intval($_GET['gid']);
    }

    public function executeGet()
    {
        $result = null;
        $msg = "";
        $geocodeResult = - 1;
        $isKirchenAdresseOK = false;
        
        $o = new Gemeinde(intval($_GET['gid']));
        if ($o->getID() != - 1) {
            $srvGeocode = new OrgelbankGoogleMapsGeocoder();
            if ($msg == "") {
                // Kirchen Adresse ermitteln
                
                $adresse = $o->getKircheAdresse()->getFormattedAdress();
                if ("" != $adresse) {
                    $srvGeocode->setAddress($adresse);
                    $geocodeResult = $srvGeocode->geocode();
                    $o->getKircheAdresse()->setGeoStatus($geocodeResult);
                    if ($geocodeResult != IGeolocationConstants::OK) {
                        $msg = Constant::getGeoStatusUserMessage($geocodeResult, "Kirchen-Adresse");
                    } else {
                        $o->getKircheAdresse()->setLat($srvGeocode->getAdresse()
                            ->getLat());
                        $o->getKircheAdresse()->setLng($srvGeocode->getAdresse()
                            ->getLng());
                        $isKirchenAdresseOK = true;
                    }
                } else {
                    $msg = Constant::getGeoStatusUserMessage($geocodeResult, "Kirchen-Adresse");
                    $o->getKircheAdresse()->setGeoStatus(IGeolocationConstants::SERVICE_STATUS_FAILED);
                }
                $o->speichern(false);
            }
            
            if ($msg == "") {
                $geocodeResult = - 1;
                $adresse = $o->getRechnungAdresse()->getFormattedAdress();
                $srvGeocode->setAddress($adresse);
                if ("" != $adresse) {
                    $geocodeResult = $srvGeocode->geocode();
                    $o->getRechnungAdresse()->setGeoStatus($geocodeResult);
                    if ($geocodeResult != IGeolocationConstants::OK) {
                        $msg = Constant::getGeoStatusUserMessage($geocodeResult, "Rechnungs-Adresse");
                    } else {
                        $o->getRechnungAdresse()->setLat($srvGeocode->getAdresse()
                            ->getLat());
                        $o->getRechnungAdresse()->setLng($srvGeocode->getAdresse()
                            ->getLng());
                    }
                } else {
                    $msg = Constant::getGeoStatusUserMessage($geocodeResult, "Rechnungs-Adresse");
                    $o->getKircheAdresse()->setGeoStatus(IGeolocationConstants::SERVICE_STATUS_FAILED);
                }
                
                $o->speichern(false);
            }
            
            if ($isKirchenAdresseOK) {
                $a = new Ansprechpartner(1);
                $srvDirection = new OrgelbankGoogleMapsDirectionsService();
                $srvDirection->setDestination($o->getKircheAdresse()
                    ->getFormattedAdress(true));
                $srvDirection->setOrigin($a->getAdresse()
                    ->getLat() . "," . $a->getAdresse()
                    ->getLng());
                if ($a->getAdresse()->getLat() == "" || $a->getAdresse()->getLat() == "") {
                    $srvDirection->setOrigin($a->getAdresse()
                        ->getFormattedAdress(true));
                }
                
                $currentGeocodeResult = $srvDirection->getDirections();
                $result = $srvDirection->getResult();
                if ($currentGeocodeResult != IGeolocationConstants::OK) {
                    $msg = Constant::getGeoStatusUserMessage($currentGeocodeResult, "Route");
                }
                
                // only overwrite status if the call before was also successful
                $geocodeResult = ($geocodeResult == IGeolocationConstants::OK ? $currentGeocodeResult : $geocodeResult);
            }
        } else {
            $msg = "Die Gemeinde muss erst gespeichert werden.";
        }
        
        if ($result == null) {
            $result = new DirectionsBean();
            $result->setMessage($msg);
        }
        // Der RC muss nach dem Service Aufruf gesetzt werden, da er im Service nicht gesetzt werden kann.
        $result->setRC($geocodeResult);
        $result->setMessage($msg);
        
        header('Content-Type: application/json');
        $tpl = new Template("content.tpl");
        $tpl->replace("content", $result->toJSON());
        return $tpl;
    }
}
?>