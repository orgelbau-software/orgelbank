<?php

class GemeindeDetailsAction implements GetRequestHandler, PostRequestHandler
{

    private $mGemeindeID;

    private $mStatus;

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::validateGetRequest()
     */
    public function validateGetRequest()
    {
        return isset($_GET['gid']);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::handleInvalidGet()
     */
    public function handleInvalidGet()
    {
        return new HTMLStatus("Keine GemeindeID übergeben.");
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::prepareGet()
     */
    public function prepareGet()
    {
        $this->mGemeindeID = $_GET['gid'];
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
        $oGemeinde = new Gemeinde($this->mGemeindeID);
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
        
        $tplGemeindeDetails->replace("RKundenNr", $oGemeinde->getKundenNr());
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
                if($oAnsprechpartner->getTelefon() != "") {
                    $tplPartnerDS->replace("Telefon", $oAnsprechpartner->getTelefon());
                } else if($oAnsprechpartner->getMobil() != "") {
                    $tplPartnerDS->replace("Telefon", $oAnsprechpartner->getMobil());
                } else {
                    $tplPartnerDS->replace("Telefon", "");
                }
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
        
        if($this->mStatus != null) {
            $tplGemeindeDetails->replace("Status", $this->mStatus);
        } else {
            $tplGemeindeDetails->replace("Status", "");
        }
        
        // Gemeindedetails Template ausgeben
        return $tplGemeindeDetails;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::preparePost()
     */
    public function preparePost()
    {
        $this->mGemeindeID = intval($_POST['gid']);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::executePost()
     */
    public function executePost()
    {
        $oGemeinde = new Gemeinde($this->mGemeindeID);
        
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
        
        $oGemeinde->setKundenNr($_POST['rkundennr']);
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
        
        $this->mStatus = new HTMLStatus();
        $this->mStatus->setStatusclass(HTMLStatus::$STATUS_OK);
        $this->mStatus->setText("Die Gemeindedaten wurden gespeichert");
        
        $this->mGemeindeID = $oGemeinde->getID();
        
        return $this->executeGet();
    }
}