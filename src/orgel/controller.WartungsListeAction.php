<?php

class WartungsListeAction implements GetRequestHandler, PostRequestHandler, PostRequestValidator
{

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::validateGetRequest()
     */
    public function validateGetRequest()
    {
        if (! isset($_GET['oid'])) {
            return false;
        }
        return true;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::handleInvalidGet()
     */
    public function handleInvalidGet()
    {
        return new HTMLStatus("Hier ist etwas schief gelaufen.", HTMLStatus::$STATUS_ERROR, false);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::prepareGet()
     */
    public function prepareGet()
    {
        // TODO Auto-generated method stub
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
        global $webUser;
        $oWartung = null;
        $htmlStatus = null;
        $tplWartung = new Template("orgel_wartung_details.tpl");
        
        // Session leeren, damit Ansprechpartner Verwaltung funktioniert
        unset($_SESSION['request']['oid']);
        
        if ($_POST) {
            $htmlStatus = new HTMLStatus();
            $htmlStatus->setStatusclass(2);
            $oOrgel = new Orgel($_POST['orgelId']);
            if ($_POST['submit'] == "Eintragen" || $_POST['submit'] == "Bearbeiten" || $_POST['submit'] == "Gehe Zu:") {
                if ($_POST['goto'] != "") {
                    $oOrgel = new Orgel(intval($_POST['goto']));
                    $redirectURL = "index.php?page=2&do=28&oid=";
                    if ($oOrgel->getID() == - 1) {
                        $tplInnerStatus = new HTMLStatus("Die Orgel mit der ID " . $_POST['goto'] . "existiert nicht. Bitte geben Sie eine gültige OrgelID ein.", 1);
                        $tplStatus = new HTMLRedirect($tplInnerStatus, $redirectURL . $_POST['orgelId'], 3);
                    } else {
                        $tplStatus = new HTMLRedirect("Sie werden weitergeleitet", $redirectURL . $oOrgel->getID());
                    }
                    
                    // haesslich aber so gehts...
                    return $tplStatus;
                } elseif ($_POST['submit'] == "Eintragen") {
                    $oWartung = new Wartung();
                    $oWartung->setOrgelId($oOrgel->getId());
                    $htmlStatus->setText("Wartung erfolgreich gespeichert.");
                } else {
                    $oWartung = new Wartung($_POST['wartungId']);
                    $htmlStatus->setText("Wartung erfolgreich bearbeitet.");
                }
                $oWartung->setBemerkung($_POST['bemerkung']);
                $oWartung->setMitarbeiterId1($_POST['mitarbeiter_1']);
                $oWartung->setMitarbeiterId2($_POST['mitarbeiter_2']);
                $oWartung->setMitarbeiterId3($_POST['mitarbeiter_3']);
                $oWartung->setMitarbeiterIstStd1($this->commaToDot($_POST['ma1_stunden_ist']));
                $oWartung->setMitarbeiterIstStd2($this->commaToDot($_POST['ma2_stunden_ist']));
                $oWartung->setMitarbeiterIstStd3($this->commaToDot($_POST['ma3_stunden_ist']));
                $oWartung->setMitarbeiterFaktStd1($this->commaToDot($_POST['ma1_stunden_fakt']));
                $oWartung->setMitarbeiterFaktStd2($this->commaToDot($_POST['ma2_stunden_fakt']));
                $oWartung->setMitarbeiterFaktStd3($this->commaToDot($_POST['ma3_stunden_fakt']));
                $oWartung->setTastenhalter(isset($_POST['tastenhalter']));
                $oWartung->setMaterial($_POST['material']);
                $oWartung->setAbrechnungsArtId($_POST['abrechnung']);
                
                $oWartung->setDatum(date("Y-m-d HH:ii:ss", strtotime($_POST['datum'])));
                $oWartung->setTemperatur($_POST['temperatur']);
                $oWartung->setLuftfeuchtigkeit($_POST['luftfeuchtigkeit']);
                $oWartung->setStimmtonHoehe($_POST['stimmtonhoehe']);
                $oWartung->setStimmung($_POST['stimmung']);
                $oWartung->setChangeBy($webUser->getBenutzername());
                $oWartung->speichern(true);
            } elseif ($_POST['submit'] == "Löschen") {
                $oWartung = new Wartung($_POST['wartungId']);
                WartungUtilities::deleteWartung($_POST['wartungId']);
                $htmlStatus->setText("Wartung erfolgreich gel&ouml;scht.");
            }
            
            // OrgelPflege Update
            if ($oWartung != null) {
                $oOrgel = new Orgel($oWartung->getOrgelId());
                $oLetzteWartung = WartungUtilities::getOrgelLetzteWartung($oWartung->getOrgelId());
                if ($oLetzteWartung != null) {
                    $oOrgel->setLetztePflege($oLetzteWartung->getDatum(false));
                } else {
                    $oOrgel->setLetztePflege(null);
                }
                
                $oOrgel->setMassnahmen($_POST['massnahmen']);
                $oOrgel->setAnmerkung($_POST['anmerkungen']);
                $oOrgel->speichern(false);
            } else {
                // Wartung wurde geloescht
            }
        }
        
        if (isset($_GET['action'], $_GET['wid'])) {
            if ($_GET['action'] == "delete") {
                $tplWartung->replace("SubmitValue", "L&ouml;schen");
            } elseif ($_GET['action'] == "edit") {
                $tplWartung->replace("SubmitValue", "Bearbeiten");
            }
        }
        
        if ($oWartung == null) {
            if (isset($_GET['wid']) && ! $_POST) {
                $oWartung = new Wartung(intval($_GET['wid']));
            } elseif (isset($_GET['oid'])) {
                // Neue Wartung
                $oWartung = new Wartung();
                $oWartung->setOrgelId(intval($_GET['oid']));
                $letzteWartung = WartungUtilities::getOrgelLetzteWartung($oWartung->getOrgelid());
                if ($letzteWartung != null) {
                    $oWartung->setTemperatur($letzteWartung->getTemperatur());
                    $oWartung->setStimmtonhoehe($letzteWartung->getStimmtonhoehe());
                    $oWartung->setMitarbeiterId1($letzteWartung->getMitarbeiterId1());
                    $letzteWartung->getStimmung() == "0" ? $oWartung->setStimmung("1") : $oWartung->setStimmung("2");
                }
            } else {
                throw new Exception("Keine OrgelID übergeben!");
            }
        }
        
        $tplWartungDS = new BufferedTemplate("orgel_wartung_details_ds.tpl", "CSS", "td1", "td2");
        
        $tplWartung->replace("OrgelId", $oWartung->getOrgelId());
        $tplWartung->replace("WartungId", $oWartung->getID());
        
        // Wartungsdatensaetze
        $col = WartungUtilities::getOrgelWartungen($oWartung->getOrgelId(), "ORDER BY w_datum DESC");
        $stimmungen = Constant::getStimmung();
        if ($col->getSize() > 0) {
            foreach ($col as $wartung) {
                $benutzer = new Benutzer($wartung->getMitarbeiterId1());
                $tplWartungDS->replace("WartungId", $wartung->getID());
                $tplWartungDS->replace("OrgelId", $wartung->getOrgelId());
                $tplWartungDS->replace("Datum", $wartung->getDatum(true));
                $tplWartungDS->replace("Mitarbeiter", $benutzer->getBenutzername());
                $tplWartungDS->replace("Bemerkung", $wartung->getBemerkung());
                $tplWartungDS->replace("Temperatur", ($wartung->getTemperatur() != "" ? $wartung->getTemperatur() . " °C" : ""));
                $tplWartungDS->replace("Stimmtonhoehe", ($wartung->getStimmtonHoehe() != "" ? $wartung->getStimmtonHoehe() . " HZ" : ""));
                $tplWartungDS->replace("Luftfeuchtigkeit", ($wartung->getLuftfeuchtigkeit() != "" ? $wartung->getLuftfeuchtigkeit() . " %" : ""));
                
                $stimmung = $stimmungen[$wartung->getStimmung()];
                $tplWartungDS->replace("Stimmung", $stimmung);
                
                $tplWartungDS->next();
            }
        } else {
            $tplWartungDS = new BufferedTemplate("orgel_wartung_details_keine.tpl");
            $tplWartungDS->next();
        }
        $tplWartung->replace("Wartungen", $tplWartungDS->getOutput());
        
        $cBenutzer = BenutzerUtilities::getBenutzer();
        $htmlSelectMitarbeiter1 = new HTMLSelect($cBenutzer, "getBenutzername", $oWartung->getMitarbeiterId1());
        $htmlSelectMitarbeiter2 = new HTMLSelect($cBenutzer, "getBenutzername", $oWartung->getMitarbeiterId2());
        $htmlSelectMitarbeiter3 = new HTMLSelect($cBenutzer, "getBenutzername", $oWartung->getMitarbeiterId3());
        $tplWartung->replace("MitarbeiterListe1", $htmlSelectMitarbeiter1->getOutput());
        $tplWartung->replace("MitarbeiterListe2", $htmlSelectMitarbeiter2->getOutput());
        $tplWartung->replace("MitarbeiterListe3", $htmlSelectMitarbeiter3->getOutput());
        
        $tplWartung->replace("Datum", date("d.m.Y"));
        $tplWartung->replace("Bemerkung", $oWartung->getBemerkung());
        $tplWartung->replace("Temperatur", $oWartung->getTemperatur());
        $tplWartung->replace("Luftfeuchtigkeit", $oWartung->getLuftfeuchtigkeit());
        $tplWartung->replace("Stimmtonhoehe", $oWartung->getStimmtonHoehe());
        $tplWartung->replace("SubmitValue", "Eintragen");
        
        if ($oWartung->getAbrechnungsArtId() == 1) {
            $tplWartung->replace("AbrVertrag", Constant::$HTML_SELECTED_SELECTED);
        } elseif ($oWartung->getAbrechnungsArtId() == 2) {
            $tplWartung->replace("AbrAufwand", Constant::$HTML_SELECTED_SELECTED);
        } elseif ($oWartung->getAbrechnungsArtId() == 3) {
            $tplWartung->replace("AbrGarantie", Constant::$HTML_SELECTED_SELECTED);
        }
        $tplWartung->replace("AbrVertrag", "");
        $tplWartung->replace("AbrAufwand", "");
        $tplWartung->replace("AbrGarantie", "");
        
        if ($oWartung->getStimmung() == 2) {
            $tplWartung->replace("Hauptstimmung", Constant::$HTML_SELECTED_SELECTED);
        } elseif ($oWartung->getStimmung() == 1) {
            $tplWartung->replace("Nebenstimmung", Constant::$HTML_SELECTED_SELECTED);
        } elseif ($oWartung->getStimmung() == 3) {
            $tplWartung->replace("Zungenstimmung", Constant::$HTML_SELECTED_SELECTED);
        } elseif ($oWartung->getStimmung() == 0) {
            $tplWartung->replace("NichtDurchgefuehrt", Constant::$HTML_SELECTED_SELECTED);
        } elseif ($oWartung->getStimmung() == 5) {
            $tplWartung->replace("Reparatur", Constant::$HTML_SELECTED_SELECTED);
        }
        $tplWartung->replace("Hauptstimmung", "");
        $tplWartung->replace("Nebenstimmung", "");
        $tplWartung->replace("Zungenstimmung", "");
        $tplWartung->replace("NichtDurchgefuehrt", "");
        $tplWartung->replace("Reparatur", "");
        
        $tplWartung->replace("Material", $oWartung->getMaterial());
        $tplWartung->replace("Ma1IstStd", $this->dotToComma($oWartung->getMitarbeiterIstStd1()));
        $tplWartung->replace("Ma2IstStd", $this->dotToComma($oWartung->getMitarbeiterIstStd2()));
        $tplWartung->replace("Ma3IstStd", $this->dotToComma($oWartung->getMitarbeiterIstStd3()));
        $tplWartung->replace("Ma1FaktStd", $this->dotToComma($oWartung->getMitarbeiterFaktStd1()));
        $tplWartung->replace("Ma2FaktStd", $this->dotToComma($oWartung->getMitarbeiterFaktStd2()));
        $tplWartung->replace("Ma3FaktStd", $this->dotToComma($oWartung->getMitarbeiterFaktStd3()));
        $tplWartung->replace("Tastenhalter", ($oWartung->getTastenhalter() == true ? Constant::$HTML_CHECKED_CHECKED : ""));
        
        // Gemeinde Details
        $oOrgel = new Orgel($oWartung->getOrgelId());
        $oGemeinde = new Gemeinde($oOrgel->getGemeindeId());
        $tplWartung->replace("Kirche", $oGemeinde->getKirche());
        
        // Sachen die zur Orgel gehoeren
        $tplWartung->replace("NotwendigeMassnahmen", $oOrgel->getMassnahmen());
        $tplWartung->replace("Anmerkungen", $oOrgel->getAnmerkung());
        
        // Orgel Ansprechpartner
        $tplWartung->replace("GemeindeId", $oOrgel->getGemeindeId());
        $cAnsprechpartner = AnsprechpartnerUtilities::getGemeindeAnsprechpartner($oGemeinde->getID());
        $tplAnsprechpartner = new BufferedTemplate("orgel_wartung_ansprechpartner_ds.tpl", "css", "td1", "td2");
        if ($cAnsprechpartner->getSize() > 0) {
            foreach ($cAnsprechpartner as $oAnsprechpartner) {
                $tplAnsprechpartner->replace("AID", $oAnsprechpartner->getId());
                $tplAnsprechpartner->replace("OID", $oOrgel->getId());
                $tplAnsprechpartner->replace("GID", $oOrgel->getGemeindeId());
                $tplAnsprechpartner->replace("Funktion", $oAnsprechpartner->getFunktion());
                $tplAnsprechpartner->replace("Name", $oAnsprechpartner->getAnzeigename());
                $tplAnsprechpartner->next();
            }
        } else {
            $tplAnsprechpartner = new Template("orgel_wartung_ansprechpartner_keine.tpl");
            $tplAnsprechpartner->replace("OID", $oOrgel->getID());
            $tplAnsprechpartner->replace("GID", $oOrgel->getGemeindeId());
        }
        $tplWartung->replace("Ansprechpartner", $tplAnsprechpartner->getOutput());
        
        $oWartung = null;
        
        // HTML Status
        if ($htmlStatus != null) {
            $tplWartung->replace("HTMLStatus", $htmlStatus->getOutput());
        }
        return $tplWartung;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::preparePost()
     */
    public function preparePost()
    {}

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::executePost()
     */
    public function executePost()
    {
        return $this->executeGet();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestValidator::validatePostRequest()
     */
    public function validatePostRequest()
    {
        if (! isset($_POST['orgelId'])) {
            return false;
        }
        return true;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestValidator::handleInvalidPost()
     */
    public function handleInvalidPost()
    {
        return $this->handleInvalidGet();
    }
    
    private function commaToDot($pValue) {
        if($pValue == null) { 
            return null;
        }
        $stunden = str_replace(",", ".", $pValue);
        return doubleval($stunden);
    }
    
    private function dotToComma($pValue) {
        if($pValue == null) {
            return null;
        }
        return str_replace(".", ",", $pValue);
    }
}