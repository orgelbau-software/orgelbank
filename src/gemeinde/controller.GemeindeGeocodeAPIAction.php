<?php

class GemeindeGeocodeAPIAction implements GetRequestHandler
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
        $result = new DirectionsBean();
        $msg = "";
        $geocodeResult = - 1;
        $isKirchenAdresseOK = false;
        
        $o = new Gemeinde(intval($_GET['gid']));
        if ($o->getID() != - 1) {
            
            $kirche = array();
            $kirche['strasse'] = $o->getKircheAdresse()->getStrasse();
            $kirche['plz'] = $o->getKircheAdresse()->getPlz();
            $kirche['ort'] = $o->getKircheAdresse()->getOrt();
            $kirche['land'] = $o->getKircheAdresse()->getLand();
            
            $rechnung = array();
            $rechnung['strasse'] = $o->getRechnungAdresse()->getStrasse();
            $rechnung['plz'] = $o->getRechnungAdresse()->getPlz();
            $rechnung['ort'] = $o->getRechnungAdresse()->getOrt();
            $rechnung['land'] = $o->getRechnungAdresse()->getLand();
            
            $a = new Ansprechpartner(1);
            $hauptsitz = array();
            $hauptsitz['strasse'] = $a->getAdresse()->getStrasse();
            $hauptsitz['plz'] = $a->getAdresse()->getPlz();
            $hauptsitz['ort'] = $a->getAdresse()->getOrt();
            $hauptsitz['land'] = $a->getAdresse()->getLand();
            
            $requestData = array(
                "apiKey" => ConstantLoader::getGeocodeAPIServiceKey(),
                "origin" => $hauptsitz,
                "kirche" => $kirche,
                "rechnung" => $rechnung
            );
            
            $options = array(
                'http' => array(
                    'method' => 'POST',
                    'content' => json_encode($requestData),
                    'header' => "Content-Type: application/json\r\n" . "Accept: application/json\r\n"
                )
            );
            
            $context = stream_context_create($options);
            $serviceResponse = file_get_contents(ConstantLoader::getGeocodeAPIServiceEndpoint(), false, $context);
            // echo $serviceResponse;
            $serviceResponse = json_decode($serviceResponse);
            
            $result->setRC($serviceResponse->status);
            $o->getKircheAdresse()->setGeoStatus($serviceResponse->kirche->status);
            if ($serviceResponse->kirche->status == IGeolocationConstants::OK) {
                $o->getKircheAdresse()->setLat($serviceResponse->kirche->lat);
                $o->getKircheAdresse()->setLng($serviceResponse->kirche->lng);
            } else {
                $msg = Constant::getGeoStatusUserMessage($serviceResponse->kirche->status, "Kirchen-Adresse");
                $o->getKircheAdresse()->setGeoStatus(IGeolocationConstants::SERVICE_STATUS_FAILED);
                $result->setRC(IGeolocationConstants::SERVICE_STATUS_FAILED);
            }
            $o->speichern(false);
            
            $o->getRechnungAdresse()->setGeoStatus($serviceResponse->rechnung->status);
            if ($serviceResponse->rechnung->status == IGeolocationConstants::OK) {
                $o->getRechnungAdresse()->setLat($serviceResponse->kirche->lat);
                $o->getRechnungAdresse()->setLng($serviceResponse->kirche->lng);
            } else {
                $msg .= Constant::getGeoStatusUserMessage($serviceResponse->rechnung->status, "Rechnungs-Adresse");
                $o->getKircheAdresse()->setGeoStatus(IGeolocationConstants::SERVICE_STATUS_FAILED);
                $result->setRC(IGeolocationConstants::SERVICE_STATUS_FAILED);
            }
            $o->speichern(false);
            
            $result->setDuration($serviceResponse->duration);
            $result->setDistance($serviceResponse->distance);
            
            // Warum nicht speichern? Die anderen Daten werden ja auch gespeichert.
            $o->setDistanz($serviceResponse->distance);
            $o->setFahrtzeit($serviceResponse->duration);
            $o->speichern(false);
            
            // only overwrite status if the call before was also successful
            if ($msg == "" && $serviceResponse->status != IGeolocationConstants::OK) {
                $msg = $serviceResponse->message;
                $msg = $serviceResponse->message;
                $msg = Constant::getGeoStatusUserMessage($serviceResponse->status, "Route");
                $result->setRC(IGeolocationConstants::SERVICE_STATUS_FAILED);
            }
        }
        
        $result->setMessage($msg);
        
        header('Content-Type: application/json');
        $tpl = new Template("content.tpl");
        $tpl->replace("content", $result->toJSON());
        return $tpl;
    }
}
?>