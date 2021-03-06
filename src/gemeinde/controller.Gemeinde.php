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
        if (! isset($_POST['gid']))
            return;
        
        $oGemeinde = new Gemeinde($_POST['gid']);
        
        if (! isset($_POST['hauptaid']))
            $_POST['hauptaid'] = 0;
        
        $oGemeinde->setKID($_POST['konfession']);
        $oGemeinde->setKirche($_POST['kirche']);
        $oGemeinde->getKircheAdresse()->setType(Adresse::TYPE_KIRCHE);
        $oGemeinde->getKircheAdresse()->setStrasse($_POST['strasse']);
        $oGemeinde->getKircheAdresse()->setHausnummer($_POST['hausnummer']);
        $oGemeinde->getKircheAdresse()->setPLZ($_POST['plz']);
        $oGemeinde->getKircheAdresse()->setOrt($_POST['ort']);
        $oGemeinde->getKircheAdresse()->setLand($_POST['land']);
        
        $oGemeinde->setRAnschrift($_POST['ranschrift']);
        $oGemeinde->setRGemeinde($_POST['rgemeinde']);
        
        $oGemeinde->getRechnungAdresse()->setType(Adresse::TYPE_RECHNUNG);
        $oGemeinde->getRechnungAdresse()->setStrasse($_POST['rstrasse']);
        $oGemeinde->getRechnungAdresse()->setHausnummer($_POST['rhausnummer']);
        $oGemeinde->getRechnungAdresse()->setPLZ($_POST['rplz']);
        $oGemeinde->getRechnungAdresse()->setOrt($_POST['rort']);
        $oGemeinde->getRechnungAdresse()->setLand($_POST['rland']);
        
        $oGemeinde->setBID($_POST['bezirk']);
        $oGemeinde->setDistanz($_POST['distanz']);
        $oGemeinde->setFahrtzeit($_POST['fahrzeit']);
        
        $oGemeinde->setAktiv(1);
        $oGemeinde->setAID($_POST['hauptaid']);
        
        $tplGeoStatus = new HTMLStatus("Die Gemeindedaten wurden gespeichert", 2, false);
        $redirectTime = 1;
        if ($oGemeinde->getKircheAdresse()->hasChanged()) {
            // $Geocoder = new OrgelbankGoogleMapsGeocoder();
            // $Geocoder->setSearchAddress($oGemeinde->getKircheAdresse());
            // $geocoderStatus = $Geocoder->geocode();
            $oGemeinde->getKircheAdresse()->setGeoStatus(null);
            // if(OrgelbankGoogleMapsGeocoder::OK == $geocoderStatus || OrgelbankGoogleMapsGeocoder::PARTIAL_OK == $geocoderStatus) {
            $oGemeinde->getKircheAdresse()->setLat(null);
            $oGemeinde->getKircheAdresse()->setLng(null);
            // } else {
            // $tplGeoStatus = new HTMLStatus("Die Gemeindedaten wurden gespeichert. Jedoch konnte die Adresse der Kirchengemeinde nicht gefunden werden.", 3, false);
            // $redirectTime = 3;
            // }
        }
        
        if ($oGemeinde->getRechnungAdresse()->hasChanged()) {
            $oGemeinde->getRechnungAdresse()->setGeoStatus(null);
            $oGemeinde->getRechnungAdresse()->setLat(null);
            $oGemeinde->getRechnungAdresse()->setLng(null);
        }
        
        $oGemeinde->speichern(true);
        $tplStatus = new HTMLRedirect($tplGeoStatus->getOutput(), "index.php?page=1&do=2&gid=" . $oGemeinde->getID(), $redirectTime);
        $tplStatus->anzeigen();
    }

    public static function zeigeGemeindeDetails()
    {
        if (! isset($_GET['gid']))
            return;
        
        $oGemeinde = new Gemeinde($_GET['gid']);
        $tplGemeindeDetails = new Template("gemeinde_details.tpl");
        $tplPartnerDS = new BufferedTemplate("gemeinde_ansprechpartner_ds.tpl", "css", "td1", "td2");
        $tplOrgelDS = new BufferedTemplate("gemeinde_details_orgel_ds.tpl");
        
        // Konfessionsausgabe
        $cKonf = KonfessionUtilities::getKonfessionen("ORDER BY k_name ASC");
        $htmlSelect = new HTMLSelect($cKonf, "getBezeichnung", $oGemeinde->getKID());
        
        // Inhalte ersetzen
        $tplGemeindeDetails->replace("GemeindeID", $oGemeinde->getID());
        $tplGemeindeDetails->replace("GemeindeNamen", $oGemeinde->getKirche());
        $tplGemeindeDetails->replace("Konfessionen", $htmlSelect->getOutput());
        
        $tplGemeindeDetails->replace("Kirche", $oGemeinde->getKirche());
        $tplGemeindeDetails->replace("Strasse", $oGemeinde->getKircheAdresse()
            ->getStrasse());
        $tplGemeindeDetails->replace("Hausnummer", $oGemeinde->getKircheAdresse()
            ->getHausnummer());
        $tplGemeindeDetails->replace("PLZ", $oGemeinde->getKircheAdresse()
            ->getPLZ());
        $tplGemeindeDetails->replace("Ort", $oGemeinde->getKircheAdresse()
            ->getOrt());
        
        $lat = "";
        if ($oGemeinde->getKircheAdresse()->getLat() != null && $oGemeinde->getKircheAdresse()->getLat() != "") {
            $lat = $oGemeinde->getKircheAdresse()->getLat();
        }
        
        $lng = "";
        if ($oGemeinde->getKircheAdresse()->getLng() != null && $oGemeinde->getKircheAdresse()->getLng() != "") {
            $lng = $oGemeinde->getKircheAdresse()->getLng();
        }
        
        $tplGemeindeDetails->replace("LatLng", $lat . "," . $lng);
        $tplGemeindeDetails->replace("Lat", $lat);
        $tplGemeindeDetails->replace("Lng", $lng);
        
        $tplGemeindeDetails->replace("RKirchenamt", $oGemeinde->getRAnschrift());
        $tplGemeindeDetails->replace("RGemeinde", $oGemeinde->getRGemeinde());
        $tplGemeindeDetails->replace("RStrasse", $oGemeinde->getRechnungAdresse()
            ->getStrasse());
        $tplGemeindeDetails->replace("RHausnummer", $oGemeinde->getRechnungAdresse()
            ->getHausnummer());
        $tplGemeindeDetails->replace("RPLZ", $oGemeinde->getRechnungAdresse()
            ->getPLZ());
        $tplGemeindeDetails->replace("ROrt", $oGemeinde->getRechnungAdresse()
            ->getOrt());
        $tplGemeindeDetails->replace("Bezirk", $oGemeinde->getBID());
        $tplGemeindeDetails->replace("KM", $oGemeinde->getDistanz());
        $tplGemeindeDetails->replace("Fahrzeit", $oGemeinde->getFahrtzeit());
        
        $selectLand = new HTMLSelectForArray(ConstantLoader::getLaenderAuswahl(), $oGemeinde->getKircheAdresse()->getLand());
        $tplGemeindeDetails->replace("Land", $selectLand->getOutput());
        $selectLand = new HTMLSelectForArray(ConstantLoader::getLaenderAuswahl(), $oGemeinde->getRechnungAdresse()->getLand());
        $tplGemeindeDetails->replace("RLand", $selectLand->getOutput());
        
        $tplGemeindeDetails->replace("Adresse", $oGemeinde->getKircheAdresse()
            ->getStrasse() . " " . $oGemeinde->getKircheAdresse()
            ->getHausnummer() . ", " . $oGemeinde->getKircheAdresse()
            ->getPLZ() . " " . $oGemeinde->getKircheAdresse()
            ->getOrt());
        $tplGemeindeDetails->replace("AdresseNurOrt", $oGemeinde->getKircheAdresse()
            ->getPLZ() . " " . $oGemeinde->getKircheAdresse()
            ->getOrt());
        
        // Kartenanzeige etc.
        $aInhaber = new Ansprechpartner(1);
        $tplGemeindeDetails->replace("RouteStartStrasse", $aInhaber->getAdresse()
            ->getStrasse());
        $tplGemeindeDetails->replace("RouteStartOrt", $aInhaber->getAdresse()
            ->getOrt());
        
        // Ansprechpartner
        $c = $oGemeinde->getAnsprechpartner();
        
        if ($c->getSize() > 0) {
            foreach ($c as $oAnsprechpartner) {
                if ($oAnsprechpartner->getID() == $oGemeinde->getAID())
                    $tplPartnerDS->replace("Checked", "checked");
                $tplPartnerDS->replace("Checked", "");
                
                $tplPartnerDS->replace("Funktion", $oAnsprechpartner->getFunktion());
                $tplPartnerDS->replace("Vorname", $oAnsprechpartner->getVorname());
                $tplPartnerDS->replace("Nachname", $oAnsprechpartner->getNachname());
                $tplPartnerDS->replace("Telefon", $oAnsprechpartner->getTelefon());
                $tplPartnerDS->replace("AID", $oAnsprechpartner->getID());
                $tplPartnerDS->next();
            }
        } else {
            $tplPartnerDS = new Template("gemeinde_ansprechpartner_keine.tpl");
            $tplPartnerDS->replace("GemeindeID", $oGemeinde->getID());
        }
        $tplGemeindeDetails->replace("Ansprechpartner", $tplPartnerDS->getOutput());
        
        // Orgeln
        $c = $oGemeinde->getOrgeln();
        
        if ($c->getSize() > 0) {
            foreach ($c as $oOrgel) {
                
                if ($oOrgel->getManual5() == 1) {
                    $manual = "V";
                } elseif ($oOrgel->getManual4() == 1) {
                    $manual = "IV";
                } elseif ($oOrgel->getManual3() == 1) {
                    $manual = "III";
                } elseif ($oOrgel->getManual2() == 1) {
                    $manual = "II";
                } elseif ($oOrgel->getManual1() == 1) {
                    $manual = "I";
                } else {
                    $manual = "keine Manuale";
                }
                if ($oOrgel->getPedal() == 1) {
                    $manual = $manual . "/Pedal";
                }
                
                // Im Template ersetzen
                $tplOrgelDS->replace("Manuale", $manual);
                $tplOrgelDS->replace("Register", $oOrgel->getRegisterAnzahl());
                $tplOrgelDS->replace("LetztePflege", $oOrgel->getLetztePflege(true));
                $tplOrgelDS->replace("Erbauer", $oOrgel->getErbauer());
                $tplOrgelDS->replace("Baujahr", $oOrgel->getBaujahr());
                $tplOrgelDS->replace("OID", $oOrgel->getID());
                $tplOrgelDS->next();
            }
        } else {
            $tplOrgelDS->loadNewTemplate("templates/gemeinde_details_orgel_keine.tpl");
            $tplOrgelDS->replace("GemeindeID", $oGemeinde->getID());
            $tplOrgelDS->next();
        }
        
        // Rechnungen
        global $webUser;
        
        $tplRechnungsDS = new BufferedTemplate("gemeinde_rechnung_ds.tpl", "CSS", "td1", "td2");
        $c = RechnungUtilities::zeigeGemeindeRechnungen($oGemeinde->getID(), " ORDER BY r_datum DESC");
        if ($webUser->isAdmin() && $c->getSize() > 0) {
            
            foreach ($c as $oRechnung) {
                $tplRechnungsDS->replace("Datum", $oRechnung->getDatum(true));
                $tplRechnungsDS->replace("Nummer", $oRechnung->getNummer());
                $tplRechnungsDS->replace("Typ", $oRechnung->getRechnungsTyp());
                $tplRechnungsDS->replace("TypId", $oRechnung->getRechnungsTypId());
                $tplRechnungsDS->replace("RID", $oRechnung->getId());
                $tplRechnungsDS->replace("Betrag", $oRechnung->getNettoBetrag(true) . " EUR (Netto)");
                $tplRechnungsDS->next();
            }
        } else {
            $tplRechnungsDS->replace("Nummer", "keine Rechnungen");
            $tplRechnungsDS->replace("Datum", "-");
            $tplRechnungsDS->replace("Typ", "-");
            $tplRechnungsDS->replace("TypId", "-");
            $tplRechnungsDS->replace("RID", "");
            $tplRechnungsDS->replace("Betrag", "-");
            $tplRechnungsDS->next();
        }
        $tplGemeindeDetails->replace("Rechnungen", $tplRechnungsDS->getOutput());
        
        // Orgeln im Template einsetzen
        $tplGemeindeDetails->replace("Orgeln", $tplOrgelDS->getOutput());
        
        // Gemeindedetails Template ausgeben
        $tplGemeindeDetails->anzeigen();
    }

    /**
     * Enter description here .
     *
     *
     *
     *
     *
     *
     *
     * ..
     */
    public static function zeigeGemeindeListe()
    {
        $tplGemeindeListe = new Template("gemeinde_liste.tpl");
        $tplGemeindeDS = new BufferedTemplate("gemeinde_liste_ds.tpl", "Farbwechsel", "td1", "td2");
        $tplGemeindeRubrik = new Template("gemeinde_liste_rubrik_first.tpl");
        $strRubriken = "";
        $boFirst = true;
        $iAnzahlGemeinden = GemeindeUtilities::getAnzahlGemeinden();
        // Rubriken f�r die Gemeindeansicht
        $konfession = KonfessionUtilities::getKonfessionenAsArray();
        
        // Bei wenig Kunden immer den Gesamtbestand anzeigen
        if (! isset($_GET['index']) && $iAnzahlGemeinden < ConstantLoader::getMindestAnzahlGemeindenFuerGruppierung()) {
            $_GET['index'] = "all";
        }
        
        $requestHandler = new GemeindeRequestHandler();
        $handledRequest = $requestHandler->prepareGemeindeListRequest();
        
        $tplGemeindeListe->replace("Dir", $handledRequest->getValueOf("TPLDIR"));
        $tplGemeindeListe->replace("Order", $handledRequest->getValueOf("TPLORDER"));
        
        $c = GemeindeUtilities::getGesuchteGemeinden($handledRequest->getValueOf("SUCHBEGRIFF"), $handledRequest->getValueOf("RESULT"));
        $x = GemeindeUtilities::getGesuchteGemeinden($handledRequest->getValueOf("SUCHBEGRIFF"));
        
        $tplGemeindeListe->replace("AnzahlGemeindenAnzeige", $c->getSize());
        $tplGemeindeListe->replace("AnzahlGemeindenGesamt", $iAnzahlGemeinden);
        
        $tplGemeindeListe->replace("SessionID", session_id());
        
        // Rubriken einbauen
        $oldindex = "null";
        $newindex = "foobar";
        
        // Array, der die Anfangszeichen speichert, damit sie nachher in der Rubrikenliste ausgegeben werden k�nnen
        $Anfangszeichen = array();
        foreach ($c as $oGemeinde) {
            
            // Neue Rubrik einfuegen, wenn neuer Anfangsbuchstabe/Zeichen
            if ($handledRequest->getValueOf("TPLORDER") == "bezirk") {
                $newindex = array(
                    "bezirk",
                    $oGemeinde->getGemeindeBezirk(),
                    $oGemeinde->getGemeindeBezirk()
                );
            } elseif ($handledRequest->getValueOf("TPLORDER") == "konfession") {
                $newindex = array(
                    "konfession",
                    $oGemeinde->getKID(),
                    $konfession[$oGemeinde->getKID()]
                );
            } elseif ($handledRequest->getValueOf("TPLORDER") == "ort") {
                $newindex = array(
                    "ort",
                    substr($oGemeinde->getGemeindeOrt(), 0, 1),
                    substr($oGemeinde->getGemeindeOrt(), 0, 1)
                );
            } elseif ($handledRequest->getValueOf("TPLORDER") == "plz") {
                $newindex = array(
                    "plz",
                    substr($oGemeinde->getGemeindePLZ(), 0, 1),
                    substr($oGemeinde->getGemeindePLZ(), 0, 1)
                );
            } else {
                $newindex = array(
                    "gemeinde",
                    substr($oGemeinde->getKirche(), 0, 1),
                    substr($oGemeinde->getKirche(), 0, 1)
                );
            }
            
            if ($newindex[1] != $oldindex[1]) {
                $tplGemeindeRubrik->replace("Rubrik", $newindex[2]);
                $tplGemeindeRubrik->replace("Dir", $handledRequest->getValueOf("TPLDIR"));
                $tplGemeindeRubrik->replace("Show", $handledRequest->getValueOf("TPLSHOW"));
                $tplGemeindeRubrik->replace("Index", $handledRequest->getValueOf("INDEX"));
                $tplGemeindeDS->addToBuffer($tplGemeindeRubrik);
                $tplGemeindeRubrik->restoreTemplate();
                if (trim($newindex[1]) != "")
                    $Anfangszeichen[] = $newindex;
                if ($boFirst) {
                    $boFirst = false;
                    $tplGemeindeRubrik = new Template("gemeinde_liste_rubrik.tpl");
                }
            }
            
            // Platzhalter ersetzen, Datensatz der Variablen anh�ngen, Template zur�cksetzen
            $tplGemeindeDS->replace("Gemeinde", $oGemeinde->getKirche());
            $tplGemeindeDS->replace("GemeindeID", $oGemeinde->getGemeindeID());
            $tplGemeindeDS->replace("PLZ", $oGemeinde->getGemeindePLZ());
            $tplGemeindeDS->replace("Ort", $oGemeinde->getGemeindeOrt());
            $tplGemeindeDS->replace("Land", ($oGemeinde->getGemeindeLand() == "Deutschland" ? "" : ", " . $oGemeinde->getGemeindeLand()));
            $tplGemeindeDS->replace("Konfession", $konfession[$oGemeinde->getKID()]);
            $tplGemeindeDS->replace("Bezirk", $oGemeinde->getGemeindeBezirk());
            
            if ($oGemeinde->getGeoStatus() != IGeolocationConstants::OK) {
                $tplGeoStatus = new Template("gemeinde_liste_geostatus.tpl");
                $tplGeoStatus->replace("Title", Constant::getGeoStatusUserMessage($oGemeinde->getGeoStatus()));
                $tplGemeindeDS->replace("GeoStatus", $tplGeoStatus->getOutput());
            }
            $tplGemeindeDS->replace("GeoStatus", "");
            $tplGemeindeDS->next();
            
            // Alten Index speichern
            $oldindex = $newindex;
        }
        
        // Gemeinden in Template einf�gen
        $tplGemeindeListe->replace("GemeindeListe", $tplGemeindeDS->getOutput());
        
        $suchbegriff = $handledRequest->getValueOf("SUCHBEGRIFF") == "" ? "Suchbegriff..." : $handledRequest->getValueOf("SUCHBEGRIFF");
        $tplGemeindeListe->replace("Suchbegriff", $suchbegriff);
        
        $lblArray = null;
        if ($handledRequest->getValueOf("TPLORDER") == "konfession") {
            $lblArray = $konfession;
        }
        
        $q = new Quickjump($x, $handledRequest->getValueOf("GETTER"), "index.php?page=1&do=1&order=" . $handledRequest->getValueOf("TPLORDER") . "&dir=asc&index=<!--Index-->", $handledRequest->getValueOf("SKALA"), $lblArray);
        $tplGemeindeListe->replace("Quickjump", $q->getOutput());
        // Template ausgeben
        $tplGemeindeListe->anzeigen();
    }

    public static function exportGemeindeListeExcel()
    {
        $requestHandler = new GemeindeRequestHandler();
        $handledRequest = $requestHandler->prepareGemeindeListRequest();
        error_reporting(E_ALL); // gibt sonst haessliche Fehler im Code
        
        $workbook = new OrgelbankExcelWriter();
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