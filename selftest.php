<?php
include "conf/config.inc.php";

// Datenbankverbindung herstellen
$db = DB::getInstance();
$db->connect();

$o[] = new AbschlagsRechnung();
$o[] = new Ansprechpartner();
$o[] = new Arbeitstag();
$o[] = new Aufgabe();
$o[] = new Benutzer();
$o[] = new BenutzerVerlauf();
$o[] = new EndRechnung();
$o[] = new Gemeinde();
$o[] = new Konfession();
$o[] = new OptionvalueObjekt();
$o[] = new Orgel();
$o[] = new PflegeRechnung();
$o[] = new Projekt();
// $o[] = new ProjektAufgabe();
$o[] = new ProjektRechnung();
$o[] = new RechnungsPosition();
$o[] = new Register();
$o[] = new Reisekosten();
$o[] = new StundenRechnung();
$o[] = new Wartung();

foreach ($o as $current) {
    $current->getLastSavedObjectForSelftest();
    echo get_class($current)."-".$current->getID()."<br>";
}

$geocoder = new OrgelbankGoogleMapsGeocoder();
$geocoder->setAddress("Baumschulweg 22, 37688 Beverungen");
$result = $geocoder->geocode();
echo "Geocoding: " . $result;
echo $geocoder->getAdresse ()->getLat ();
echo $geocoder->getAdresse ()->getLng ();

$srvDirection = new OrgelbankGoogleMapsDirectionsService ();
$srvDirection->setDestination ( "Twierweg 35a, 37671 Hoexter" );
$srvDirection->setOrigin ( $geocoder->getAdresse ()->getLat () . "," . $geocoder->getAdresse ()->getLng () );
$currentGeocodeResult = $srvDirection->getDirections ();
$result = $srvDirection->getResult ();

echo "Directions: " . $result;

// finally
$db->disconnect();

