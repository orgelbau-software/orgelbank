<?php
include_once '../../conf/config.inc.php';
echo "<pre>";

$service = new OrgelbankGoogleMapsDirectionsService();
$service->setOrigin("Beverungen");
$service->setDestination("Berlin");
// $service->setOrigin("51.3604619,8.8045264");
// $service->setDestination("51.11269,9.07167");
print_r($service->getDirections());

print_r($service->getResult());

echo "</pre>";

?>
