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
        
        $standardSortierung = ConstantLoader::getGemeindeListeStandardSortierung();
        if ($standardSortierung == "ort") {
            $htmlGemeinden = new HTMLSelectForKey(GemeindeUtilities::getGemeinden(" ORDER BY ad_ort"), "getGemeindeId", "getOrt,getKirche", 0);
        } else {
            $htmlGemeinden = new HTMLSelectForKey(GemeindeUtilities::getGemeinden(" ORDER BY g_kirche"), "getGemeindeId", "getKirche,getOrt", 0);
        }
        $htmlGemeinden->setValueMaxLength(56);
        $tplOrgelDetails->replace("Gemeinden", $htmlGemeinden->getOutput());
        
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
        
        // Kosten Haupt und Teilstimmung
        $tplOrgelDetails->replace("KostenHauptstimmung", "");
        $tplOrgelDetails->replace("KostenTeilstimmung", "");
        $tplOrgelDetails->replace("Stimmton", "");
        
        $htmlIntervalHauptstimmung = new HTMLSelectForArray(Constant::getIntervallHauptstimmung());
        $tplOrgelDetails->replace("IntervallHaupstimmungSelect", $htmlIntervalHauptstimmung->getOutput());
        
        // Pflegevertrag
        foreach (Constant::getPflegevertrag() as $zahl => $text) {
            $tplOrgelDetails->replace("SelectedPflege" . $zahl, "");
        }
        
        $tplOrgelDetails->replace("StimmungNach", "");
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
        RequestHandler::handle(new OrgelDetailsAction());
    }

    public static function zeigeOrgelDetails()
    {
        RequestHandler::handle(new OrgelDetailsAction());
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
        RequestHandler::handle(new OrgelListeAction());
    }

    public static function zeigeWartungsListe()
    {
        RequestHandler::handle(new WartungsListeAction());
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
        if ($cOrgelListe->getSize() > 0) {
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
                $tplDS->replace("NaechstePflege", "<a href=\"index.php?page=2&do=28&oid=134&action=edit&wid=" . $orgel->getBaujahr() . "\">Zur Wartung</a>");
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
        
        $workbook = new OrgelbankPHPSpreadsheetWriter();
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
        
        // Temporaer
        $worksheet->write("P1", "GemeindeID", $frmFett);
        $worksheet->write("Q1", "OrgelID", $frmFett);
        $worksheet->write("R1", "AnsprechpartnerId", $frmFett);
        
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
                
                $worksheet->write("P" . $iZeile, $orgel->getOrgelId());
                $worksheet->write("Q" . $iZeile, $orgel->getGemeindeId());
                $worksheet->write("R" . $iZeile, $orgel->getAnsprechpartnerId());
                
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

    public static function zeigeWartungsprotokolle()
    {
        RequestHandler::handle(new WartungsprotokolleAction());
    }
}
?>
