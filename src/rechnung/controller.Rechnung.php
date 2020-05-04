<?php

class RechnungController
{

    public static function druckeEndrechnung()
    {
        if (! isset($_POST['gemeindeid']))
            return;
        
        $a = array();
        
        foreach ($_POST as $key => $val) {
            $_POST[$key] = addslashes(trim($val));
            if ($key == $val) {
                $a[] = $val;
            }
        }
        
        $oRechnung = new EndRechnung();
        
        $oRechnung->setGemeindeID($_POST['gemeindeid']);
        $oRechnung->setDatum($_POST['datum']);
        $oRechnung->setZieldatum($_POST['zahlungsziel']);
        $oRechnung->setNummer($_POST['rechnungsnummer']);
        $oRechnung->setTitel($_POST['titel']);
        $oRechnung->setText($_POST['text']);
        $oRechnung->setNettoBetrag($_POST['rnetto'], true);
        $oRechnung->setGesamtNetto($_POST['gnetto'], true);
        
        $oRechnung->speichern(true);
        Log::debug("RechnungID = " . $oRechnung->getID());
        
        foreach ($a as $aID) {
            AbschlagrechnungUtilities::updateAbschlagsRechnungWithEndRechnungId($aID, $oRechnung->getID());
        }
        
        // Template öffnen
        $tplRechnung = new EndRechnungOutput("resources/vorlagen/rechnung_end", $oRechnung);
        $tplRechnung->erstellen();
        
        $tplRechnung->speichern($oRechnung->getSpeicherOrt());
        
        // Zur Rechnung weiterleiten
        $htmlStatus = new HTMLRedirect("", $oRechnung->getSpeicherOrt(), 1);
        $htmlStatus->anzeigen();
    }

    public static function neueEndrechnung()
    {
        $tplRechnung = new Template("rechnung_endrechnung.tpl");
        
        $t = RechnungUtilities::baueRechnungsAuswahlKopf();
        $t->plainReplace("doAdresse()", "doAbschlag()");
        
        $tplRechnung->replace("RechnungsKopf", $t->getOutput());
        $tplRechnung->replace("disableForm", "");
        $tplRechnung->replace("SubmitValue", "Rechnung erstellen");
        $tplRechnung->replace("KopfzeilenZusatz", "");
        
        $k = new Template("rechnung_end_ajax_kopf.tpl");
        $t = new Template("rechnung_end_ajax_ds.tpl");
        $f = new Template("rechnung_end_ajax_keine.tpl");
        $s = "";
        
        if (isset($_GET['gid'])) {
            $g = new Gemeinde($_GET['gid']);
            $r = AbschlagrechnungUtilities::getGemeindeAbschlagsRechnungen($g->getID(), " ORDER BY ra_datum ASC");
            $s = $k->forceOutput();
            if ($r->getLength() >= 0) {
                foreach ($r as $oAbschlagsRechnung) {
                    $t->replace("ABezeichnung", $oAbschlagsRechnung->getANr() . ". Abschlag");
                    $t->replace("ANr", $oAbschlagsRechnung->getID());
                    $t->replace("NettoGesamt", $oAbschlagsRechnung->getGesamtNetto(true));
                    $t->replace("BruttoGesamt", $oAbschlagsRechnung->getGesamtBrutto(true));
                    $t->replace("NettoBetrag", $oAbschlagsRechnung->getNettoBetrag(true));
                    $t->replace("BruttoBetrag", $oAbschlagsRechnung->getBruttoBetrag(true));
                    $t->replace("NettoBetragUnformatted", $oAbschlagsRechnung->getNettoBetrag());
                    $t->replace("NettoGesamtUnformatted", $oAbschlagsRechnung->getBruttoBetrag());
                    $t->replace("ADatum", $oAbschlagsRechnung->getDatum(true));
                    $t->replace("disabled", "");
                    $t->replace("checked", "");
                    $s .= $t->getOutputAndRestore();
                }
            }
        } else {
            $s .= $k->forceOutput();
            $s .= $f->forceOutput();
        }
        
        $tplRechnung->replace("Rechnungen", $s);
        $tplRechnung->replace("Einleitung", "");
        $tplRechnung->replace("NettoBetrag", "");
        $tplRechnung->replace("MwSt", "");
        $tplRechnung->replace("GesamtMwSt", "");
        $tplRechnung->replace("GesamtNetto", "");
        $tplRechnung->replace("GesamtBrutto", "");
        $tplRechnung->replace("AbschlaegeBisher", "");
        $tplRechnung->replace("BruttoBetrag", "");
        
        // Rechnungsdatum & Zahlungsziel
        $tplRechnung->replace("Rechnungsdatum", date("d.m.Y"));
        $tplRechnung->replace("Zahlungsziel", date("d.m.Y", strtotime("+" . ConstantLoader::getStandardZahlungsziel() . " day")));
        
        // Rechnungsnummer
        $tplRechnung->replace("Rechnungsjahr", date("y"));
        
        $tplRechnung->replace("Rechnungsnummer", ConstantLoader::getAbschlagRechnungsNummerNaechste());
        
        // Rechnung ausgeben
        $tplRechnung->anzeigen();
    }

    public static function druckeAbschlagsrechnung()
    {
        if (! isset($_POST['gemeindeid']))
            throw new Exception("GemeindeID nicht uebergeben");
        
        foreach ($_POST as $key => $val) {
            $_POST[$key] = addslashes(trim($val));
        }
        
        if (isset($_POST['satz']) && $_POST['satz'] != "" && $_POST['satz'] != 0) {
            $nettoBetrag = round($_POST['gnetto'] * $_POST['satz'] / 100, 2);
        } else {
            $nettoBetrag = round($_POST['anetto'], 2);
        }
        
        $oRechnung = new AbschlagsRechnung();
        
        $oRechnung->setGemeindeID($_POST['gemeindeid']);
        $oRechnung->setANr($_POST['anr']);
        $oRechnung->setDatum($_POST['datum']);
        $oRechnung->setZieldatum($_POST['zahlungsziel']);
        $oRechnung->setNummer($_POST['rechnungsnummer']);
        $oRechnung->setTitel($_POST['titel']);
        $oRechnung->setEinleitung($_POST['einleitung']);
        $oRechnung->setNettoBetrag($nettoBetrag, true);
        $oRechnung->setGesamtNetto($_POST['gnetto'], true);
        $oRechnung->setAbschlagSatz($_POST['satz']);
        
        $oRechnung->speichern(true);
        
        // Update der Rechnungsnummer
        $cs = new ConstantSetter();
        $rechnungsNummerOhneJahr = substr($_POST['rechnungsnummer'], 0, strpos($_POST['rechnungsnummer'], "/"));
        $cs->setAbschlagRechnungsNummerNaechste($rechnungsNummerOhneJahr + 1);
        
        // Template öffnen
        $tplRechnung = new AbschlagRechnungOutput("resources/vorlagen/rechnung_abschlag", $oRechnung);
        $tplRechnung->erstellen();
        
        $tplRechnung->speichern($oRechnung->getSpeicherOrt());
        
        // Zur Rechnung weiterleiten
        $tplStatus = new HTMLRedirect("Die Rechnung wurde erstellt.", $oRechnung->getSpeicherOrt());
        $tplStatus->anzeigen();
    }

    public static function neueAbschlagsrechnung()
    {
        $tplRechnung = new Template("rechnung_abschlag.tpl");
        $tplRechnung->replace("RechnungsKopf", RechnungUtilities::baueRechnungsAuswahlKopf()->getOutput());
        $tplRechnung->plainReplace("doAdresse()", "ajaxLoadAbschlagsrechnungenForNewAbschlag()");
        
        $tplRechnung->replace("disableForm", "");
        $tplRechnung->replace("SubmitValue", "Rechnung erstellen");
        $tplRechnung->replace("KopfzeilenZusatz", "");
        
        $tplRechnung->replace("Titel", "");
        $tplRechnung->replace("GesamtNetto", "");
        $tplRechnung->replace("GesamtMwSt", "");
        $tplRechnung->replace("GesamtSumme", "");
        $tplRechnung->replace("AbschlagSatz", "");
        $tplRechnung->replace("AbschlagNetto", "");
        $tplRechnung->replace("AbschlagMwSt", "");
        $tplRechnung->replace("AbschlagSumme", "");
        
        $tplRechnung->replace("Abschlag1Text", str_replace("\r\n", "\\r\\n", ConstantLoader::getRechnungAbschlag1Text()));
        $tplRechnung->replace("Abschlag2Text", str_replace("\r\n", "\\r\\n", ConstantLoader::getRechnungAbschlag2Text()));
        $tplRechnung->replace("Abschlag3Text", str_replace("\r\n", "\\r\\n", ConstantLoader::getRechnungAbschlag3Text()));
        $tplRechnung->replace("Abschlag1Prozent", ConstantLoader::getRechnungAbschlag1Prozent());
        $tplRechnung->replace("Abschlag2Prozent", ConstantLoader::getRechnungAbschlag2Prozent());
        $tplRechnung->replace("Abschlag3Prozent", ConstantLoader::getRechnungAbschlag3Prozent());
        
        $tplRechnung->replace("StandardZahlungsziel", ConstantLoader::getStandardZahlungsziel());
        
        $t = new BufferedTemplate("rechnung_abschlag_ajax_ds.tpl");
        $t->addToBuffer(new Template("rechnung_abschlag_ajax_kopf.tpl"));
        $s = $t->getOutput();
        
        if (isset($_GET['gid'])) {
            $oGemeinde = new Gemeinde($_GET['gid']);
            $tplRechnung->replace("GID", $oGemeinde->getID());
            $tplRechnung->replace("GemeindeBezeichnung", $oGemeinde->getKirche());
            
            $r = AbschlagrechnungUtilities::getAbschlagsRechnungenOhneEndRechnung($oGemeinde->getID(), " ORDER BY ra_datum ASC");
            if ($r->getLength() >= 0) {
                foreach ($r as $oAbschlagsRechnung) {
                    $t->replace("ABezeichnung", $oAbschlagsRechnung->getANr() . ". Abschlag");
                    $t->replace("ANr", $oAbschlagsRechnung->getID());
                    $t->replace("NettoGesamt", $oAbschlagsRechnung->getGesamtNetto(true));
                    $t->replace("BruttoGesamt", $oAbschlagsRechnung->getGesamtBrutto(true));
                    $t->replace("NettoBetrag", $oAbschlagsRechnung->getNettoBetrag(true));
                    $t->replace("BruttoBetrag", $oAbschlagsRechnung->getBruttoBetrag(true));
                    $t->replace("ADatum", $oAbschlagsRechnung->getDatum(true));
                    $t->next();
                }
            }
            $s = $t->getOutput();
        } else {
            $f = new Template("rechnung_abschlag_ajax_keine.tpl");
            $s .= $f->forceOutput();
        }
        $tplRechnung->replace("Rechnungen", $s);
        $tplRechnung->replace("GID", 0);
        $tplRechnung->replace("Einleitung", "");
        
        // Rechnungsdatum & Zahlungsziel
        $tplRechnung->replace("Rechnungsdatum", date("d.m.Y"));
        $tplRechnung->replace("Zahlungsziel", date("d.m.Y", strtotime("+" . ConstantLoader::getStandardZahlungsziel() . " day")));
        
        // Rechnungsnummer
        $tplRechnung->replace("Rechnungsjahr", date("y"));
        
        $tplRechnung->replace("Rechnungsnummer", ConstantLoader::getAbschlagRechnungsNummerNaechste());
        
        $tplRechnung->anzeigen();
    }

    public static function druckeStundenrechnung()
    {
        if (! isset($_POST['gemeindeid']))
            return;
        
        foreach ($_POST as $key => $val)
            $_POST[$key] = addslashes(trim($val));
        
        $oRechnung = new StundenRechnung();
        
        $oRechnung->setGemeindeID($_POST['gemeindeid']);
        $oRechnung->setDatum($_POST['datum']);
        $oRechnung->setZieldatum($_POST['zahlungsziel']);
        $oRechnung->setNummer($_POST['rechnungsnummer']);
        $oRechnung->setText1($_POST['bemerkung1']);
        $oRechnung->setText2($_POST['bemerkung2']);
        $oRechnung->setAzubiLohn($_POST['azubi_lohn']);
        $oRechnung->setAzubiStd($_POST['azubi_std']);
        $oRechnung->setGeselleLohn($_POST['geselle_lohn']);
        $oRechnung->setGeselleStd($_POST['geselle_std']);
        $oRechnung->setMaterial($_POST['material']);
        $oRechnung->setFahrtkosten($_POST['fahrtkosten']);
        $oRechnung->errechneGesamtBetrag(true);
        $oRechnung->speichern(true);
        
        // Update der Rechnungsnummer
        $cs = new ConstantSetter();
        $rechnungsNummerOhneJahr = substr($_POST['rechnungsnummer'], 0, strpos($_POST['rechnungsnummer'], "/"));
        $cs->setPflegeRechnungsNummerNaechste($rechnungsNummerOhneJahr + 1);
        
        // Positionen speichern
        $iPosition = 1;
        foreach ($_POST as $key => $val) {
            // 'osition' ist richtig!
            if (strpos($key, "osition_") > 0) {
                $p = new RechnungsPosition();
                $p->setRechnungsID($oRechnung->getID());
                $p->setText($val);
                $p->setType(2);
                $p->setPosition($iPosition ++);
                $p->speichern();
            }
        }
        
        // Template öffnen
        // $tplRechnung = new StundenRechnungOutput("resources/vorlagen/rechnung_stunde", $oRechnung);
        $tplRechnung = new StundenRechnungOutput("resources/vorlagen/rechnung_stunde", $oRechnung);
        $tplRechnung->erstellen();
        
        $tplRechnung->speichern($oRechnung->getSpeicherOrt());
        
        // Zur Rechnung weiterleiten
        $tplStatus = new Template("rechnung_status_zurueck.tpl");
        $tplStatus->replace("Ziel", $oRechnung->getSpeicherOrt());
        $tplStatus->replace("Sekunden", 1);
        $tplStatus->anzeigen();
    }

    public static function neueStundenrechnung()
    {
        $tplRechnung = new Template("rechnung_stundenrechnung.tpl");
        $tplRechnung->replace("RechnungsKopf", RechnungUtilities::baueRechnungsAuswahlKopf()->getOutput());
        $tplRechnung->replace("disableForm", "");
        $tplRechnung->replace("SubmitValue", "Rechnung erstellen");
        $tplRechnung->replace("KopfzeilenZusatz", "");
        
        $tplRechnung->replace("AzubiStd", "");
        $tplRechnung->replace("GeselleStd", "");
        $tplRechnung->replace("GeselleLohn", "");
        $tplRechnung->replace("AzubiLohn", "");
        $tplRechnung->replace("Material", "");
        $tplRechnung->replace("Fahrtkosten", "");
        $tplRechnung->replace("SummeAzubi", "");
        $tplRechnung->replace("SummeGeselle", "");
        $tplRechnung->replace("Betrag", "");
        $tplRechnung->replace("StandardZahlungsziel", ConstantLoader::getStandardZahlungsziel());
        
        if (isset($_GET['gid'])) {
            $oGemeinde = new Gemeinde($_GET['gid']);
            $r = StundenrechnungUtilities::getLetzeGemeindeRechnung($oGemeinde->getID());
            if ($r != null) {
                if ($r->getFahrtkosten() == "") {
                    $tplRechnung->replace("LetzterRechnungsbetrag", $r->getNettoBetrag() . " Euro (Brutto)");
                    $tplRechnung->replace("LetzteFahrtkosten", "-,-- Euro");
                }
                $tplRechnung->replace("LetzterRechnungsbetrag", $r->getNettoBetrag() . " Euro (Netto)");
                $tplRechnung->replace("LetzteFahrtkosten", $r->getFahrtkosten() . " Euro");
            }
            $tplRechnung->replace("GID", $oGemeinde->getID());
            $tplRechnung->replace("GemeindeBezeichnung", $oGemeinde->getKirche());
        }
        
        $tplRechnung->replace("GID", 0);
        $tplRechnung->replace("GemeindeBezeichnung", "GEMEINDE");
        
        $tplRechnung->replace("Bemerkung1", "");
        $tplRechnung->replace("Bemerkung2", "");
        
        // Rechnungsdatum & Zahlungsziel
        $tplRechnung->replace("Rechnungsdatum", date("d.m.Y"));
        $tplRechnung->replace("Zahlungsziel", date("d.m.Y", strtotime("+" . ConstantLoader::getStandardZahlungsziel() . " day")));
        
        // Rechnungsnummer
        $tplRechnung->replace("Rechnungsjahr", date("y"));
        
        $tplRechnung->replace("Rechnungsnummer", ConstantLoader::getPflegeRechnungsNummerNaechste());
        
        // Positionen
        $tplRechnung->replace("Standardposition1", ConstantLoader::getStandardStundenrechnungPos1());
        $tplRechnung->replace("Standardposition2", ConstantLoader::getStandardStundenrechnungPos2());
        $tplRechnung->replace("Standardposition3", ConstantLoader::getStandardStundenrechnungPos3());
        $tplRechnung->replace("Standardposition4", ConstantLoader::getStandardStundenrechnungPos4());
        $tplRechnung->replace("Standardposition5", ConstantLoader::getStandardStundenrechnungPos5());
        $tplRechnung->replace("Standardposition6", ConstantLoader::getStandardStundenrechnungPos6());
        $tplRechnung->replace("Standardposition7", ConstantLoader::getStandardStundenrechnungPos7());
        $tplRechnung->replace("Standardposition8", ConstantLoader::getStandardStundenrechnungPos8());
        $tplRechnung->replace("Standardposition9", ConstantLoader::getStandardStundenrechnungPos9());
        $tplRechnung->replace("Standardposition10", ConstantLoader::getStandardStundenrechnungPos10());
        
        $tplRechnung->anzeigen();
    }

    public static function druckePflegerechnung()
    {
        if (! isset($_POST['gemeindeid']))
            return;
        
        foreach ($_POST as $key => $val)
            $_POST[$key] = addslashes(trim($val));
        
        $oRechnung = new PflegeRechnung();
        $oRechnung->setGemeindeID($_POST['gemeindeid']);
        $oRechnung->setDatum($_POST['datum']);
        $oRechnung->setZieldatum($_POST['zahlungsziel']);
        $oRechnung->setNummer($_POST['rechnungsnummer']);
        $oRechnung->setText1($_POST['bemerkung1']);
        $oRechnung->setText2($_POST['bemerkung2']);
        $oRechnung->setFahrtkosten($_POST['fahrtkosten']);
        $oRechnung->setPflegekosten($_POST['pflegebetrag']);
        $oRechnung->errechneGesamtBetrag(true);
        $oRechnung->speichern(true);
        
        // Update der Rechnungsnummer
        $cs = new ConstantSetter();
        $rechnungsNummerOhneJahr = substr($_POST['rechnungsnummer'], 0, strpos($_POST['rechnungsnummer'], "/"));
        $cs->setPflegeRechnungsNummerNaechste($rechnungsNummerOhneJahr + 1);
        
        // Rechnungspositionen speichern
        // Positionen speichern
        $iPosition = 1;
        foreach ($_POST as $key => $val) {
            // 'osition' ist richtig!
            if (strpos($key, "osition_") > 0) {
                $p = new RechnungsPosition();
                $p->setRechnungsID($oRechnung->getID());
                $p->setType(1);
                $p->setText($val);
                $p->setPosition($iPosition ++);
                $p->speichern();
            }
        }
        
        // Template öffnen
        // $tplRechnung = new PflegeRechnungOutput("resources/vorlagen/rechnung_pflege", $oRechnung);
        $tplRechnung = new PflegeRechnungOutput("resources/vorlagen/rechnung_pflege", $oRechnung);
        $tplRechnung->erstellen();
        $neuerSpeicherort = $oRechnung->getSpeicherOrt();
        $neuerSpeicherort = $tplRechnung->speichern($neuerSpeicherort);
        
        // Zur Rechnung weiterleiten
        $tplStatus = new HTMLRedirect("Rechnung wurde erstellt.", $neuerSpeicherort);
        $tplStatus->anzeigen();
    }

    public static function neuePflegerechnung()
    {
        $tplRechnung = new Template("rechnung_pflegerechnung.tpl");
        $tplRechnung->replace("RechnungsKopf", RechnungUtilities::baueRechnungsAuswahlKopf()->getOutput());
        $tplRechnung->replace("disableForm", "");
        $tplRechnung->replace("SubmitValue", "Rechnung erstellen");
        $tplRechnung->replace("KopfzeilenZusatz", "");
        $tplRechnung->replace("Betrag", "");
        $tplRechnung->replace("MwSt", "");
        $tplRechnung->replace("BruttoBetrag", "");
        $tplRechnung->replace("Fahrtkosten", "");
        $tplRechnung->replace("Pflegebetrag", "");
        $tplRechnung->replace("StandardZahlungsziel", ConstantLoader::getStandardZahlungsziel());
        
        if (isset($_GET['gid'])) {
            $oGemeinde = new Gemeinde($_GET['gid']);
            $r = PflegeRechnungUtilities::getLetztePflegeRechnung($oGemeinde->getID());
            if ($r != null) {
                // Fahrtkosten sind noch bei den ersten Rechnungen leer, Betrag war Brutto
                if ($r->getFahrtkosten() == "") {
                    $tplRechnung->replace("LetzteFahrt", "-,-- Euro");
                    $tplRechnung->replace("LetztePflege", "-,-- Euro");
                }
                
                // Pflegekosten == "", wenn Rechnung vor 6.11.2008 erstellt
                if ($r->getPflegekosten() == "") {
                    $tplRechnung->replace("LetzteFahrt", $r->getFahrtkosten(true));
                    $tplRechnung->replace("LetztePflege", 0);
                }
                
                $tplRechnung->replace("LetzteNetto", $r->getNettoBetrag(true));
                $tplRechnung->replace("LetzteBrutto", $r->getBruttoBetrag(true));
                $tplRechnung->replace("LetzteMwSt", $r->getMwSt(true));
                $tplRechnung->replace("LetzteFahrt", $r->getFahrtkosten(true));
                $tplRechnung->replace("LetztePflege", $r->getPflegekosten(true));
            }
            $tplRechnung->replace("GID", $oGemeinde->getID());
            $tplRechnung->replace("GemeindeBezeichnung", $oGemeinde->getKirche());
        }
        
        $tplRechnung->replace("LetzteNetto", "");
        $tplRechnung->replace("LetzteBrutto", "");
        $tplRechnung->replace("LetzteMwSt", "");
        $tplRechnung->replace("LetzteFahrt", "");
        $tplRechnung->replace("LetztePflege", "");
        
        $tplRechnung->replace("GID", 0);
        $tplRechnung->replace("GemeindeBezeichnung", "GEMEINDE");
        $tplRechnung->replace("Bemerkung1", "");
        $tplRechnung->replace("Bemerkung2", "");
        
        // Rechnungsdatum & Zahlungsziel
        $iZahlungsZiel = ConstantLoader::getStandardZahlungsziel();
        $tplRechnung->replace("Rechnungsdatum", date("d.m.Y"));
        $tplRechnung->replace("Zahlungsziel", date("d.m.Y", strtotime("+" . $iZahlungsZiel . " day")));
        
        // Rechnungsnummer
        $tplRechnung->replace("Rechnungsjahr", date("y"));
        
        $tplRechnung->replace("Rechnungsnummer", ConstantLoader::getPflegeRechnungsNummerNaechste());
        
        // Standardantworten
        $tplRechnung->replace("Standardposition1", ConstantLoader::getStandardPflegerechnungPos1());
        $tplRechnung->replace("Standardposition2", ConstantLoader::getStandardPflegerechnungPos2());
        $tplRechnung->replace("Standardposition3", ConstantLoader::getStandardPflegerechnungPos3());
        $tplRechnung->replace("Standardposition4", ConstantLoader::getStandardPflegerechnungPos4());
        $tplRechnung->replace("Standardposition5", ConstantLoader::getStandardPflegerechnungPos5());
        $tplRechnung->replace("Standardposition6", ConstantLoader::getStandardPflegerechnungPos6());
        $tplRechnung->replace("Standardposition7", ConstantLoader::getStandardPflegerechnungPos7());
        $tplRechnung->replace("Standardposition8", ConstantLoader::getStandardPflegerechnungPos8());
        $tplRechnung->replace("Standardposition9", ConstantLoader::getStandardPflegerechnungPos9());
        $tplRechnung->replace("Standardposition10", ConstantLoader::getStandardPflegerechnungPos10());
        
        // Rechnung ausgeben
        $tplRechnung->anzeigen();
    }

    /**
     * Zeigt eine Liste aller Rechnungen an mit der Möglichkeit der Filterung nach bestimmten Kriterien
     */
    public static function zeigeRechnungsListe()
    {
        $tplRechnungen = new Template("rechnung_liste.tpl");
        $tplRechnungsDS = new BufferedTemplate("rechnung_liste_ds.tpl", "CSS", "td1", "td2");
        $rechnungsTypen = array(
            0,
            1,
            2,
            3,
            4
        );
        $strWhere = " WHERE ";
        $selectedVon = null;
        $selectedBis = null;
        $selectedBetrag = null;
        $summe = 0;
        $bruttoSumme = 0;
        
        // POST Handling
        if ($_POST && $_POST['submit'] != "Zurücksetzen") {
            if ($_POST['typ'] != 0) {
                $strWhere .= "r_typid = " . intval($_POST['typ']);
                $tplRechnungen->replace("selected" . $_POST['typ'], Constant::$HTML_SELECTED_SELECTED);
            } else {
                $strWhere .= "r_typid <> 0";
            }
            
            if (isset($_POST['von'])) {
                $strWhere .= " AND r_datum >= '" . $_POST['von'] . "'";
                $selectedVon = $_POST['von'];
            }
            
            if (isset($_POST['bis'])) {
                $strWhere .= " AND r_datum <= '" . $_POST['bis'] . "'";
                $selectedBis = $_POST['bis'];
            }
            
            if (isset($_POST['maxbetrag'])) {
                $strWhere .= " AND r_bruttobetrag <= " . $_POST['maxbetrag'];
                $selectedBetrag = $_POST['maxbetrag'];
            }
        } else {
            if (date("m") <= 2) {
                $selectedBis = date("Y") . "-02-31";
                $selectedVon = (date("Y") - 1) . "-01-01";
            } else {
                $selectedBis = date("Y") . "-12-31";
                $selectedVon = date("Y") . "-01-01";
            }
            $strWhere = " WHERE r_datum >=  '" . $selectedVon . "' AND r_datum <='" . $selectedBis . "'";
        }
        
        // GET Handling Start
        $handler = new RechnungsListeRequestHandler();
        $handledRequest = $handler->prepareRequest();
        
        // $strWhere .= " ORDER BY r_datum DESC";
        $strWhere .= $handledRequest['RESULT'];
        
        $tplRechnungen->replace("Dir", $handledRequest->getValueOf("TPLDIR"));
        $tplRechnungen->replace("Order", $handledRequest->getValueOf("TPLORDER"));
        
        // GET Handling End
        
        $cRechnungsListe = RechnungViewUtilities::getFilteredRechnung($strWhere);
        
        // Inhalt der Datums Einschränkungen
        $cVonDaten = new DatabaseStorageObjektCollection();
        $cBisDaten = new DatabaseStorageObjektCollection();
        $cAlleRechnungsDaten = RechnungViewUtilities::getRechnungsDatumListAsArray();
        $tmpVonDaten = NULL;
        $tmpBisDaten = NULL;
        $bErsterDatensatz = true;
        $bUpdateVonDatensatz = true;
        $cacheLast = array(
            "0",
            "0"
        );
        
        foreach ($cAlleRechnungsDaten as $oRechnungsDatum) {
            $x = date("m/Y", strtotime($oRechnungsDatum['datum']));
            if ($cacheLast[0] != $x) {
                $tmpVonDaten = new OptionvalueObjekt();
                $tmpVonDaten->setID(date("Y-m", strtotime($oRechnungsDatum['datum'])) . "-01");
                $tmpVonDaten->setName($x);
                $cVonDaten->add($tmpVonDaten);
                
                $tmpBisDaten = new OptionvalueObjekt();
                $tmpBisDaten->setID(date("Y-m", strtotime($oRechnungsDatum['datum'])) . "-31");
                $tmpBisDaten->setName($x);
                $cBisDaten->add($tmpBisDaten);
                
                // Fuer die Statistik
                if ($selectedBis == "" && $bErsterDatensatz) {
                    $selectedBis = $tmpBisDaten->getID();
                    $bErsterDatensatz = false;
                }
                
                if ($bUpdateVonDatensatz && strtotime($tmpVonDaten->getID()) < strtotime($selectedVon)) {
                    $selectedVon = $cacheLast[1];
                    $bUpdateVonDatensatz = false;
                }
                
                // merken...
                $cacheLast = array(
                    $x,
                    $tmpVonDaten->getID()
                );
            }
        }
        
        // Inhalt für maximalen Betrag
        $cBetrag = new DatabaseStorageObjektCollection();
        $tmp = new OptionvalueObjekt();
        $tmp->setID(10000000);
        $tmp->setName("ohne Begrenzung");
        $cBetrag->add($tmp);
        for ($i = 100; $i <= 2000; $i += 100) {
            $tmp = new OptionvalueObjekt();
            $tmp->setID($i);
            $tmp->setName("bis " . $i . " Euro");
            $cBetrag->add($tmp);
        }
        
        // Rechnungsselektierung aufräumen
        foreach ($rechnungsTypen as $i) {
            $tplRechnungen->replace("selected" . $i, "");
        }
        
        // Selectboxen im Template füllen
        $selectVon = new HTMLSelect($cVonDaten, "getName", $selectedVon);
        $selectBis = new HTMLSelect($cBisDaten, "getName", $selectedBis);
        $selectBetrag = new HTMLSelect($cBetrag, "getName", $selectedBetrag);
        
        $tplRechnungen->replace("Von", $selectVon->getOutput());
        $tplRechnungen->replace("Bis", $selectBis->getOutput());
        $tplRechnungen->replace("MaxBetrag", $selectBetrag->getOutput());
        
        // Ausgabe der Datensätze
        if ($cRechnungsListe->getSize() > 0) {
            $tplRechnungen->replace("disabled", "");
            foreach ($cRechnungsListe as $oRechnung) {
                $tplRechnungsDS->replace("RechnungsNr", $oRechnung->getNummer());
                $tplRechnungsDS->replace("Typ", $oRechnung->getRechnungsTyp());
                $tplRechnungsDS->replace("TypID", $oRechnung->getRechnungsTypId());
                $tplRechnungsDS->replace("RID", $oRechnung->getId());
                $tplRechnungsDS->replace("Typ", $oRechnung->getRechnungsTyp());
                $tplRechnungsDS->replace("Datum", $oRechnung->getDatum(true));
                $tplRechnungsDS->replace("Gemeinde", $oRechnung->getGemeindeName());
                $tplRechnungsDS->replace("Netto", $oRechnung->getNettoBetrag(true));
                $tplRechnungsDS->replace("Brutto", $oRechnung->getBruttoBetrag(true));
                $tplRechnungsDS->replace("JSRechnungsID", $oRechnung->getRechnungsTypId() . "_" . $oRechnung->getId());
                $tplRechnungsDS->replace("EingangsAnmerkung", $oRechnung->getEingangsAnmerkung());
                
                if ($oRechnung->getEingangsDatum() != "0000-00-00") {
                    $tplRechnungsDS->replace("EingangsDatum", $oRechnung->getEingangsDatum(true));
                    $tplRechnungsDS->replace("EingangsBetrag", $oRechnung->getEingangsBetrag(true));
                    if ($oRechnung->getEingangsBetrag() < $oRechnung->getBruttoBetrag()) {
                        $tplRechnungsDS->replace("IconImageName", "gem_cancel_1.png");
                    } elseif ($oRechnung->getEingangsBetrag() > $oRechnung->getBruttoBetrag()) {
                        $tplRechnungsDS->replace("IconImageName", "gem_remove.png");
                    }
                    $tplRechnungsDS->replace("IconImageName", "gem_okay.png");
                }
                $tplRechnungsDS->replace("EingangsBetrag", $oRechnung->getBruttoBetrag(true));
                $tplRechnungsDS->replace("IconImageName", "sprocket_light.png");
                $tplRechnungsDS->replace("EingangsDatum", date("d.m.Y"));
                
                $summe += $oRechnung->getNettoBetrag();
                $bruttoSumme += $oRechnung->getBruttoBetrag();
                $tplRechnungsDS->next();
            }
        } else {
            // Auskommentiert, z.B. wenn es Rechnungen aus dem vorherhige Jahr gibt aber im aktuellen noch keine geschrieben wurde.
            $tplRechnungen->replace("disabled", "");
            $tplRechnungsDS->loadNewTemplate("templates/rechnung_liste_keine.tpl");
            $tplRechnungsDS->next();
        }
        
        // Statistische Angaben
        $tplRechnungen->replace("AnzahlRechnungen", $cRechnungsListe->getSize());
        $tplRechnungen->replace("GesamtNetto", Rechnung::toWaehrung($summe));
        $tplRechnungen->replace("GesamtBrutto", Rechnung::toWaehrung($bruttoSumme));
        $tplRechnungen->replace("ZeitraumVon", date("d.m.Y", strtotime($selectedVon)));
        $tplRechnungen->replace("ZeitraumBis", date("d.m.Y", strtotime($selectedBis)));
        
        // Ausgabe der Liste
        $tplRechnungen->replace("RechnungsListe", $tplRechnungsDS->getOutput());
        $tplRechnungen->anzeigen();
    }

    public static function zeigeReadOnlyRechnung()
    {
        if (! isset($_GET['typid'], $_GET['id']))
            return;
        
        $tplRechnung = null;
        $oRechnung = null;
        switch ($_GET['typid']) {
            case 1:
                $tplRechnung = new Template("rechnung_pflegerechnung.tpl");
                $oRechnung = new PflegeRechnung($_GET['id']);
                $o = new PflegeRechnungTemplateBuilder($tplRechnung, $oRechnung);
                $o->getTemplate()->replace("Betrag", $oRechnung->getNettoBetrag(true));
                $o->getTemplate()->replace("Fahrtkosten", $oRechnung->getFahrtkosten(true));
                if ($oRechnung->getPflegekosten() == "0.00")
                    $o->getTemplate()->replace("Pflegebetrag", Rechnung::toWaehrung($oRechnung->getNettoBetrag() - $oRechnung->getFahrtkosten()));
                $o->getTemplate()->replace("Pflegebetrag", $oRechnung->getPflegekosten(true));
                
                $oLetzteRechnung = PflegeRechnungUtilities::getLetztePflegeRechnungVor($oRechnung->getGemeindeID(), $oRechnung->getDatum());
                if ($oLetzteRechnung == null) {
                    $o->getTemplate()->replace("LetztePflege", "");
                    $o->getTemplate()->replace("LetzteFahrt", "");
                    $o->getTemplate()->replace("LetzteNetto", "");
                    $o->getTemplate()->replace("LetzteMwSt", "");
                    $o->getTemplate()->replace("LetzteBrutto", "");
                } else {
                    $o->getTemplate()->replace("LetzteRechnung", $oLetzteRechnung->getDatum(true));
                    $o->getTemplate()->replace("LetztePflege", $oLetzteRechnung->getPflegekosten(true));
                    $o->getTemplate()->replace("LetzteFahrt", $oLetzteRechnung->getFahrtkosten());
                    $o->getTemplate()->replace("LetzteNetto", $oLetzteRechnung->getNettobetrag(true));
                    $o->getTemplate()->replace("LetzteMwSt", $oLetzteRechnung->getMwSt(true));
                    $o->getTemplate()->replace("LetzteBrutto", $oLetzteRechnung->getBruttoBetrag(true));
                }
                break;
            case 2:
                $tplRechnung = new Template("rechnung_stundenrechnung.tpl");
                $oRechnung = new StundenRechnung($_GET['id']);
                $o = new StundenRechnungTemplateBuilder($tplRechnung, $oRechnung);
                
                $o->getTemplate()->replace("AzubiStd", $oRechnung->getAzubiStd());
                $o->getTemplate()->replace("GeselleStd", $oRechnung->getGeselleStd());
                $o->getTemplate()->replace("GeselleLohn", $oRechnung->getGeselleLohn(true));
                $o->getTemplate()->replace("AzubiLohn", $oRechnung->getAzubiLohn(true));
                $o->getTemplate()->replace("Material", $oRechnung->getMaterial(true));
                $o->getTemplate()->replace("Fahrtkosten", $oRechnung->getFahrtkosten(true));
                $o->getTemplate()->replace("SummeAzubi", Rechnung::toWaehrung($oRechnung->getAzubiStd() * $oRechnung->getAzubiLohn()));
                $o->getTemplate()->replace("SummeGeselle", Rechnung::toWaehrung($oRechnung->getGeselleStd() * $oRechnung->getGeselleLohn()));
                $o->getTemplate()->replace("Betrag", $oRechnung->getNettoBetrag(true));
                break;
            case 3:
                $tplRechnung = new Template("rechnung_abschlag.tpl");
                $oRechnung = new AbschlagsRechnung($_GET['id']);
                $o = new AbschlagRechnungTemplateBuilder($tplRechnung, $oRechnung);
                break;
            case 4:
                $tplRechnung = new Template("rechnung_endrechnung.tpl");
                $oRechnung = new EndRechnung($_GET['id']);
                $o = new EndRechnungTemplateBuilder($tplRechnung, $oRechnung);
                
                $r = AbschlagrechnungUtilities::getAbschlagsRechnungenFuerEndRechnung($oRechnung->getID());
                $k = new Template("rechnung_end_ajax_kopf.tpl");
                $t = new Template("rechnung_end_ajax_ds.tpl");
                $s = $k->forceOutput();
                if ($r->getSize() >= 0) {
                    foreach ($r as $oAbschlagsRechnung) {
                        $t->replace("ABezeichnung", $oAbschlagsRechnung->getANr() . ". Abschlag");
                        $t->replace("ANr", $oAbschlagsRechnung->getID());
                        $t->replace("NettoGesamtFormatiert", $oAbschlagsRechnung->getGesamtNetto(true));
                        $t->replace("BruttoGesamtFormatiert", $oAbschlagsRechnung->getGesamtBrutto(true));
                        $t->replace("NettoBetragFormatiert", $oAbschlagsRechnung->getNettoBetrag(true));
                        $t->replace("BruttoBetragFormatiert", $oAbschlagsRechnung->getBruttoBetrag(true));
                        $t->replace("NettoBetragUnformatted", $oAbschlagsRechnung->getNettoBetrag());
                        $t->replace("NettoGesamtUnformatted", $oAbschlagsRechnung->getBruttoBetrag());
                        $t->replace("ADatum", $oAbschlagsRechnung->getDatum(true));
                        $t->replace("disabled", "disabled");
                        $t->replace("checked", Constant::$HTML_CHECKED_CHECKED);
                        $s .= $t->getOutputAndRestore();
                    }
                }
                $tplRechnung->replace("Rechnungen", $s);
                break;
        }
        
        $colPositionen = RechnungsPositionUtilities::getRechnungsPositionen($oRechnung->getID(), $oRechnung->getType());
        if ($colPositionen->getSize() > 0) {
            $iPosCounter = 1;
            foreach ($colPositionen as $currentPos) {
                $o->getTemplate()->replace("Standardposition" . $iPosCounter ++, stripslashes($currentPos->getText()));
            }
        }
        $o->getTemplate()->replace("RechnungsKopf", RechnungUtilities::baueRechnungsAuswahlKopf($oRechnung)->getOutput());
        $o->getTemplate()->replace("disableForm", "disabled");
        $o->getTemplate()->replace("SubmitValue", "Rechnung erstellen (nicht verf&uuml;gbar)");
        $o->getTemplate()->replace("KopfzeilenZusatz", "(Anzeigemodus)");
        $o->getTemplate()->plainReplace("<!--datePicker-->", ""); // Datumspicker disablen
        $o->getTemplate()->plainReplace("/<!--Rechnungsjahr-->", "");
        
        $o->erstellen();
        $o->anzeigen();
    }

    public static function loescheRechnung()
    {
        if ($_POST) {
            switch ($_POST['typid']) {
                case 1:
                    $oRechnung = new PflegeRechnung($_POST['rid']);
                    break;
                case 2:
                    $oRechnung = new StundenRechnung($_POST['rid']);
                    break;
                case 3:
                    $oRechnung = new AbschlagsRechnung($_POST['rid']);
                    break;
                case 4:
                    $oRechnung = new EndRechnung($_POST['rid']);
                    break;
            }
            $oRechnung->loeschen();
            $tpl = new HTMLRedirect("Die Rechnung wurde gelöscht", "index.php?page=5&do=89");
        } else {
            $tpl = new Template("rechnung_loeschen_dialog.tpl");
            $tpl->replace("RID", $_GET['id']);
            $tpl->replace("TypID", $_GET['typid']);
        }
        
        $tpl->anzeigen();
    }

    public static function verbucheEingangsRechnung()
    {
        if (! $_POST || ! isset($_POST['id'], $_POST['typid'], $_POST['betrag'], $_POST['datum'], $_POST['anmerkung']) || intval($_POST['betrag']) == 0) {
            echo "Falsche Parameter!";
            return;
        }
        
        $_POST['betrag'] = WaehrungUtil::formatWaehrungToDB($_POST['betrag']);
        $_POST['datum'] = date("Y-m-d", strtotime($_POST['datum']));
        
        switch ($_POST['typid']) {
            case 1:
                $oRechnung = new PflegeRechnung($_POST['id']);
                break;
            case 2:
                $oRechnung = new StundenRechnung($_POST['id']);
                break;
            case 3:
                $oRechnung = new AbschlagsRechnung($_POST['id']);
                break;
            case 4:
                $oRechnung = new EndRechnung($_POST['id']);
                break;
            default:
                throw new IllegalArgumentException("invalid typid for bill: " . $_POST['typid'], null);
        }
        $oRechnung->setEingangsAnmerkung(addslashes($_POST['anmerkung']));
        $oRechnung->setEingangsDatum($_POST['datum']);
        $oRechnung->setEingangsBetrag($_POST['betrag']);
        $oRechnung->speichern(false);
        
        $oRechnung = RechnungViewUtilities::getRechnungByIdAndType($oRechnung->getID(), $_POST['typid']);
        $oRechnung = $oRechnung[0];
        $tpl = new Template("rechnung_liste_ds.tpl");
        $tpl->replace("RechnungsNr", $oRechnung->getNummer());
        $tpl->replace("Typ", $oRechnung->getRechnungsTyp());
        $tpl->replace("TypID", $oRechnung->getRechnungsTypId());
        $tpl->replace("RID", $oRechnung->getId());
        $tpl->replace("Typ", $oRechnung->getRechnungsTyp());
        $tpl->replace("Datum", $oRechnung->getDatum(true));
        $tpl->replace("Gemeinde", $oRechnung->getGemeindeName());
        $tpl->replace("Netto", $oRechnung->getNettoBetrag(true));
        $tpl->replace("Brutto", $oRechnung->getBruttoBetrag(true));
        $tpl->replace("JSRechnungsID", $oRechnung->getRechnungsTypId() . "_" . $oRechnung->getId());
        $tpl->replace("EingangsBetrag", $oRechnung->getEingangsBetrag(true));
        $tpl->replace("EingangsAnmerkung", $oRechnung->getEingangsAnmerkung());
        $tpl->replace("CSS", $_POST['css']);
        
        if ($oRechnung->getEingangsDatum() != "0000-00-00") {
            $tpl->replace("EingangsDatum", $oRechnung->getEingangsDatum(true));
            if ($oRechnung->getEingangsBetrag() < $oRechnung->getBruttoBetrag()) {
                $tpl->replace("IconImageName", "gem_cancel_1.png");
            } elseif ($oRechnung->getEingangsBetrag() > $oRechnung->getBruttoBetrag()) {
                $tpl->replace("IconImageName", "gem_remove.png");
            }
            $tpl->replace("IconImageName", "gem_okay.png");
        }
        $tpl->replace("IconImageName", "sprocket_light.png");
        $tpl->replace("EingangsDatum", date("d.m.Y"));
        
        $tpl->plainReplace("<tr id=\"" . $oRechnung->getRechnungsTypId() . "_" . $oRechnung->getId() . "\">", "");
        $tpl->plainReplace("</tr>", "");
        $tpl->anzeigen();
    }

    public static function sucheRechnungsPosition()
    {
        RequestHandler::handle(new RechnungsPositionsSuggestionAction());
    }
}
?>