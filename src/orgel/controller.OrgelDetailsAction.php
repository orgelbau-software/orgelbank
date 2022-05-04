<?php

class OrgelDetailsAction implements GetRequestHandler
{

    private $mOrgelId;

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
        $this->mOrgelId = intval($_GET['oid']);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
        $tplOrgelDetails = new Template("orgel_details.tpl");
        $tplSelectOption = new Template("select_option.tpl");
        $oOrgel = new Orgel($this->mOrgelId);
        $oGemeinde = new Gemeinde($oOrgel->getGemeindeId());
        $strContent = "";
        
        // Checkboxen fuer die Manuale praeperieren
        $arManuale = array(
            1 => "",
            2 => "",
            3 => "",
            4 => "",
            5 => "",
            6 => ""
        );
        
        if ($oOrgel->getManual1() == 1)
            $arManuale[1] = "checked";
        if ($oOrgel->getManual2() == 1)
            $arManuale[2] = "checked";
        if ($oOrgel->getManual3() == 1)
            $arManuale[3] = "checked";
        if ($oOrgel->getManual4() == 1)
            $arManuale[4] = "checked";
        if ($oOrgel->getManual5() == 1)
            $arManuale[5] = "checked";
        if ($oOrgel->getPedal() == 1)
            $arManuale[6] = "checked";
        
        $anzahlManuale = OrgelUtilities::getOrgelManualeUebersicht($oOrgel);
        $tplOrgelDetails->replace("AnzahlManuale", $anzahlManuale);
        
        if($oOrgel->getRegisterAnzahl() > 0) {
            $anzahlManuale .= " - ".$oOrgel->getRegisterAnzahl();
        }
        
        // Replaces
        $tplOrgelDetails->replace("OID", $oOrgel->getID());
        $tplOrgelDetails->replace("GID", $oOrgel->getGemeindeID());
        $tplOrgelDetails->replace("Erbauer", $oOrgel->getErbauer());
        $tplOrgelDetails->replace("Baujahr", $oOrgel->getBaujahr());
        $tplOrgelDetails->replace("Renoviert", $oOrgel->getRenoviert());
        $tplOrgelDetails->replace("Renovierer", $oOrgel->getRenovierer());
        $tplOrgelDetails->replace("NotwendigeMassnahmen", $oOrgel->getMassnahmen());
        $tplOrgelDetails->replace("Hauptstimmung", $oOrgel->getHauptstimmung());
        $tplOrgelDetails->replace("StimmungNach", $oOrgel->getStimmung());
        $tplOrgelDetails->replace("Register", $oOrgel->getRegisterAnzahl());
        $tplOrgelDetails->replace("Anmerkung", stripslashes($oOrgel->getAnmerkung()));
        $tplOrgelDetails->replace("AnzahlManualeUndRegister", $anzahlManuale);
        
        
        // Manuale Checkboxen
        $tplOrgelDetails->replace("m1", $arManuale[1]);
        $tplOrgelDetails->replace("m2", $arManuale[2]);
        $tplOrgelDetails->replace("m3", $arManuale[3]);
        $tplOrgelDetails->replace("m4", $arManuale[4]);
        $tplOrgelDetails->replace("m5", $arManuale[4]);
        $tplOrgelDetails->replace("m6", $arManuale[6]);
        
        // Manuale Groesse
        $tplOrgelDetails->replace("m1groesse", stripslashes($oOrgel->getGroesseM1()));
        $tplOrgelDetails->replace("m2groesse", stripslashes($oOrgel->getGroesseM2()));
        $tplOrgelDetails->replace("m3groesse", stripslashes($oOrgel->getGroesseM3()));
        $tplOrgelDetails->replace("m4groesse", stripslashes($oOrgel->getGroesseM4()));
        $tplOrgelDetails->replace("m5groesse", stripslashes($oOrgel->getGroesseM5()));
        $tplOrgelDetails->replace("m6groesse", stripslashes($oOrgel->getGroesseM6()));
        
        // Manuale Winddruck
        $tplOrgelDetails->replace("m1wd", $oOrgel->getWinddruckM1());
        $tplOrgelDetails->replace("m2wd", $oOrgel->getWinddruckM2());
        $tplOrgelDetails->replace("m3wd", $oOrgel->getWinddruckM3());
        $tplOrgelDetails->replace("m4wd", $oOrgel->getWinddruckM4());
        $tplOrgelDetails->replace("m5wd", $oOrgel->getWinddruckM5());
        $tplOrgelDetails->replace("m6wd", $oOrgel->getWinddruckM6());
        
        $htmlSelectStatus = new HTMLSelectForArray(Constant::getOrgelStatus(), $oOrgel->getOstID());
        $tplOrgelDetails->replace("Orgelstatus", $htmlSelectStatus->getOutput());
        
        $htmlSelectWinlade = new HTMLSelectForArray(Constant::getWindladen(), $oOrgel->getWindladeID());
        $tplOrgelDetails->replace("Windlade", $htmlSelectWinlade->getOutput());
        
        $htmlSelectTraktur = new HTMLSelectForArray(Constant::getSpieltrakturen(), $oOrgel->getSpieltrakturID());
        $tplOrgelDetails->replace("Spieltraktur", $htmlSelectTraktur->getOutput());
        
        $htmlSelectKoppel = new HTMLSelectForArray(Constant::getKoppeln(), $oOrgel->getKoppelID());
        $tplOrgelDetails->replace("Koppel", $htmlSelectKoppel->getOutput());
        
        $htmlSelectRegister = new HTMLSelectForArray(Constant::getRegisterTrakturen(), $oOrgel->getRegistertrakturID());
        $tplOrgelDetails->replace("Registertraktur", $htmlSelectRegister->getOutput());
        
        $htmlZyklusSelect = new HTMLSelectForArray(Constant::getZyklus(), $oOrgel->getZyklus());
        $tplOrgelDetails->replace("ZyklusSelect", $htmlZyklusSelect->getOutput());
        
        $htmlIntervalHauptstimmung = new HTMLSelectForArray(Constant::getIntervallHauptstimmung(), $oOrgel->getIntervallHauptstimmung());
        $tplOrgelDetails->replace("IntervallHaupstimmungSelect", $htmlIntervalHauptstimmung->getOutput());
        
        // Kosten Haupt und Teilstimmung
        $tplOrgelDetails->replace("KostenHauptstimmung", $oOrgel->getKostenHauptstimmung());
        $tplOrgelDetails->replace("KostenTeilstimmung", $oOrgel->getKostenTeilstimmung());
        
        // Pflegevertrag
        $tplOrgelDetails->replace("SelectedPflege" . $oOrgel->getPflegevertrag(), Constant::$HTML_SELECTED_SELECTED);
        foreach (Constant::getPflegevertrag() as $zahl => $text) {
            $tplOrgelDetails->replace("SelectedPflege" . $zahl, "");
        }
        
        // Wartungen
        $cWartungen = WartungUtilities::getOrgelWartungen($oOrgel->getID(), " ORDER BY w_datum DESC");
        $tplWartungen = new BufferedTemplate("orgel_details_wartung_ds.tpl", "CSS", "td1", "td2");
        if ($cWartungen->getSize() > 0) {
            foreach ($cWartungen as $oWartung) {
                $benutzer = new Benutzer($oWartung->getMitarbeiterId1()); // Execute Separate Select
                $tplWartungen->replace("Datum", $oWartung->getDatum(true));
                $tplWartungen->replace("Mitarbeiter", $benutzer->getBenutzername());
                $temp = "";
                if ($oWartung->getTemperatur() != "") {
                    $temp = $oWartung->getTemperatur() . " °C";
                }
                $tplWartungen->replace("Temperatur", $temp);
                $luft = "";
                if ($oWartung->getLuftfeuchtigkeit() != "") {
                    $luft = $oWartung->getLuftfeuchtigkeit() . " %";
                }
                $tplWartungen->replace("Luftfeuchtigkeit", $luft);
                $tplWartungen->replace("Stimmtonhoehe", $oWartung->getStimmtonhoehe());
                if ($oWartung->getStimmung() == 2) {
                    $tplWartungen->replace("Hauptstimmung", "Haupt");
                } else if ($oWartung->getStimmung() == 1) {
                    $tplWartungen->replace("Hauptstimmung", "Neben");
                } else {
                    $tplWartungen->replace("Hauptstimmung", "Keine");
                }
                $tplWartungen->next();
            }
        } else {
            $tplWartungen = new BufferedTemplate("orgel_details_wartung_keine.tpl");
            $tplWartungen->replace("OrgelID", $oOrgel->getID());
            $tplWartungen->next();
        }
        $tplOrgelDetails->replace("Wartungen", $tplWartungen->getOutput());
        if ($oOrgel->getAnzahlManuale() == 0)
            $tplOrgelDetails->replace("CSSSpanHide", "hideContent");
        
        // Wartungsprotokolle
        $cWartungsprotokolle = WartungsprotokollUtilities::getWartungsprotokolle();
        $htmlSelectProtokolle = new HTMLSelectForDSOC($cWartungsprotokolle, "getName", $oOrgel->getWartungsprotokollID());
        $tplOrgelDetails->replace("Wartungsprotokolle", $htmlSelectProtokolle->getOutput());
        
        // Gemeindenamen
        $standardSortierung = ConstantLoader::getGemeindeListeStandardSortierung();
        if ($standardSortierung == "ort") {
            $htmlGemeinden = new HTMLSelectForKey(GemeindeUtilities::getGemeinden(" ORDER BY ad_ort"), "getGemeindeId", "getOrt,getKirche", $oGemeinde->getID());
        } else {
            $htmlGemeinden = new HTMLSelectForKey(GemeindeUtilities::getGemeinden(" ORDER BY g_kirche"), "getGemeindeId", "getKirche,getOrt", $oGemeinde->getID());
        }
        
        $tplOrgelDetails->replace("Gemeinden", $htmlGemeinden->getOutput());
        
        $oldmanual = 0;
        $strContent = "";
        
        $c = RegisterUtilities::ladeOrgelRegister($oOrgel->getID(), " ORDER BY m_id, d_reihenfolge");
        
        if ($c != null && $c->getSize() > 0) {
            // Disposition
            $tplManual = new Template("disposition_liste_manual.tpl");
            $tplRegister = new Template("disposition_liste_ds.tpl");
            
            foreach ($c as $oRegister) {
                $manual = "Pedal";
                if ($oRegister->getManual() != 6) {
                    $manual = $oRegister->getManual() . ". Manual";
                }
                
                if ($oRegister->getManual() != $oldmanual) {
                    $tplManual->replace("Manual", $manual);
                    $strContent .= $tplManual->getOutputAndRestore();
                }
                
                $suffix = "";
                if ($oRegister->getTyp() == 2) {
                    $suffix = " (T)";
                } else if ($oRegister->getTyp() == 3) {
                    $suffix = " (E)";
                } else {
                    $suffix = "";
                }
                
                $tplRegister->replace("Spalte1", $oRegister->getName() . $suffix);
                $tplRegister->replace("Spalte2", $oRegister->getFuss() . "'");
                $tplRegister->replace("Spalte3", $oRegister->getReihenfolge());
                $strContent .= $tplRegister->getOutputAndRestore();
                $oldmanual = $oRegister->getManual();
            }
        } else if ($oOrgel->getAnzahlManuale() > 0) {
            // Manuale vorhanden, aber noch keine Disposition
            $tpl = new Template("orgel_details_keineDisp.tpl");
            $tpl->replace("OID", $oOrgel->getID());
            $strContent = $tpl->forceOutput();
        } else {
            // Noch keine Manuale eingegeben.
            $tpl = new Template("orgel_details_keineDispKeineManuale.tpl");
            $strContent = $tpl->forceOutput();
        }
        $tplOrgelDetails->replace("DispositionContent", $strContent);
        
        // Bild
        $tplOrgelBilder = new BufferedTemplate("orgel_details_orgelbild.tpl");
        $iBildCounter = 0;
        for ($i = 1; $i <= 3; $i ++) {
            $originalBild = ORGELBILD_BILD_PFAD . $oOrgel->getID() . "_" . $i . ".jpg";
            $thumb = ORGELBILD_THUMB_PFAD . $oOrgel->getID() . "_" . $i . ".jpg";
            if (file_exists($originalBild) && file_exists($thumb)) {
                $tplOrgelBilder->replace("PicID", $i);
                $tplOrgelBilder->replace("OID", $oOrgel->getID());
                $tplOrgelBilder->replace("GemeindeNamen", $oGemeinde->getKirche());
                
                $imagesize = getimagesize($thumb);
                $width = $imagesize[1];
                if ($imagesize[0] > $imagesize[1]) {
                    $width = $imagesize[1];
                }
                
                $tplOrgelBilder->replace("Bildname", $oOrgel->getID());
                $tplOrgelBilder->replace("BildBreite", $width);
                $tplOrgelBilder->next();
                $iBildCounter ++;
            }
        }
        
        // Keine Bilder vorhanden
        if ($iBildCounter == 0) {
            $tplOrgelBilder = new Template("orgel_details_orgelbild_keine.tpl");
            $tplOrgelBilder->replace("OID", $oOrgel->getID());
        }
        
        $tplOrgelDetails->replace("OrgelBilder", $tplOrgelBilder->getOutput());
        $tplOrgelDetails->replace("AnzahlOrgelBilder", $iBildCounter);
        return $tplOrgelDetails;
    }
}