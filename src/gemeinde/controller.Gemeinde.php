<?php

class GemeindeController
{

    public static function forwardGoogleMaps()
    {
        RequestHandler::handle(new GemeindeGoogleMapsForwardAction());
    }

    public static function zeigeGemeindeListeDruckansicht()
    {
        RequestHandler::handle(new GemeindeDruckansicht());
    }

    public static function ajaxGemeindeListeDruckansicht()
    {
        RequestHandler::handle(new GemeindeDruckansicht());
    }

    public static function loescheGemeinde()
    {
        RequestHandler::handle(new GemeindeLoeschenAction());
    }

    public static function geocodeGemeinde()
    {
        RequestHandler::handle(new GemeindeGeocodeAction());
    }
    
    
    public static function geocodeGemeindeAPI()
    {
    	RequestHandler::handle(new GemeindeGeocodeAPIAction());
    }

    public static function zeigeGemeindeLandkarte()
    {
        RequestHandler::handle(new GemeindeKarteAction());
    }

    public static function neueGemeindeAnlegen()
    {
        $tplGemeindeDetails = new Template("gemeinde_details_neu.tpl");
        
        // Konfessionsausgabe
        $cKonf = KonfessionUtilities::getKonfessionen("ORDER BY k_name ASC");
        $htmlSelect = new HTMLSelect($cKonf, "getBezeichnung", 0);
        $tplGemeindeDetails->replace("Konfessionen", $htmlSelect->getOutput());
        
        // Andere Daten
        $tplGemeindeDetails->replace("GemeindeID", "");
        $tplGemeindeDetails->replace("Kirche", "");
        $tplGemeindeDetails->replace("Strasse", "");
        $tplGemeindeDetails->replace("Hausnummer", "");
        $tplGemeindeDetails->replace("PLZ", "");
        $tplGemeindeDetails->replace("Ort", "");
        $tplGemeindeDetails->replace("RKirchenamt", "");
        $tplGemeindeDetails->replace("RGemeinde", "");
        $tplGemeindeDetails->replace("RStrasse", "");
        $tplGemeindeDetails->replace("RHausnummer", "");
        $tplGemeindeDetails->replace("RPLZ", "");
        $tplGemeindeDetails->replace("ROrt", "");
        $tplGemeindeDetails->replace("Bezirk", "");
        $tplGemeindeDetails->replace("KM", "");
        $tplGemeindeDetails->replace("Fahrzeit", "");
        
        $selectLand = new HTMLSelectForArray(ConstantLoader::getLaenderAuswahl(), "");
        $tplGemeindeDetails->replace("Land", $selectLand->getOutput());
        $selectLand = new HTMLSelectForArray(ConstantLoader::getLaenderAuswahl(), "");
        $tplGemeindeDetails->replace("RLand", $selectLand->getOutput());
        
        $tplGemeindeDetails->anzeigen();
    }

    public static function speichereGemeindeDetails()
    {
        RequestHandler::handle(new GemeindeDetailsAction());
    }

    public static function zeigeGemeindeDetails()
    {
        RequestHandler::handle(new GemeindeDetailsAction());
    }

    public static function zeigeGemeindeListe()
    {
        RequestHandler::handle(new GemeindeListeAction());
    }

    public static function exportGemeindeListeExcel()
    {
        $requestHandler = new GemeindeRequestHandler();
        $handledRequest = $requestHandler->prepareGemeindeListRequest();
        error_reporting(E_ALL); // gibt sonst haessliche Fehler im Code
        
        $workbook = new OrgelbankPHPSpreadsheetWriter();
        $workbook->setTempDir(TMPDIR);
        $worksheet = $workbook->addWorksheet();
        
        $frmFett = "bold";
        $cGemeinden = GemeindeUtilities::getDruckAnsichtGemeinden();
        Log::debug("count gemeinden=" . $cGemeinden->getSize());
        
        if ($cGemeinden->getSize() == 0) {
            $tpl = new HTMLFehlerseite("Ihre Auswahl enth&auml;lt keine Gemeinden");
            $tpl->anzeigen();
            return;
        }
        
        $konfession = KonfessionUtilities::getKonfessionenAsArray();
        
        $worksheet->write("A1", "Nr.", $frmFett);
        $worksheet->write("B1", "Gemeinde", $frmFett);
        $worksheet->write("C1", "PLZ", $frmFett);
        $worksheet->write("D1", "Ort", $frmFett);
        $worksheet->write("E1", "Konfession", $frmFett);
        $worksheet->write("F1", "Bezirk", $frmFett);
        $worksheet->write("G1", "Funktion", $frmFett);
        $worksheet->write("H1", "Nachname", $frmFett);
        $worksheet->write("I1", "Vorname", $frmFett);
        $worksheet->write("J1", "Telefon", $frmFett);
        
        $iZeile = 2;
        foreach ($cGemeinden as $gemeinde) {
            $worksheet->write("A" . $iZeile, $iZeile);
            $worksheet->write("B" . $iZeile, $gemeinde->getKirche());
            $worksheet->write("C" . $iZeile, $gemeinde->getGemeindePLZ());
            $worksheet->write("D" . $iZeile, $gemeinde->getGemeindeOrt());
            $worksheet->write("E" . $iZeile, $konfession[$gemeinde->getKID()]);
            $worksheet->write("F" . $iZeile, $gemeinde->getGemeindeBezirk());
            $worksheet->write("G" . $iZeile, $gemeinde->getFunktion());
            $worksheet->write("H" . $iZeile, $gemeinde->getNachname());
            $worksheet->write("I" . $iZeile, $gemeinde->getVorname());
            $worksheet->write("J" . $iZeile, $gemeinde->getTelefon());
            $iZeile ++;
            Log::debug($iZeile);
        }
        
        $workbook->download("GemeindeList-" . date("Ymd_Hi") . ".xlsx");
        $workbook->close();
    }
}
?>