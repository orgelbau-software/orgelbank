<?php
include_once '../../conf/config.inc.php';

$retVal = array();
$retVal['http_status'] = "500";
$retVal['message'] = "";

try {
    $db = DB::getInstance();
    $db->connect();
    
    ConstantLoader::performAutoload();
    
    if (isset($_GET['limit']) && is_numeric($_GET['limit'])) {
        $limit = $_GET['limit'];
    } else {
        $limit = ConstantLoader::getCronjobGeoStatusLimit();
    }
    
    if (intval($limit) <= 0) {
        $limit = 10;
    }
    
    $sql = "SELECT 
		   ad.* 
	     FROM 
		  adresse ad
		  WHERE 
		    (ad_geostatus IS NULL OR ad_geostatus = '') ";
    $sql .= " AND ad_type = 2 "; // Nur Kirch-Adressen, keine Ansprechpartner und keine Rechnungsadressen
    $sql .= " ORDER BY 
		      ad_id 
		    LIMIT " . $limit;
    
    $retVal['sql'] = $sql;
    $retVal['results'] = array();
    
    $res = $db->SelectQuery($sql);
    $Geocoder = new OrgelbankGoogleMapsGeocoder();
    // $Geocoder = new MockGeocoderService();
    $Directions = new OrgelbankGoogleMapsDirectionsService();
    // $Directions = new MockDirectionService();
    
    // Firmensitz laden und ggf. Geo-Koordinaten bestimmen.
    $firmensitz = new Ansprechpartner("1");
    if ($firmensitz->getAdresse()->getLat() == null || $firmensitz->getAdresse()->getLat() == "") {
        $Geocoder->setSearchAddress($firmensitz->getAdresse());
        $geocoderStatus = $Geocoder->geocode();
        if (OrgelbankGoogleMapsGeocoder::OK == $geocoderStatus) {
            $firmensitz->getAdresse()->setLat($Geocoder->getAdresse()
                ->getLat());
            $firmensitz->getAdresse()->setLng($Geocoder->getAdresse()
                ->getLng());
        }
        $firmensitz->getAdresse()->setGeoStatus($geocoderStatus);
        $firmensitz->speichern(true);
    }
    
    if ($firmensitz->getAdresse()->getLat() == null || $firmensitz->getAdresse()->getLat() == "") {
        $retVal['message'] = "unable to determine company adress, status: " . $firmensitz->getAdresse()->getGeoStatus() . ", Adresse: " . $firmensitz->getAdresse()->getFormattedAdress(true);
    } else if ($res) {
        $retVal['found'] = count($res);
        foreach ($res as $row) {
            $adresse = new Adresse();
            $adresse->doLoadFromArray($row);
            
            $current = array();
            
            $current['adress_id'] = $adresse->getID();
            $current['adress_type'] = $adresse->getType();
            $Geocoder->setSearchAddress($adresse);
            $geocoderStatus = $Geocoder->geocode();
            $adresse->setGeoStatus($geocoderStatus);
            if (OrgelbankGoogleMapsGeocoder::OK == $geocoderStatus || OrgelbankGoogleMapsGeocoder::PARTIAL_OK == $geocoderStatus) {
                $adresse->setLat($Geocoder->getAdresse()
                    ->getLat());
                $adresse->setLng($Geocoder->getAdresse()
                    ->getLng());
                $current['adress_status'] = $geocoderStatus;
                $current['adress_lat'] = $adresse->getLat();
                $current['adress_lng'] = $adresse->getLng();
                
                // Distanz nur ermitteln, nicht speichern
                $Directions->setOrigin($firmensitz->getAdresse()
                    ->getLat() . "," . $firmensitz->getAdresse()
                    ->getLng());
                $Directions->setDestination($adresse->getLat() . "," . $adresse->getLng());
                $Directions->setDestination($adresse->getFormattedAdress(true));
                $current['directions_status'] = $Directions->getDirections();
                $result = $Directions->getResult();
                if($result != null) {
                    $current['directions_result'] = $Directions->getResult()->toArray();
                } else {
                    $current['directions_result'] = "result is NULL";
                }
            } else {
                $current['not_found'] = $geocoderStatus;
            }
            
            $adresse->speichern(false);
            $retVal['results'][] = $current;
            
            // Google API is limited to 5 Calls / second
            // Because PHP can only wait 1 sec, we wait 1 sec after a call
            sleep(1);
        }
        $retVal['http_status'] = "200";
    } else {
        $retVal['message'] = "no results for: " . $sql;
        $retVal['http_status'] = "201";
    }
} catch (Throwable $t) {
    echo $t;
    $retVal['http_status'] = "501";
    $retVal['message'] = $t->getMessage();
    BenutzerController::doHilfeRufenCronjob($retVal);
}
header('Content-Type: application/json');
http_response_code($retVal['http_status']);
echo json_encode($retVal);
?>
