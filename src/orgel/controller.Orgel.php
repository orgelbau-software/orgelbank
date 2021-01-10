<?php

class OrgelController
{

    public static function loescheOrgel()
    {
        if (! isset($_GET['oid']) && ! isset($_POST['objektid']))
            return;
        
        if ($_POST && isset($_POST['objektid'])) {
            
            $oOrgel = new Orgel($_POST['objektid']);
            $oOrgel->setAktiv(0);
            $oOrgel->speichern(false);
            
            $htmlStatus = new HTMLRedirect();
            $htmlStatus->setLink("index.php?page=2&do=20");
            $htmlStatus->setNachricht("Orgel erfolgreich gel&ouml;scht.");
            $htmlStatus->setSekunden(ConstantLoader::getDefaultRedirectSecondsTrue());
            
            $htmlStatus->anzeigen();
        } else {
            $o = new Orgel(intval($_GET['oid']));
            
            $tpl = new HTMLSicherheitsAbfrage();
            $tpl->setText("M&ouml;chten Sie die Orge wirklich endg&uuml;ltig l&ouml;schen? ");
            $tpl->setButtonJa("Ja, Orgel l&ouml;schen!");
            $tpl->setButtonNein("Nein, zur&uuml;ck");
            $tpl->setButtonNeinLink("index.php?page=2&do=20");
            $tpl->setFormLink("index.php?page=2&do=27");
            $tpl->setObjektID($o->getID());
            
            $tpl->anzeigen();
        }
    }

    public static function loescheOrgelGemeindeVerbindung()
    {
        if (! isset($_GET['oid'], $_GET['gid']))
            return;
        
        OrgelUtilities::deleteOrgelGemeindeLink($_GET['oid'], $_GET['gid']);
        
        $tplStatus = new Output("./templates/status_zurueck_u_redirect.tpl");
        $tplStatus->replace("<!--Text-->", "Orgelverbindung wurde gel&ouml;scht.");
        $tplStatus->replace("<!--Sekunden-->", 1);
        $tplStatus->replace("<!--Ziel-->", "index.php?page=2&do=21&oid=" . $_GET['oid']);
        
        echo $tplStatus->getOutput();
    }

    public static function neueOrgelAnlegen()
    {
        $tplOrgelDetails = new Template("orgel_details_neu.tpl");
        
        $gid = 0;
        if (isset($_GET['gid'])) {
            $gid = intval($_GET['gid']);
        }
        
        $htmlGemeinden = new HTMLSelect(GemeindeUtilities::getGemeinden(" ORDER BY g_kirche"), "getKirche", $gid);
        $tplOrgelDetails->replace("<!--Gemeinden-->", $htmlGemeinden->getOutput());
        
        $htmlSelectStatus = new HTMLSelectForArray(Constant::getOrgelStatus(), 0);
        $tplOrgelDetails->replace("Orgelstatus", $htmlSelectStatus->getOutput());
        
        $htmlSelectWinlade = new HTMLSelectForArray(Constant::getWindladen(), 0);
        $tplOrgelDetails->replace("Windlade", $htmlSelectWinlade->getOutput());
        
        $htmlSelectTraktur = new HTMLSelectForArray(Constant::getSpieltrakturen(), 0);
        $tplOrgelDetails->replace("Spieltraktur", $htmlSelectTraktur->getOutput());
        
        $htmlSelectKoppel = new HTMLSelectForArray(Constant::getKoppeln(), 0);
        $tplOrgelDetails->replace("Koppel", $htmlSelectKoppel->getOutput());
        
        $htmlSelectRegister = new HTMLSelectForArray(Constant::getRegisterTrakturen(), 0);
        $tplOrgelDetails->replace("Registertraktur", $htmlSelectRegister->getOutput());
        
        $htmlZyklusSelect = new HTMLSelectForArray(Constant::getZyklus(), 0);
        $tplOrgelDetails->replace("ZyklusSelect", $htmlZyklusSelect->getOutput());
        
        $tplOrgelDetails->anzeigen();
    }

    public static function verwalteOrgelBild()
    {
        RequestHandler::handle(new OrgelBildAction());
    }

    public static function deleteOrgelPicture()
    {
        RequestHandler::handle(new OrgelBildAction());
    }

    public static function speicherOrgelDetails()
    {
        if (! isset($_POST['o_id']))
            return;
        
        if ($_POST['o_id'] == 0) {
            $oOrgel = new Orgel(); // Bei neuer Orgel wird 0 uebergeben
        } else {
            $oOrgel = new Orgel($_POST['o_id']); // Orgel per ID laden
        }
        
        $tplStatus = new Output("./templates/status_zurueck_u_redirect.tpl");
        
        // Speichern
        $oOrgel->setAktiv(1);
        $oOrgel->setBaujahr($_POST['baujahr']);
        $oOrgel->setErbauer($_POST['erbauer']);
        $oOrgel->setOstID($_POST['status']);
        $oOrgel->setRenoviert($_POST['renoviert']);
        $oOrgel->setRenovierer($_POST['renovierer']);
        $oOrgel->setWindladeID($_POST['windlade']);
        $oOrgel->setSpieltrakturID($_POST['spieltraktur']);
        $oOrgel->setKoppelID($_POST['koppel']);
        $oOrgel->setRegistertrakturID($_POST['registertraktur']);
        $oOrgel->setStimmung($_POST['stimmung']);
        $oOrgel->setAnmerkung($_POST['anmerkung']);
        $oOrgel->setPflegevertrag($_POST['pflegevertrag']);
        $oOrgel->setKostenHauptstimmung($_POST['kostenhauptstimmung']);
        $oOrgel->setKostenTeilstimmung($_POST['kostenteilstimmung']);
        $oOrgel->setZyklus($_POST['zyklus']);
        $oOrgel->setMassnahmen($_POST['massnahmen']);
        $oOrgel->setGemeindeId($_POST['gemeindeid']);
        
        if (isset($_POST['manual1'])) {
            $oOrgel->setManual1(1);
        } else {
            $oOrgel->setManual1(0);
        }
        if (isset($_POST['manual2'])) {
            $oOrgel->setManual2(1);
        } else {
            $oOrgel->setManual2(0);
        }
        if (isset($_POST['manual3'])) {
            $oOrgel->setManual3(1);
        } else {
            $oOrgel->setManual3(0);
        }
        if (isset($_POST['manual4'])) {
            $oOrgel->setManual4(1);
        } else {
            $oOrgel->setManual4(0);
        }
        if (isset($_POST['pedal'])) {
            $oOrgel->setPedal(1);
        } else {
            $oOrgel->setPedal(0);
        }
        
        $oOrgel->setGroesseM1($_POST['m1groesse']);
        $oOrgel->setGroesseM2($_POST['m2groesse']);
        $oOrgel->setGroesseM3($_POST['m3groesse']);
        $oOrgel->setGroesseM4($_POST['m4groesse']);
        $oOrgel->setGroesseM6($_POST['m6groesse']);
        
        $oOrgel->setWinddruckM1($_POST['m1wd']);
        $oOrgel->setWinddruckM2($_POST['m2wd']);
        $oOrgel->setWinddruckM3($_POST['m3wd']);
        $oOrgel->setWinddruckM4($_POST['m4wd']);
        $oOrgel->setWinddruckM6($_POST['m6wd']);
        $oOrgel->speichern(true);
        
        $tplStatus->replace("<!--Text-->", "Orgeldetails gespeichert.");
        $tplStatus->replace("<!--Sekunden-->", 1);
        $tplStatus->replace("<!--Ziel-->", "index.php?page=2&do=21&oid=" . $oOrgel->getID());
        echo $tplStatus->getOutput();
    }

    public static function zeigeOrgelDetails()
    {
        if (! isset($_GET['oid']))
            return;
        
        $tplOrgelDetails = new Template("orgel_details.tpl");
        $tplSelectOption = new Template("select_option.tpl");
        $oOrgel = new Orgel($_GET['oid']);
        $oGemeinde = new Gemeinde($oOrgel->getGemeindeId());
        $strContent = "";
        
        // Checkboxen f�r die Manuale pr�perieren
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
        
        // Gemeindenamen
        $htmlGemeinden = new HTMLSelect(GemeindeUtilities::getGemeinden(" ORDER BY g_kirche"), "getKirche", $oGemeinde->getID());
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
                
                $tplRegister->replace("Spalte1", $oRegister->getName());
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
            if (file_exists("store/orgelpics/" . $oOrgel->getID() . "_" . $i . ".jpg")) {
                $tplOrgelBilder->replace("PicID", $i);
                $tplOrgelBilder->replace("OID", $oOrgel->getID());
                $tplOrgelBilder->replace("GemeindeNamen", $oGemeinde->getKirche());
                
                $imagesize = getimagesize("store/orgelpics/thumbs/" . $oOrgel->getID() . "_" . $i . ".jpg");
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
        $tplOrgelDetails->anzeigen();
    }

    /**
     * Zeigt die Orgel Druckansicht
     */
    public static function zeigeOrgelDruckansicht()
    {
        $tplOrgelDruck = new Template("orgel_liste_druck.tpl");
        $tplOrgelDruckDs = new BufferedTemplate("orgel_liste_druck_ds.tpl");
        $strOrgelOutput = "";
        $i = 0;
        $strSQLOrderBy = "";
        
        if (! isset($_GET['order']) || $_GET['order'] == "erbauer") {
            $strSQLOrderBy = "o_erbauer";
        } elseif ($_GET['order'] == "baujahr") {
            $strSQLOrderBy = "o_baujahr";
        } elseif ($_GET['order'] == "wartung") {
            $strSQLOrderBy = "o_letztepflege";
        } elseif ($_GET['order'] == "manual") {
            // !!!
        } elseif ($_GET['order'] == "register") {
            $strSQLOrderBy = "o_anzahlregister";
        } elseif ($_GET['order'] == "gemeinde") {
            $strSQLOrderBy = "g_kirche";
        } elseif ($_GET['order'] == "plz") {
            $strSQLOrderBy = "ad_plz";
        } elseif ($_GET['order'] == "ort") {
            $strSQLOrderBy = "ad_ort";
        } elseif ($_GET['order'] == "bezirk") {
            $strSQLOrderBy = "b_id";
        } elseif ($_GET['order'] == "pflegevertrag") {
            $strSQLOrderBy = "o_pflegevertrag";
        } elseif ($_GET['order'] == "zyklus") {
            $strSQLOrderBy = "o_zyklus";
        }
        
        // Sortierueberschriften ausgeben
        if (! isset($_GET['dir']) || $_GET['dir'] == "asc") {
            $strSQLDir = "ASC";
            $strTPLDir = "desc";
        } else {
            $strSQLDir = "DESC";
            $strTPLDir = "asc";
        }
        
        $tplOrgelDruck->replace("Dir", $strTPLDir);
        $tplOrgelDruck->replace("OrgelAnzahl", OrgelUtilities::getAnzahlOrgeln());
        $tplOrgelDruck->replace("Datum", date("d.m.Y, H:i") . " Uhr");
        
        $c = OrgelUtilities::getDruckAnsichtOrgeln("ORDER BY " . $strSQLOrderBy . " " . $strSQLDir);
        
        // Ausgabe der Datens�tze
        foreach ($c as $oOrgel) {
            
            // Manuale aus der Datenbank lesen
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
            
            // Werte ins Template einf�gen
            $tplOrgelDruckDs->replace("Lfnr", ++ $i);
            $tplOrgelDruckDs->replace("OID", $oOrgel->getOrgelID());
            $tplOrgelDruckDs->replace("GID", $oOrgel->getGemeindeID());
            if ($oOrgel->getGemeindeNamen() == "")
                $tplOrgelDruckDs->replace("Gemeinde", "&nbsp;");
            $tplOrgelDruckDs->replace("Gemeinde", $oOrgel->getGemeindeNamen());
            
            if ($oOrgel->getErbauer() == "")
                $tplOrgelDruckDs->replace("Erbauer", "&nbsp;");
            $tplOrgelDruckDs->replace("Erbauer", $oOrgel->getErbauer());
            
            if ($oOrgel->getBaujahr() == "")
                $tplOrgelDruckDs->replace("Baujahr", "&nbsp;");
            $tplOrgelDruckDs->replace("Baujahr", $oOrgel->getBaujahr());
            
            if ($oOrgel->getLetztePflege() == "")
                $tplOrgelDruckDs->replace("LetztePflege", "&nbsp;");
            $tplOrgelDruckDs->replace("LetztePflege", $oOrgel->getLetztePflege(true));
            
            if (trim($oOrgel->getPflegevertrag()) == "")
                $tplOrgelDruckDs->replace("Pflegevertrag", "&nbsp;");
            $tplOrgelDruckDs->replace("Pflegevertrag", ($oOrgel->getPflegevertrag() == "1" ? "Ja" : "Nein"));
            
            if (trim($oOrgel->getZyklus()) == "")
                $tplOrgelDruckDs->replace("Zyklus", "&nbsp;");
            $tplOrgelDruckDs->replace("Zyklus", $oOrgel->getZyklus());
            
            $tplOrgelDruckDs->replace("Manuale", $manual);
            $tplOrgelDruckDs->replace("Register", $oOrgel->getRegisterAnzahl());
            if ($oOrgel->getGemeindePLZ() == "")
                $tplOrgelDruckDs->replace("PLZ", "&nbsp;");
            $tplOrgelDruckDs->replace("PLZ", $oOrgel->getGemeindePLZ());
            
            if ($oOrgel->getGemeindeOrt() == "")
                $tplOrgelDruckDs->replace("Ort", "&nbsp;");
            $tplOrgelDruckDs->replace("Ort", $oOrgel->getGemeindeOrt());
            
            if ($oOrgel->getGemeindeBezirk() == "")
                $tplOrgelDruckDs->replace("Bezirk", "&nbsp;");
            $tplOrgelDruckDs->replace("Bezirk", $oOrgel->getGemeindeBezirk());
            
            if (trim($oOrgel->getFunktion()) == "")
                $tplOrgelDruckDs->replace("Funktion", "&nbsp;");
            $tplOrgelDruckDs->replace("Funktion", $oOrgel->getFunktion());
            
            if (trim($oOrgel->getNachname()) == "")
                $tplOrgelDruckDs->replace("Nachname", "---");
            $tplOrgelDruckDs->replace("Nachname", $oOrgel->getNachname());
            
            if (trim($oOrgel->getVorname()) == "")
                $tplOrgelDruckDs->replace("Vorname", "---");
            $tplOrgelDruckDs->replace("Vorname", $oOrgel->getVorname());
            
            if (trim($oOrgel->getTelefon()) == "")
                $tplOrgelDruckDs->replace("Telefon", "&nbsp;");
            $tplOrgelDruckDs->replace("Telefon", $oOrgel->getTelefon());
            
            $tplOrgelDruckDs->next();
        }
        
        // Orgeldatens�tze ins Template einf�gen
        $tplOrgelDruck->replace("Content", $tplOrgelDruckDs->getOutput());
        
        // Template ausgeben
        $tplOrgelDruck->anzeigen();
    }

    public static function zeigeOrgelListe()
    {
        $tplOrgeldetails = new Template("orgel_liste.tpl");
        $tplOrgellisterubrik = new Template("orgel_liste_rubrik_first.tpl");
        $tplOrgellisteDs = new BufferedTemplate("orgel_liste_ds.tpl", "Farbwechsel", "td1", "td2");
        $oldindex = - 1;
        $strChecked1 = "";
        $strChecked2 = "";
        $strChecked3 = "";
        $strChecked4 = "";
        $boFirst = true;
        
        $xForQuickJump = OrgelUtilities::getOrgelListe();
        
        // Bei wenig Kunden immer den Gesamtbestand anzeigen
        if (! isset($_GET['index']) && $xForQuickJump->getSize() < ConstantLoader::getMindestAnzahlOrgelnFuerGruppierung()) {
            $_GET['index'] = "all";
        }
        
        $handler = new OrgelRequestHandler();
        $handledRequest = $handler->prepareOrgelListe();
        
        if (isset($_SESSION['suchbegriff']['ost_id-1']) && $_SESSION['suchbegriff']['ost_id-1'] != "") {
            $strChecked1 = Constant::$HTML_CHECKED_CHECKED;
        }
        if (isset($_SESSION['suchbegriff']['ost_id-2']) && $_SESSION['suchbegriff']['ost_id-2'] != "") {
            $strChecked2 = Constant::$HTML_CHECKED_CHECKED;
        }
        if (isset($_SESSION['suchbegriff']['ost_id-3']) && $_SESSION['suchbegriff']['ost_id-3'] != "") {
            $strChecked3 = Constant::$HTML_CHECKED_CHECKED;
        }
        if (isset($_SESSION['suchbegriff']['nichtzugeordnet']) && $_SESSION['suchbegriff']['nichtzugeordnet'] != "") {
            $strChecked4 = Constant::$HTML_CHECKED_CHECKED;
        }
        
        $tplOrgeldetails->replace("SessionID", session_id());
        $tplOrgeldetails->replace("checked1", $strChecked1);
        $tplOrgeldetails->replace("checked2", $strChecked2);
        $tplOrgeldetails->replace("checked3", $strChecked3);
        $tplOrgeldetails->replace("checked4", $strChecked4);
        
        $tplOrgeldetails->replace("Dir", $handledRequest['TPLDIR']);
        $tplOrgeldetails->replace("Order", $handledRequest['TPLORDER']);
        $tplOrgeldetails->replace("Index", $handledRequest['INDEX']);
        $c = OrgelUtilities::getOrgelListe($handledRequest['SQLADD']);
        
        $tplOrgeldetails->replace("OrgelAnzahlAnzeige", $c->getSize());
        $tplOrgeldetails->replace("OrgelAnzahlGesamt", OrgelUtilities::getAnzahlOrgeln());
        
        foreach ($c as $oOrgel) {
            // Neue Rubrik einfuegen, wenn neuer Anfangsbuchstabe/Zeichen
            if (isset($_GET['order']) && $_GET['order'] == "bezirk") {
                $newindex = $oOrgel->getGemeindeBezirk();
            } elseif (isset($_GET['order']) && $_GET['order'] == "konfession") {
                $newindex = $oOrgel->getKID();
            } elseif (isset($_GET['order']) && $_GET['order'] == "baujahr") {
                $newindex = $oOrgel->getBaujahr();
            } elseif (isset($_GET['order']) && $_GET['order'] == "wartung") {
                if (strlen($oOrgel->getLetztePflege()) == 10) {
                    $newindex = substr($oOrgel->getLetztePflege(), 6, 4);
                } else {
                    $newindex = $oOrgel->getLetztePflege();
                }
            } elseif (isset($_GET['order']) && $_GET['order'] == "erbauer") {
                $newindex = substr($oOrgel->getErbauer(), 0, 1);
            } elseif (isset($_GET['order']) && $_GET['order'] == "plz") {
                $newindex = substr($oOrgel->getGemeindePLZ(), 0, 1);
            } elseif (isset($_GET['order']) && $_GET['order'] == "ort") {
                $newindex = substr($oOrgel->getGemeindeOrt(), 0, 1);
            } else {
                $newindex = substr($oOrgel->getGemeindeNamen(), 0, 1);
            }
            
            if ($newindex != $oldindex) {
                $tplOrgellisterubrik->replace("Rubrik", $newindex);
                $tplOrgellisterubrik->replace("Dir", $handledRequest['TPLDIR']);
                $tplOrgellisterubrik->replace("Index", $handledRequest['INDEX']);
                $tplOrgellisteDs->addToBuffer($tplOrgellisterubrik);
                if ($boFirst) {
                    $boFirst = false;
                    $tplOrgellisterubrik = new Template("orgel_liste_rubrik.tpl");
                }
                $tplOrgellisterubrik->restoreTemplate();
            }
            
            // Manuale aus der Datenbank lesen
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
            
            // Werte ins Template einf�gen
            $tplOrgellisteDs->replace("OID", $oOrgel->getOrgelID());
            $tplOrgellisteDs->replace("GID", $oOrgel->getGemeindeID());
            $tplOrgellisteDs->replace("Gemeinde", $oOrgel->getGemeindeNamen());
            $tplOrgellisteDs->replace("Erbauer", $oOrgel->getErbauer());
            $tplOrgellisteDs->replace("Baujahr", $oOrgel->getBaujahr());
            $tplOrgellisteDs->replace("LetztePflege", $oOrgel->getLetztePflege(true));
            $tplOrgellisteDs->replace("Manuale", $manual);
            $tplOrgellisteDs->replace("Register", $oOrgel->getRegisterAnzahl());
            $tplOrgellisteDs->replace("PLZ", $oOrgel->getGemeindePLZ());
            $tplOrgellisteDs->replace("Ort", $oOrgel->getGemeindeOrt());
            $tplOrgellisteDs->replace("Bezirk", $oOrgel->getGemeindeBezirk());
            $tplOrgellisteDs->replace("Rubrik", $newindex);
            $tplOrgellisteDs->next();
            
            // Alten Index speichern
            $oldindex = $newindex;
        }
        
        // Orgeldatens�tze ins Template einf�gen
        $tplOrgeldetails->replace("Content", $tplOrgellisteDs->getOutput());
        
        // Quickjump einf�gen
        $q = new Quickjump($xForQuickJump, $handledRequest['GETTER'], "index.php?page=2&do=20&order=" . $handledRequest['TPLORDER'] . "&dir=asc&index=<!--Index-->", $handledRequest['SKALA']);
        
        $tplOrgeldetails->replace("Quickjump", $q->getOutput());
        
        // Orgelliste ausgeben
        $tplOrgeldetails->anzeigen();
    }

    public static function zeigeWartungsListe()
    {
        if (! isset($_GET['oid']) && ! isset($_POST['oid']))
            throw new InvalidArgumentException("OrgelID (OID) nicht �bergeben.");
        
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
                        $tplInnerStatus = new HTMLStatus("Die Orgel mit der ID " . $_POST['goto'] . "existiert nicht. Bitte geben Sie eine g�ltige OrgelID ein.", 1);
                        $tplStatus = new HTMLRedirect($tplInnerStatus, $redirectURL . $_POST['orgelId'], 3);
                    } else {
                        $tplStatus = new HTMLRedirect("Sie werden weitergeleitet", $redirectURL . $oOrgel->getID());
                    }
                    $tplStatus->anzeigen();
                    
                    // haesslich aber so gehts...
                    return;
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
                $oWartung->setMitarbeiterIstStd1($_POST['ma1_stunden_ist']);
                $oWartung->setMitarbeiterIstStd2($_POST['ma2_stunden_ist']);
                $oWartung->setMitarbeiterIstStd3($_POST['ma3_stunden_ist']);
                $oWartung->setMitarbeiterFaktStd1($_POST['ma1_stunden_fakt']);
                $oWartung->setMitarbeiterFaktStd2($_POST['ma2_stunden_fakt']);
                $oWartung->setMitarbeiterFaktStd3($_POST['ma3_stunden_fakt']);
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
            if($oWartung != null) {
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
                throw new Exception("Keine OrgelID �bergeben!");
            }
        }
        
        $tplWartungDS = new BufferedTemplate("orgel_wartung_details_ds.tpl", "CSS", "td1", "td2");
        
        $tplWartung->replace("OrgelId", $oWartung->getOrgelId());
        $tplWartung->replace("WartungId", $oWartung->getID());
        
        // Wartungsdatensaetze
        $col = WartungUtilities::getOrgelWartungen($oWartung->getOrgelId(), "ORDER BY w_datum DESC");
        if ($col->getSize() > 0) {
            foreach ($col as $wartung) {
                $benutzer = new Benutzer($wartung->getMitarbeiterId1());
                $tplWartungDS->replace("WartungId", $wartung->getID());
                $tplWartungDS->replace("OrgelId", $wartung->getOrgelId());
                $tplWartungDS->replace("Datum", $wartung->getDatum(true));
                $tplWartungDS->replace("Mitarbeiter", $benutzer->getBenutzername());
                $tplWartungDS->replace("Bemerkung", $wartung->getBemerkung());
                $tplWartungDS->replace("Temperatur", ($wartung->getTemperatur() != "" ? $wartung->getTemperatur() . " �C" : ""));
                $tplWartungDS->replace("Stimmtonhoehe", ($wartung->getStimmtonHoehe() != "" ? $wartung->getStimmtonHoehe() . " HZ" : ""));
                $tplWartungDS->replace("Luftfeuchtigkeit", ($wartung->getLuftfeuchtigkeit() != "" ? $wartung->getLuftfeuchtigkeit() . " %" : ""));
                if ($wartung->getStimmung() == 0) {
                    $tplWartungDS->replace("Stimmung", "Keine");
                } elseif ($wartung->getStimmung() == 1) {
                    $tplWartungDS->replace("Stimmung", "Nebenstimmung");
                } elseif ($wartung->getStimmung() == 2) {
                    $tplWartungDS->replace("Stimmung", "Hauptstimmung");
                }
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
        }
        $tplWartung->replace("AbrVertrag", "");
        $tplWartung->replace("AbrAufwand", "");
        
        if ($oWartung->getStimmung() == 2) {
            $tplWartung->replace("Hauptstimmung", Constant::$HTML_SELECTED_SELECTED);
        } elseif ($oWartung->getStimmung() == 1) {
            $tplWartung->replace("Nebenstimmung", Constant::$HTML_SELECTED_SELECTED);
        } elseif ($oWartung->getStimmung() == 1) {
            $tplWartung->replace("NichtDurchgefuehrt", Constant::$HTML_SELECTED_SELECTED);
        }
        $tplWartung->replace("Hauptstimmung", "");
        $tplWartung->replace("Nebenstimmung", "");
        $tplWartung->replace("NichtDurchgefuehrt", "");
        
        $tplWartung->replace("Material", $oWartung->getMaterial());
        $tplWartung->replace("Ma1IstStd", $oWartung->getMitarbeiterIstStd1());
        $tplWartung->replace("Ma2IstStd", $oWartung->getMitarbeiterIstStd2());
        $tplWartung->replace("Ma3IstStd", $oWartung->getMitarbeiterIstStd3());
        $tplWartung->replace("Ma1FaktStd", $oWartung->getMitarbeiterFaktStd1());
        $tplWartung->replace("Ma2FaktStd", $oWartung->getMitarbeiterFaktStd2());
        $tplWartung->replace("Ma3FaktStd", $oWartung->getMitarbeiterFaktStd3());
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
        $tplWartung->anzeigen();
    }

    public static function zeigeOffeneWartungen()
    {
        $tpl = new Template("orgel_liste_wartungen.tpl");
        $tplDS = new BufferedTemplate("orgel_liste_wartungen_ds.tpl", "Farbwechsel", "td1", "td2");
        $tplRubrik = new Template("orgel_liste_wartungen_rubrik.tpl");
        $tplRubrikEnde = new Template("orgel_liste_wartungen_rubrik_ende.tpl");
        
        $handler = new OrgelOffeneWartungenRequestHandler();
        $handler = $handler->handleRequest();
        
        $tpl->replace("Zyklus" . $handler['zyklus'], Constant::$HTML_SELECTED_SELECTED);
        foreach (Constant::getZyklus() as $zahl => $text) {
            $tpl->replace("Zyklus" . $zahl, "");
        }
        
        $tpl->replace("hideunknown", ($handler['hideunknown'] ? Constant::$HTML_CHECKED_CHECKED : ""));
        
        $cOrgelListe = OrgelUtilities::getOrgelListeEingeplanteWartungen();
        $tmpJahr = 0;
        
        // Aktuell geplante aber nicht eingetragene Wartungen
        if($cOrgelListe->getSize() > 0) {
            $tplRubrik->replace("Rubrik", "!");
            $tplDS->addToBuffer($tplRubrik);
            $tplRubrik->restoreTemplate();
            foreach ($cOrgelListe as $orgel) {
                $tplDS->replace("Rubrik", ($tmpJahr == "1970" ? "Keine" : $tmpJahr));
                $tplDS->replace("OID", $orgel->getOrgelId());
                $tplDS->replace("LetztePflege", $orgel->getLetztePflege(true));
                $tplDS->replace("OID", $orgel->getOrgelId());
                $tplDS->replace("GID", $orgel->getGemeindeID());
                $tplDS->replace("Gemeinde", $orgel->getGemeindeNamen());
                $tplDS->replace("LetztePflege", $orgel->getLetztePflege(true));
                $tplDS->replace("Register", $orgel->getRegisterAnzahl());
                $tplDS->replace("PLZ", $orgel->getGemeindePLZ());
                $tplDS->replace("Ort", $orgel->getGemeindeOrt());
                $tplDS->replace("Bezirk", $orgel->getGemeindeBezirk());
                $tplDS->replace("Zyklus", $orgel->getZyklus());
                // Missbrauch des Baujahrs Feld um die WartungsId zu uebertragen
                $tplDS->replace("NaechstePflege", "<a href=\"index.php?page=2&do=28&oid=134&action=edit&wid=".$orgel->getBaujahr()."\">Zur Wartung</a>");
                $tplDS->replace("AnzahlRegister", $orgel->getRegisterAnzahl());
                $tplDS->next();
            }
        }
        
        $cOrgelListe = OrgelUtilities::getOrgelListeAnstehendeWartungen($handler['SQLADD']);
        $tmpJahr = 0;
        
        $tpl->replace("AnzahlWartungen", $cOrgelListe->getSize());
        foreach ($cOrgelListe as $orgel) {
            $naechstePflege = strtotime($orgel->getNaechstePflege());
            
            $dateNaechstePflege = date('Y', $naechstePflege);
            if ($tmpJahr != $dateNaechstePflege) {
                if ($tmpJahr != 0) {
                    $tplDS->addToBuffer($tplRubrikEnde);
                }
                $tpl->replace("Rubrik", ($tmpJahr == "1970" ? "Keine" : $tmpJahr));
                $tmpJahr = date("Y", $naechstePflege);
                
                $tplRubrik->replace("Rubrik", ($tmpJahr == "1970" ? "Keine" : $tmpJahr));
                $tplDS->addToBuffer($tplRubrik);
                $tplRubrik->restoreTemplate();
            }
            
            $tpl->replace("Rubrik", ($tmpJahr == "1970" ? "Keine" : $tmpJahr));
            
            $naechstePflege = date("d.m.Y", $naechstePflege);
            if ($tmpJahr < 1990)
                $naechstePflege = "unbekannt";
            
            $tplDS->replace("Rubrik", ($tmpJahr == "1970" ? "Keine" : $tmpJahr));
            $tplDS->replace("OID", $orgel->getOrgelId());
            $tplDS->replace("LetztePflege", $orgel->getLetztePflege(true));
            $tplDS->replace("OID", $orgel->getOrgelId());
            $tplDS->replace("GID", $orgel->getGemeindeID());
            $tplDS->replace("Gemeinde", $orgel->getGemeindeNamen());
            $tplDS->replace("LetztePflege", $orgel->getLetztePflege(true));
            $tplDS->replace("Register", $orgel->getRegisterAnzahl());
            $tplDS->replace("PLZ", $orgel->getGemeindePLZ());
            $tplDS->replace("Ort", $orgel->getGemeindeOrt());
            $tplDS->replace("Bezirk", $orgel->getGemeindeBezirk());
            $tplDS->replace("Zyklus", $orgel->getZyklus());
            $tplDS->replace("NaechstePflege", $naechstePflege);
            $tplDS->replace("AnzahlRegister", $orgel->getRegisterAnzahl());
            $tplDS->next();
            
            $tmpJahr = $dateNaechstePflege;
        }
        
        $tpl->replace("Content", $tplDS->getOutput());
        $tpl->anzeigen();
    }

    public static function exportOrgelListeExcel()
    {
        $requestHandler = new OrgelRequestHandler();
        $handledRequest = $requestHandler->prepareOrgelListe();
        // error_reporting ( null ); // gibt sonst h�ssliche Fehler im Code
        
        $workbook = new OrgelbankExcelWriter();
        $workbook->setTempDir(TMPDIR);
        $worksheet = $workbook->addWorksheet("Orgelliste");
        
        $frmFett = "bold";
        
        $cOrgeln = OrgelUtilities::getDruckAnsichtOrgeln();
        Log::debug("orgel anzahl=" . $cOrgeln->getSize());
        if ($cOrgeln->getSize() == 0) {
            $tpl = new HTMLFehlerseite("Ihre Auswahl enth&auml;lt keine Orgeln");
            $tpl->anzeigen();
            return;
        }
        
        $worksheet->write("A1", "Nr.", $frmFett);
        $worksheet->write("B1", "Gemeinde", $frmFett);
        $worksheet->write("C1", "Erbauer", $frmFett);
        $worksheet->write("D1", "Baujahr", $frmFett);
        $worksheet->write("E1", "Letzte Pflege", $frmFett);
        $worksheet->write("F1", "Manuale", $frmFett);
        $worksheet->write("G1", "Register", $frmFett);
        $worksheet->write("H1", "PLZ", $frmFett);
        $worksheet->write("I1", "Ort", $frmFett);
        $worksheet->write("J1", "Bezirk", $frmFett);
        $worksheet->write("K1", "Funktion", $frmFett);
        $worksheet->write("L1", "Name", $frmFett);
        $worksheet->write("M1", "Telefon", $frmFett);
        $worksheet->write("N1", "KostenHS", $frmFett);
        $worksheet->write("O1", "KostenTS", $frmFett);
        
        $iZeile = 2;
        if ($cOrgeln != null) {
            foreach ($cOrgeln as $orgel) {
                $name = $orgel->getNachname();
                if ($orgel->getVorname() != "") {
                    $name .= ", " . $orgel->getVorname();
                }
                $worksheet->write("A" . $iZeile, $iZeile);
                $worksheet->write("B" . $iZeile, $orgel->getGemeindeNamen());
                $worksheet->write("C" . $iZeile, $orgel->getErbauer());
                $worksheet->write("D" . $iZeile, $orgel->getBaujahr());
                $worksheet->write("E" . $iZeile, $orgel->getLetztePflege(true));
                $worksheet->write("F" . $iZeile, $orgel->getManual1());
                $worksheet->write("G" . $iZeile, $orgel->getRegisterAnzahl());
                $worksheet->write("H" . $iZeile, $orgel->getGemeindePLZ());
                $worksheet->write("I" . $iZeile, $orgel->getGemeindeOrt());
                $worksheet->write("J" . $iZeile, $orgel->getGemeindeBezirk());
                $worksheet->write("K" . $iZeile, $orgel->getFunktion());
                $worksheet->write("L" . $iZeile, $name);
                $worksheet->write("M" . $iZeile, $orgel->getTelefon());
                $worksheet->write("N" . $iZeile, $orgel->getKostenHauptstimmung());
                $worksheet->write("O" . $iZeile, $orgel->getKostenTeilstimmung());
                $iZeile += 1;
            }
        }
        
        $workbook->download("GemeindeList-" . date("Ymd_Hi") . ".xls");
        $workbook->close();
    }
    
    public static function insertOrgelWartung()
    {
        RequestHandler::handle(new OrgelWartungAction());
    }
}
?>
