<?php

class BenutzerController
{

    public static function benutzerZeitauswertung()
    {
        global $webUser;
        
        $tpl = new Template("benutzer_zeiten_auswertung.tpl");
        
        $benutzer = new Benutzer($webUser->getID());
        $eintritt = strtotime($benutzer->getEintrittsDatum());
        $heute = time();
        $dauer = $heute - $eintritt;
        $dauer = $dauer / (60 * 60 * 24 * 30 * 12);
        $dauer = round($dauer, 1);
        
        $tpl->replace("Eintrittsdatum", $benutzer->getEintrittsDatum(true));
        $tpl->replace("Dauer", $dauer);
        
        $ueberstunden = BenutzerUtilities::berechneUeberstunden($benutzer->getID());
        $ueberstunden = $ueberstunden == "" ? 0 : $ueberstunden;
        
        $urlaubstageGesamt = $benutzer->getUrlaubstage() / 8;
        $urlaubstageGesamt = $urlaubstageGesamt == "" ? 0 : $urlaubstageGesamt;
        
        $urlaubRest = $benutzer->getUrlaubRest() / 8;
        $urlaubRest = $urlaubRest == "" ? 0 : $urlaubRest;
        
        $urlaubGesamt = $benutzer->getUrlaubAktuell() / 8;
        $urlaubGesamt = $urlaubGesamt == "" ? 0 : $urlaubGesamt;
        
        $urlaubGenommen = $urlaubstageGesamt + $urlaubRest - $urlaubGesamt;
        $urlaubGenommen = $urlaubGenommen == "" ? 0 : $urlaubGenommen;
        
        $tpl->replace("Ueberstunden", $ueberstunden);
        $tpl->replace("UrlaubJahr", $urlaubstageGesamt);
        $tpl->replace("UrlaubRest", $urlaubRest);
        $tpl->replace("UrlaubGenommen", $urlaubGenommen);
        $tpl->replace("UrlaubGesamt", $urlaubGesamt);
        $tpl->anzeigen();
    }

    public static function benutzerdatenAendern()
    {
        global $webUser;
        
        if (! $webUser->isAuthed())
            die("Forbidden!");
        
        $tpl = new Template("benutzer_datenaendern.tpl");
        $tplStatus = null;
        $benutzer = new Benutzer($webUser->getID());
        
        $iMinPWLength = ConstantLoader::getBenutzerMinPasswortLength();
        $tpl->replace("MinPWLength", $iMinPWLength);
        
        // wird auch direkt nach dem Login aufgerufen, deshalb pruefen ob das Feld "bestaetigung" gesetzt ist
        if ($_POST && isset($_POST['bestaetigung'])) {
            $tplStatus = new HTMLStatus("", 3);
            
            if ($_POST['passwort'] != "" && strlen($_POST['passwort']) < $iMinPWLength) {
                $tplStatus->setText("Passwort ist zu kurz. Mindestens " . $iMinPWLength . " Zeichen");
                $tplStatus->setStatusclass(1);
            } elseif ($_POST['passwort'] != "" && $_POST['passwort'] != $_POST['bestaetigung']) {
                $tplStatus->setText("Zwei verschiedene Passw&ouml;rter eingegeben!");
                $tplStatus->setStatusclass(1);
            } elseif ($_POST['passwort'] != "" && ($_POST['passwort'] == $benutzer->getVorname() || $_POST['passwort'] == $benutzer->getNachname() || $_POST['passwort'] == $benutzer->getBenutzername())) {
                $tplStatus->setText("Passwort darf weder Vor- Nach- noch dem Benutzernamen entsprechen!" . $_POST['passwort']);
                $tplStatus->setStatusclass(1);
            } else {
                // Benutzer laden
                $benutzer->setVorname($_POST['vorname']);
                $benutzer->setNachname($_POST['nachname']);
                
                if ($_POST['passwort'] != "") {
                    $benutzer->setPasswort(md5($_POST['passwort']));
                }
                
                $tplStatus->setText("Benutzerdaten gespeichert");
                $tplStatus->setStatusclass(2);
                
                $benutzer->speichern(false);
            }
        }
        
        $tpl->replace("Vorname", $benutzer->getVorname());
        $tpl->replace("Nachname", $benutzer->getNachname());
        $tpl->replace("Benutzername", $benutzer->getBenutzername());
        
        if ($tplStatus != null)
            $tpl->replace("Statusmeldung", $tplStatus->getOutput());
        $tpl->anzeigen();
    }

    public static function benutzerLogout()
    {
        global $webUser;
        
        $webUser->logout();
        
        $h = new HTMLRedirect("Sie wurden erfolgreich ausgeloggt", "index.php");
        $h->anzeigen();
    }

    public static function zeigeZeiterfassung()
    {
        global $webUser;
        
        // Fuer welches Projekt
        if ($_POST && isset($_POST['p'])) {
            $pid = intval($_POST['p']);
        } elseif (isset($_GET['p'])) {
            $pid = intval($_GET['p']);
        } elseif (isset($_SESSION['letzte_projekt_id'])) {
            $pid = $_SESSION['letzte_projekt_id'];
        } else {
            $pid = 0;
        }
        
        $tpl = new Template("benutzer_zeit.tpl");
        
        // Fuer welchen Benutzer?
        if ($_POST && isset($_POST['formName'], $_POST['m']) && $webUser->isAdmin()) {
            $benutzer = new Benutzer($_POST['m']);
            $formTarget = "index.php?page=6&do=101&uid=" . $benutzer->getID();
            $tpl->replace("MitarbeiterSwitchDisabled", "");
        } elseif (isset($_GET['uid']) && $webUser->isAdmin()) {
            $benutzer = new Benutzer($_GET['uid']);
            $formTarget = "index.php?page=6&do=101&uid=" . $benutzer->getID();
            $tpl->replace("MitarbeiterSwitchDisabled", "");
        } elseif ($webUser->isAdmin()) {
            $benutzer = $webUser->getBenutzer();
            $formTarget = "index.php?page=6&do=101";
            $tpl->replace("MitarbeiterSwitchDisabled", "");
        } else {
            $benutzer = $webUser->getBenutzer();
            $formTarget = "index.php?page=8&do=142";
            $tpl->replace("MitarbeiterSwitchDisabled", "disabled");
        }
        
        // Demo Patching
        if ($benutzer->isDemo()) {
            // einfach den ersten gueltigen Benutzer nehmen
            $benutzer = BenutzerUtilities::getBenutzer()[0];
        }
        
        // Benutzer fuer Zeiterfassung zugelassen?
        if ($benutzer->isZeiterfassung() == false) {
            $html = new HTMLStatus("Keine Zulassung für Zeiterfassung", 1);
            $html->anzeigen();
            return;
        } else {
            // ProjektId in Session merken fuer Vorauswahl in Zeiterfassungsdialogen / Benutzerübergreifend
            $_SESSION['letzte_projekt_id'] = $pid;
        }
        // Mitarbeiter Selectbox
        $c = BenutzerUtilities::getZeiterfassungsBenutzer();
        $htmlSelect = new HTMLSelect($c, "getBenutzername", $benutzer->getID());
        $tpl->replace("Mitarbeiter", $htmlSelect->getOutput());
        
        $formTarget .= "&p=" . $pid;
        $tpl->replace("FormTarget", $formTarget);
        
        $tplStatus = null; // wird trotzdem gebraucht, auch wenn als WARNING angezeigt wird
        $isWocheChanged = false;
        
        // Navigation
        if (isset($_SESSION['request']['woche'])) {
            $woche = $_SESSION['request']['woche'];
        } else {
            $woche = time();
            $isWocheChanged = true;
        }
        
        if ($_POST && ! isset($_POST['formName'])) {
            if ($_POST['submit'] == "Vorherige Woche") {
                $woche = strtotime("-7 day", $_SESSION['request']['woche']);
                $isWocheChanged = true;
            } elseif ($_POST['submit'] == "Nächste Woche") {
                $woche = strtotime("+7 day", $_SESSION['request']['woche']);
                $isWocheChanged = true;
            } elseif ($_POST['submit'] == "Aktuelle Woche") {
                $woche = time();
                $isWocheChanged = true;
            } elseif ($_POST['submit'] == "Speichern") {}
        } elseif ($_POST && isset($_POST['formName'])) {
            $woche = $_POST['w'];
            $isWocheChanged = true;
        } elseif (isset($_GET['date'])) {
            $woche = intval($_GET['date']);
            $isWocheChanged = true;
        }
        
        // Überprüfung wie weit der Benutzer sich die Wochen anschauen darf
        if ($woche <= strtotime($benutzer->getCreatedAt())) {
            $tplStatus = new HTMLStatus("Erster Datensatz erreicht", 4);
        } elseif ($woche >= strtotime("+7 days")) {
            $woche = strtotime("+7 days");
            $isWocheChanged = true;
            $tplStatus = new HTMLStatus("Letzter Datensatz erreicht", 4);
        }
        
        // Arbeitswoche berechnen
        $arWochentage = Date::berechneArbeitswoche($woche);
        $arWochentageTS = Date::berechneArbeitswocheTimestamp($woche);
        $_SESSION['request']['woche'] = $woche;
        
        $kw = date("W", $arWochentageTS[4]); // ISO 8601 Der Donnerstag der Woche ist entscheidend. Problemfall 2019
        $jahr = date("Y", $arWochentageTS[4]); // ISO 8601 Der Donnerstag der Woche ist entscheidend. Problemfall 2019
        
        // Projekte für SelectBox
        $gemeindeCache = new HashTable();
        $c = ProjektUtilities::getBenutzerAufgaben($benutzer->getID());
        $tplSelect = new BufferedTemplate("select_option.tpl");
        $bisherGeleisteteStundenProProjekt = ArbeitstagUtilities::getMitarbeiterZeitraumStundenProProjekt($benutzer->getID(), date("Y-m-d", $arWochentageTS[0]), date("Y-m-d", $arWochentageTS[6]));
        $bisherStundenSumme = 0;
        foreach ($c as $projekt) {
            if (! $gemeindeCache->containsKey($projekt->getGemeindeID())) {
                $gemeindeCache->put($projekt->getGemeindeID(), new Gemeinde($projekt->getGemeindeID()));
            }
            
            $stundenZusatz = "";
            if (isset($bisherGeleisteteStundenProProjekt[$projekt->getID()])) {
                $stundenZusatz = " (" . $bisherGeleisteteStundenProProjekt[$projekt->getID()] . " Std.)";
                $bisherStundenSumme = $bisherStundenSumme + $bisherGeleisteteStundenProProjekt[$projekt->getID()];
            }
            $tplSelect->replace("Name", $gemeindeCache->getValueOf($projekt->getGemeindeID())
                ->getKirche() . ", " . $projekt->getBezeichnung() . $stundenZusatz);
            $tplSelect->replace("Value", $projekt->getID());
            if ($pid == $projekt->getID()) {
                $tplSelect->replace("Selected", Constant::$HTML_SELECTED_SELECTED);
            }
            $tplSelect->replace("Selected", "");
            $tplSelect->next();
            
            if ($pid == 0) {
                $pid = $projekt->getID();
            }
        }
        $tpl->replace("ProjektSelectbox", $tplSelect->getOutput());
        
        // Select Box KW Auswahl START
        $kwArray = array();
        $selectedKW = 0;
        for ($i = 0; $i < 52; $i ++) {
            $tmpKW = strtotime("-" . ($i * 7) . " day");
            $tmpAWoche = Date::berechneArbeitswocheTimestamp($tmpKW);
            $kwArray[$tmpAWoche[0]] = "KW " . date("W", $tmpAWoche[1]) . " vom " . date("d.m.Y", $tmpAWoche[0]) . " bis zum " . date("d.m.Y", $tmpAWoche[6]);
            if ($arWochentageTS[0] < $tmpAWoche[0]) {
                $selectedKW = $tmpAWoche[0];
            }
        }
        $kwSelect = new HTMLSelectForArray($kwArray, $selectedKW);
        $tpl->replace("KWSelect", $kwSelect->getOutput());
        // Select KW Auswahl ENDE
        
        // Wochentagsausgabe
        $tplDSRubrik = new BufferedTemplate("benutzer_zeit_rubrik.tpl");
        for ($i = 0; $i < 7; $i ++) {
            $feiertagsZusatz = $arWochentage[$i];
            $feiertag = Date::getFeiertagsBezeichnung($arWochentage[$i]);
            if ($feiertag != "") {
                $feiertagsZusatz = "<span style=\"color: #CE0000;\">" . $feiertagsZusatz . "<br/>" . $feiertag . "</font>";
            }
            $tplDSRubrik->replace("Datum" . ($i + 1), $feiertagsZusatz);
        }
        $tplDSRubrik->perceive();
        
        // Grundvoraussetzung, damit Daten gespeichert werden.
        // Sorgt ebenfalls dafuer das bei "Naechste" und "Vorherhige" Woche keine Datensaetze geaendert werden
        $doDatenSpeichern = ! $isWocheChanged && $_POST && ! isset($_POST['formName']) && $_POST['submit'] == "Speichern";
        
        // Gesperrt & Komplettierung der Woche unbedingt vor der Verarbeitung prüfen
        $boGesperrt = ArbeitstagUtilities::isArbeitswocheGesperrt($woche);
        $boIstKomplett = ArbeitstagUtilities::isBenutzerArbeitswocheKomplett($woche, $benutzer->getID());
        $boSollKomplett = $boIstKomplett;
        
        if ($boIstKomplett && isset($_POST['woche_komplett'])) {
            Log::debug("Woche ist bereits komplett. Keine Aenderung dieses Zustandes.");
            $doDatenSpeichern = false;
        } elseif ($doDatenSpeichern && $_POST && isset($_POST['woche_komplett'])) {
            Log::debug("Woche soll als KOMPLETT markiert werden");
            $boSollKomplett = true;
        } elseif ($doDatenSpeichern && $_POST && ! isset($_POST['woche_komplett'])) {
            Log::debug("Woche soll als NICHT KOMPLETT markiert werden");
            $boSollKomplett = false;
        }
        
        // Woche ist gesperrt aber RadioButton für Sperrung ist nicht gesetzt --> Woche wieder entsperren
        if ($doDatenSpeichern && $boGesperrt == false && $boIstKomplett == true && $boSollKomplett == false) {
            Log::debug("Arbeitswoche wird als INKOMPLETT markiert.");
            ArbeitstagUtilities::setBenutzerArbeitswocheInkomplett($woche, $benutzer->getID());
            $boIstKomplett = false;
            // Wenn die Woche gerade entsperrt wurde, dürfen die Daten nicht bearbeitet werden, da keine POST-Daten vorhanden
            $doDatenSpeichern = false;
        }
        
        // Stunden speichern
        if ($doDatenSpeichern) {
            Log::debug("Daten koennen / duerfen gespeichert bzw. geaendert werden.");
            
            unset($_POST['submit']);
            
            $awArbeitswoche = ArbeitswocheUtilities::getOrCreateArbeitswoche($benutzer->getID(), $arWochentageTS[4], $pid);
            // ArbeitswocheUtilities::deletePreviousArbeitswoche($awArbeitswoche);

            $benSollStunden = BenutzerUtilities::getBenutzerSollWochenStunden($benutzer->getID(), $arWochentageTS[4]);
            
            // Urlaub wird neu berechnet
            $awArbeitswoche->setWochenStundenUrlaub(0);
            
            foreach ($_POST as $key => $stunden) {
                if (substr($key, 0, 2) == "TS" && trim($stunden) != "") {

                    // Stundenwert berichtigen
                    $stunden = str_replace(",", ".", $stunden);
                    $stunden = doubleval($stunden);
                    $stunden = abs($stunden); // stellt sicher, dass $stunden positiv ist
                                              
                    // Timestamp parsen
                    $timestamp = substr($key, 2, strpos($key, "_") - 2);
                    
                    // Projekt parsen
                    $pPos = strpos($key, "_P") + 2;
                    $aPos = strpos($key, "_A");
                    $pLength = $aPos - $pPos;
                    $projektID = substr($key, $pPos, $pLength);
                    
                    // Aufgabe parsen
                    $aPos = strpos($key, "_A") + 2;
                    $aufgabeID = substr($key, $aPos);
                    
                    // Arbeitstageinträge zurücksetzen - nur EINMAL(!) VOR(!) dem Speichern der neuen Arbeitstage
                    ArbeitstagUtilities::resetMitarbeiterArbeitstagAufgabe($benutzer->getID(), date("Y-m-d", $timestamp), $projektID, $aufgabeID);
                    
                    // Stunden speichern
                    if (trim($stunden != "")) {
                        $at = ArbeitstagUtilities::speicherNeuenArbeitstag($timestamp, $awArbeitswoche->getID(), $benutzer->getID(), $projektID, $aufgabeID, $stunden, $benSollStunden[Date::getTagDerWoche($timestamp)], $boSollKomplett);
                        $awArbeitswoche->addArbeitstag($at);
                    }
                }
                unset($_POST['woche_komplett']);
            }
            
            // Reisekosten speichern
            $kmKosten = WaehrungUtil::formatWaehrungToDB($_POST['km']) * ConstantLoader::getKilometerpauschale();
            $rk = ReisekostenUtilities::getReisekosten($benutzer->getID(), $pid, $kw, $jahr);
            $rk->setKM(WaehrungUtil::formatWaehrungToDB($_POST['km']));
            $rk->setKMKosten($kmKosten);
            $rk->setHotel(WaehrungUtil::formatWaehrungToDB($_POST['hotel']));
            $rk->setSpesen(WaehrungUtil::formatWaehrungToDB($_POST['spesen']));
            $rk->speichern(false);
            
            $awArbeitswoche->setEingabeKomplett($boSollKomplett);
            $awArbeitswoche->summieren();
            $awArbeitswoche->speichern(true);
            
            // Ist Stunden neu berechnen
            $istStunden = ArbeitstagUtilities::berechneSummeWochenIstStunden($woche, $benutzer->getID());
            $awArbeitswoche->setWochenStundenIst($istStunden);
            $awArbeitswoche->setWochenStundenDif($awArbeitswoche->getWochenStundenIst() - $awArbeitswoche->getWochenStundenSoll());
            $awArbeitswoche->speichern(true);
        } else {
            Log::debug("Daten werden nicht gespeichert.");
        }
        
        // Nach den Inserts nochmal die Statusvariablen aktualisieren
        $boIstKomplett = ArbeitstagUtilities::isBenutzerArbeitswocheKomplett($woche, $benutzer->getID());
        
        // Projektausgabe
        $c = ZeiterfassungUtilities::getBenutzerProjektAufgaben($benutzer->getID(), $pid);
        $tplDS = new BufferedTemplate("benutzer_zeit_ds.tpl", "cssklasse", "td1", "td2");
        
        $tmpProj = "";
        $tmpHaupt = "";
        $wochentagsStunden = array(
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0
        );
        
        // Ermittelt alle Arbeitsstunden des Benutzers als Array, um Performance zu schonen
        $atX = ArbeitstagUtilities::getMitarbeiterZeitraumStundeAsArray($benutzer->getID(), date("Y-m-d", $arWochentageTS[0]), date("Y-m-d", $arWochentageTS[6]));
        
        foreach ($c as $z) {
            if (! $gemeindeCache->containsKey($z->getGemeindeID())) {
                $gemeindeCache->put($z->getGemeindeID(), new Gemeinde($z->getGemeindeID()));
            }
            if ($tmpProj != $z->getProjektBezeichnung()) {
                $tplDSRubrik->replace("ProjektBezeichnung", $gemeindeCache->getValueOf($z->getGemeindeID())
                    ->getKirche() . ", " . $z->getProjektBezeichnung());
                $tplDS->addToBuffer($tplDSRubrik);
                $tplDSRubrik->forget();
                
                $tmpHaupt = null;
            }
            $tplDS->replace("ProjektBezeichnung", "");
            
            if ($tmpHaupt != $z->getHauptaufgabeBezeichnung())
                $tplDS->replace("Hauptaufgabe", $z->getHauptaufgabeBezeichnung());
            $tplDS->replace("Hauptaufgabe", "");
            
            $tplDS->replace("Unteraufgabe", $z->getUnteraufgabeBezeichnung());
            $tplDS->replace("UAID", $z->getUnteraufgabeID());
            $tplDS->replace("PID", $z->getProjektID());
            
            // Wochentage durchgehen
            for ($i = 0; $i < 7; $i ++) {
                $tplDS->replace("TS" . $i, $arWochentageTS[$i]);
                $tplDS->replace("Datum" . $i, date("Y-m-d", $arWochentageTS[$i]));
            }
            
            // Benutzerstunden / Arbeitstage aus der Datenbank laden
            $iStunden = 0;
            $tmpArrayIndex = date("Y-m-d", $arWochentageTS[0]);
            if (isset($atX[$tmpArrayIndex], $atX[$tmpArrayIndex][$z->getProjektID()], $atX[$tmpArrayIndex][$z->getProjektID()][$z->getUnteraufgabeID()])) {
                $at = $atX[$tmpArrayIndex][$z->getProjektID()][$z->getUnteraufgabeID()];
                foreach ($at as $arbeitstag) {
                    $s = "D" . $arbeitstag['at_datum'] . "_P" . $arbeitstag['proj_id'] . "_A" . $arbeitstag['au_id'];
                    $tplDS->replace($s, $arbeitstag['at_stunden_ist']);
                    $iStunden += $arbeitstag['at_stunden_ist'];
                    $wochentagsStunden[date("w", strtotime($arbeitstag['at_datum']))] += $arbeitstag['at_stunden_ist'];
                }
            }
            $tplDS->replace("summe_" . $z->getProjektID() . "_" . $z->getUnteraufgabeID(), $iStunden == 0 ? $iStunden = "" : $iStunden);
            $wochentagsStunden[7] += $iStunden;
            
            // Felder mit überflüssigen Platzhaltern leeren
            for ($i = 0; $i < 7; $i ++) {
                $s = "D" . date("Y-m-d", $arWochentageTS[$i]) . "_P" . $z->getProjektID() . "_A" . $z->getUnteraufgabeID();
                $tplDS->replace($s, "");
            }
            
            $tplDS->next();
            
            $tmpProj = $z->getProjektBezeichnung();
            $tmpHaupt = $z->getHauptaufgabeBezeichnung();
        }
        
        // Reisekosten Felder leeren
        $rk = ReisekostenUtilities::getReisekosten($benutzer->getID(), $pid, $kw, $jahr);
        $tpl->replace("Spesen", WaehrungUtil::formatDoubleToWaehrung($rk->getSpesen()));
        $tpl->replace("Hotel", WaehrungUtil::formatDoubleToWaehrung($rk->getHotel()));
        $tpl->replace("KM", WaehrungUtil::formatDoubleToWaehrung($rk->getKM()));
        $tpl->replace("KMKosten", WaehrungUtil::formatDoubleToWaehrung($rk->getKMKosten()));
        $tpl->replace("RK", WaehrungUtil::formatDoubleToWaehrung($rk->getGesamt()));
        
        foreach ($wochentagsStunden as $key => $val) {
            $tpl->replace("Summe" . $key, $val == 0 ? $val = "" : $val);
        }
        
        $tpl->replace("SummeAlleProjekte", $bisherStundenSumme);
        $tpl->replace("Datensaetze", $tplDS->getOutput());
        
        // Datensatz / Arbeitswoche gesperrt, dann Eingabemoeglichtkeit sperren
        if ($boGesperrt || $boIstKomplett)
            $tpl->replace("Disabled", "disabled class=\"disabled\"");
        $tpl->replace("Disabled", "");
        
        // Komplett-Button managen
        if ($boGesperrt) {
            $tpl->replace("WocheKomplettDisabled", "disabled");
            $tpl->replace("SpeichernDisabled", "disabled");
        }
        $tpl->replace("SpeichernDisabled", "");
        $tpl->replace("WocheKomplettDisabled", "");
        
        if ($boIstKomplett)
            $tpl->replace("WocheKomplettChecked", Constant::$HTML_CHECKED_CHECKED);
        $tpl->replace("WocheKomplettChecked", "");
        
        // Template fuellen
        if ($tplStatus != null)
            $tpl->replace("HTMLStatus", $tplStatus->getOutput());
        $tpl->replace("HTMLStatus", "");
        $tpl->replace("WochenStart", $arWochentage[0]);
        $tpl->replace("WochenEnde", $arWochentage[6]);
        $tpl->replace("Kalenderwoche", date("W", $woche));
        $tpl->replace("Benutzername", $benutzer->getVorname() . " " . $benutzer->getNachname());
        $tpl->anzeigen();
        
        BenutzerUtilities::berechneUeberstunden($benutzer->getID());
    }

    public static function doHilfeRufen()
    {
        global $webUser;
        
        $firmenAnschrift = new Ansprechpartner(1);
        
        $sekunden = 1;
        if ($_POST['nachricht'] != ConstantLoader::getAdminNachrichtenHoverText()) {
            $titel = INSTALLATION_NAME . ": Hilferuf!";
            $msg = "";
            $msg .= "Adresse: " . $_POST['help_addr'] . "\r\n";
            $msg .= "\r\n";
            $msg .= "Datum: " . date("d.m.Y H:i:s") . "\r\n";
            $msg .= "\r\n";
            $msg .= "Nachricht: " . $_POST['nachricht'] . "\r\n";
            $msg .= "\r\n";
            $msg .= "Benutzer: " . $webUser->getBenutzername() . " \r\n";
            $msg .= "\r\n";
            $msg .= "Umgebungsvariabel:\r\n";
            $msg .= "\tPOST: " . print_r($_POST['help_post'], true) . "\r\n";
            
            $htmlStatus = new HTMLStatus("Nachricht wurde verschickt.", 2);
            $header = "from:" . SUPPORT_MAIL_FROM . "\r\n";
            if ($firmenAnschrift->getEmail() != "") {
                $header .= "Reply-To: " . $firmenAnschrift->getEmail() . "\r\n";
            } else {}
            @mail(SUPPORT_MAIL_ADDR, $titel, $msg, $header);
        } else {
            $sekunden = 3;
            $htmlStatus = new HTMLStatus("Sie haben keinen Text eingegeben!", 1);
        }
        $html = new HTMLRedirect($htmlStatus->getOutput(), $_POST['help_addr'], $sekunden);
        $html->anzeigen();
    }
}

?>