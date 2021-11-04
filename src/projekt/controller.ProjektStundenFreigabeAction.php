<?php

class ProjektStundenFreigabeAction implements GetRequestHandler
{

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::validateGetRequest()
     */
    public function validateGetRequest()
    {
        // TODO Auto-generated method stub
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::handleInvalidGet()
     */
    public function handleInvalidGet()
    {
        // TODO Auto-generated method stub
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
        if (! $webUser->isAdmin()) {
            return;
        }
        
        $benutzer = $webUser->getBenutzer();
        $tpl = new Template("projekt_stundenfreigabe.tpl");
        $tpl->replace("Benutzername", $benutzer->getVorname() . " " . $benutzer->getNachname());
        
        // Ab Hier Copy & Paste
        $gemeindeCache = new HashTable();
        $pid = 132;
        $tplDSRubrikFirst = new BufferedTemplate("projekt_stundenfreigabe_rubrik.tpl");
        $tplDSRubrikAfter = new BufferedTemplate("projekt_stundenfreigabe_rubrik_2.tpl");
        $tplDSRubrik = $tplDSRubrikFirst;
        $tplDS = new BufferedTemplate("benutzer_zeit_ds.tpl", "cssklasse", "td1", "td2");
        $bisherStundenSumme = 0;
        
        // Navigation
        if (isset($_SESSION['request']['woche'])) {
            $woche = $_SESSION['request']['woche'];
        } else {
            $woche = time();
            $isWocheChanged = true;
        }
        
        $arWochentage = Date::berechneArbeitswoche($woche);
        $arWochentageHeadline = Date::berechneArbeitswoche($woche, "d.m");
        $arWochentageTechFormat = Date::berechneArbeitswoche($woche, "Y-m-d");
        $arWochentageTS = Date::berechneArbeitswocheTimestamp($woche);
        $kw = date("W", $arWochentageTS[4]); // ISO 8601 Der Donnerstag der Woche ist entscheidend. Problemfall 2019
        $jahr = date("Y", $arWochentageTS[4]); // ISO 8601 Der Donnerstag der Woche ist entscheidend. Problemfall 2019
        
        $tpl->replace("KW", $kw);
        $tpl->replace("Jahr", $jahr);
        $tpl->replace("DatumVon", $arWochentage[0]);
        $tpl->replace("DatumBis", $arWochentage[6]);
        
        $c = ZeiterfassungUtilities::getBenutzerProjektAufgabenImZeitraum($benutzer->getID(), $arWochentageTechFormat[0], $arWochentageTechFormat[6]);
        
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
                
                // Wochentagsausgabe
                for ($i = 0; $i < 7; $i ++) {
                    $feiertagsZusatz = $arWochentageHeadline[$i];
                    
                    $feiertag = Date::getFeiertagsBezeichnung($arWochentage[$i]);
                    if ($feiertag != "") {
                        $feiertagsZusatz = "<span style=\"color: #CE0000;\">" . $feiertagsZusatz . "<br/>" . $feiertag . "</font>";
                    }
                    $tplDSRubrik->replace("Datum" . ($i + 1), $feiertagsZusatz);
                }
                $tplDSRubrik->perceive();
                
                $tplDS->addToBuffer($tplDSRubrik);
                $tplDSRubrik->next();
                
                $tplDSRubrik = $tplDSRubrikAfter;
                
                $tmpHaupt = null;
            }
            $tplDS->replace("ProjektBezeichnung", "");
            
            // Stunden Information START
            if (($z->getSollStunden() - $z->getIstStunden()) < 0) {
                $stundenInfo = "(" . ($z->getSollStunden() - $z->getIstStunden()) . " von " . intval($z->getSollStunden()) . " Std.)";
                $tplDS->replace("cssklasse", "red");
            } else if ($z->getSollStunden() > 0) {
                $stundenInfo = " (" . ($z->getSollStunden() - $z->getIstStunden()) . " von " . intval($z->getSollStunden()) . " Std.)";
            } else {
                // Keine SollStunden fuer dieses Projekt eingegeben
                $stundenInfo = "";
            }
            
            $tplDS->replace("IstStunden", $z->getIstStunden());
            // Stunden Information ENDE
            
            if ($tmpHaupt != $z->getHauptaufgabeBezeichnung()) {
                $tplDS->replace("Hauptaufgabe", $z->getHauptaufgabeBezeichnung());
                $tplDS->replace("StundenInfo", $stundenInfo);
            } else {
                $tplDS->replace("Hauptaufgabe", "");
                $tplDS->replace("StundenInfo", "");
            }
            
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
            if ($iStunden > 0) {
                $wochentagsStunden[7] += $iStunden;
            }
            
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
        
        $tpl->replace("Disabled", "");
        $tpl->replace("FreigebenDisabled", "");
        $tpl->replace("AblehnenDisabled", "");
        
        return $tpl;
    }
}