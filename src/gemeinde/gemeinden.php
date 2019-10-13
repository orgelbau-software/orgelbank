<?php
include_once '../../conf/config.inc.php';

$db = DB::getInstance();
$db->connect();

session_start();

$user = new WebBenutzer();
if ($user->validateSession() == false) {
    die("keine gueltige session");
}

if (isset($_GET['action'])) {
    if ($_GET['action'] == "druck") {
        GemeindeController::zeigeGemeindeListeDruckansicht();
    } elseif ($_GET['action'] == "export" && isset($_GET['format'])) {
        if ($_GET['format'] == "xls") {
            GemeindeController::exportGemeindeListeExcel();
        }
    } elseif ($_GET['action'] == "ajax") {
        GemeindeController::ajaxGemeindeListeDruckansicht();
    } elseif ($_GET['action'] == "geocode") {
        ConstantLoader::performAutoload();
        if (ConstantLoader::getGeocodeAPIServiceActive()) {
            GemeindeController::geocodeGemeindeAPI();
        } else {
            GemeindeController::geocodeGemeinde();
        }
    } elseif ($_GET['action'] == "googlemaps") {
        GemeindeController::forwardGoogleMaps();
    }
}
?>