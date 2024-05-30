<?php

class Constant
{

    public static $HTML_CHECKED_CHECKED = "checked=\"checked\"";

    public static $HTML_SELECTED_SELECTED = "selected=\"selected\"";

    public static function getStimmung()
    {
        return array(
            0 => "Keine",
            1 => "Nebenstimmung",
            2 => "Hauptstimmung",
            3 => "Zungenstimmung",
            4 => "Wartung",
            5 => "Reparatur"
        );
    }
    
    public static function getDispositionTyp()
    {
        return array(
            1 => "Register",
            2 => "Transmission",
            3 => "Extension"
        );
    }
    
    public static function getRegisterTrakturen()
    {
        return array(
            0 => "Keine Angabe",
            1 => "Mechanisch",
            2 => "Elektrisch",
            3 => "Pneumatisch",
            4 => "Elektropneumatisch",
            5 => "Doppeltraktur"
        );
    }

    public static function getSpieltrakturen()
    {
        return array(
            0 => "Keine Angabe",
            1 => "Mechanisch",
            2 => "Elektrisch",
            3 => "Pneumatisch",
            4 => "Elektropneumatisch"
        );
    }

    public static function getWindladen()
    {
        return array(
            0 => "Keine Angabe",
            1 => "Schleiflade",
            2 => "Kegellade",
            3 => "Taschenlade",
            4 => "Springlade"
        );
    }

    public static function getKoppeln()
    {
        return array(
            0 => "Keine Angabe",
            1 => "Mechanisch",
            2 => "Elektrisch",
            3 => "Kombination",
            4 => "Pneumatisch"
        );
    }

    public static function getIntervallHauptstimmung()
    {
        return array(
            0 => "Niemals",
            1 => "Jedesmal",
            2 => "Jedes 2. Mal",
            3 => "Jedes 3. Mal",
            4 => "Jedes 4. Mal",
            5 => "Jedes 5. Mal",
            6 => "Jedes 6. Mal"
        );
    }
    
    public static function getZyklus()
    {
        return array(
            0 => "Kein Zyklus",
            1 => "1 Jahr",
            2 => "2 Jahre",
            3 => "3 Jahre",
            4 => "4 Jahre",
            5 => "5 Jahre",
            6 => "6 Jahre"
        );
    }

    public static function getPflegevertrag()
    {
        return array(
            0 => "Nein",
            1 => "Ja",
            2 => "Nicht Mehr"
        );
    }

    public static function getAnredeAuswahl()
    {
        return array(
            "keine" => "keine",
            "Herr" => "Herr",
            "Frau" => "Frau",
            "Herr und Frau" => "Herr und Frau"
        );
    }

    public static function getTitelAuswahl()
    {
        return array(
            "" => "",
            "Doktor" => "Doktor",
            "Professor" => "Professor"
        );
    }

    public static function getGeoStatusUserMessage($pKey, $pType = "Adresse")
    {
        // const OK = "OK";
        // const RESPONSE_IS_NULL = "RESPONSE_IS_NULL";
        // const NOT_FOUND = "NOT_FOUND";
        // const SERVICE_STATUS_FAILED = "SERVICE_STATUS_FAILED";
        // const SERVICE_NOT_OK = "SERVICE_NOT_OK";
        // const SERVICE_INVALID_RESPONSE = "SERVICE_INVALID_RESPONSE";
        // const SERVICE_NOT_AVAILABLE =
        // "SERVICE_NOT_AVAILABLE";
        $retVal = "Kein Status definiert.";
        switch ($pKey) {
            case IGeolocationConstants::OK:
                $retVal = "Status OK";
                break;
            case IGeolocationConstants::RESPONSE_IS_NULL:
                $retVal = "Bitte versuchen Sie es erneut. Der Adress-Such-Dienst war nicht verfuegbar. (" . $pType . ")";
                break;
            case IGeolocationConstants::NOT_FOUND:
                $retVal = "Die " . $pType . " wurde nicht gefunden. Bitte geben Sie korrekte Adressdaten ein.";
                break;
            case IGeolocationConstants::SERVICE_STATUS_FAILED:
                $retVal = "Die Ermittlung der " . $pType . " ist fehlgeschlagen. Bitte ueberpruefen Sie die Adressdaten.";
                break;
            case IGeolocationConstants::SERVICE_INVALID_RESPONSE:
                $retVal = "Die " . $pType . " konnten nicht verarbeitet werden. Bitte ueberpruefen Sie die Adressdaten. ";
                break;
            case IGeolocationConstants::SERVICE_NOT_AVAILABLE:
                $retVal = "Bitte versuchen Sie erneut die Route zu berechnen.";
                break;
            case OrgelbankGoogleMapsGeocoder::ADDRESS_NOT_UNIQUE:
                $retVal = "Die " . $pType . " kann nicht eindeutig ermittelt werden. Bitte korrigieren Sie die Adressdaten.";
                break;
            default:
                $retVal = "Fehler beim Ermitteln der " . $pType;
        }
        $retVal .= " (" . $pKey . ")";
        return $retVal;
    }
    
}
