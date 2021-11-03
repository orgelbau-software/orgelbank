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
        $tplDSRubrik = new BufferedTemplate("projekt_stundenfreigabe_rubrik.tpl");
        $c = ZeiterfassungUtilities::getBenutzerProjektAufgaben($benutzer->getID(), $pid);
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
        $arWochentageTS = Date::berechneArbeitswocheTimestamp($woche);
        $kw = date("W", $arWochentageTS[4]); // ISO 8601 Der Donnerstag der Woche ist entscheidend. Problemfall 2019
        $jahr = date("Y", $arWochentageTS[4]); // ISO 8601 Der Donnerstag der Woche ist entscheidend. Problemfall 2019
        
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
        
        return $tpl;
    }
}