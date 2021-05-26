<?php
use Sabre\VObject\DateTimeParser;

include "../../../conf/config.inc.php";
$httpResponseCode = 200;
DB::getInstance()->connect();
if (isset($_GET['action']) && $_GET['action'] == "benutzer") {
    $cBenutzer = BenutzerUtilities::getZeiterfassungsBenutzer();
    $retVal = array();
    foreach ($cBenutzer as $currentBenutzer) {
        $retVal[$currentBenutzer->getId()] = $currentBenutzer->getBenutzername();
    }
} else if (isset($_GET['action'], $_GET['pin'])) {
    $benutzer = BenutzerUtilities::loadByPin($_GET['pin'], true);
    if (false == $benutzer) {
        $retVal = array();
        $retVal['status'] = "error";
    } else if ($_GET['action'] == "pincheck") {
        // Pincheck is successful
        $retVal = array();
        $retVal['status'] = "ok";
        
        $datum = time();
        
        $benSollStunden = BenutzerUtilities::getBenutzerSollWochenStunden($benutzer->getID(), $datum);
        $tmpSollStundenAbzglFeiertage = 0;
        foreach ($benSollStunden as $key => $val) {
            $tmpSollStundenAbzglFeiertage += $val;
        }
        $istStunden = ArbeitstagUtilities::berechneSummeWochenIstStunden($datum, $benutzer->getID());
        
        if($benSollStunden == $istStunden) {
            $text = "Du hast alle Stunden erfasst.";
        } else if($tmpSollStundenAbzglFeiertage > $istStunden) {
            $text = "Es fehlen noch ".($tmpSollStundenAbzglFeiertage - $istStunden)." Stunde(n) diese Woche";
        } else {
            $text = "Du hast diese Woche bereits " . ($istStunden - $tmpSollStundenAbzglFeiertage ). " Überstunde(n).";
        }
        
        $retVal['msg'] = "Hallo ". $benutzer->getVorname()."! ". $text;
    } else if ($_GET['action'] == "projekte") {
        $cProjekte = ProjektUtilities::getProjekte();
        $projekte = array();
        
        if(isset($_GET['datum'])) {
            $datum = strtotime($_GET['datum']);
        } else {
            $datum = time(); // Heute
        }
        
        foreach ($cProjekte as $currentProjekt) {
            $gemeinde = new Gemeinde($currentProjekt->getGemeindeId());
            $istStunden = ArbeitstagUtilities::berechneSummeWochenIstStundenProProjekt($datum, $benutzer->getID(), $currentProjekt->getID());
            $istStunden = ($istStunden == null ? 0 : $istStunden);
            $projekte[$currentProjekt->getId()] = array("bezeichnung" => $currentProjekt->getBezeichnung(), "ort" => $gemeinde->getKirche(), "istStunden" => $istStunden);
        }
        
        $retVal = array(
            "anzahl" => $cProjekte->getSize(),
            "projekte" => $projekte
        );
    } else if (isset($_GET['projektId']) && $_GET['action'] == "unteraufgaben") {
        $cAufgaben = ProjektAufgabeUtilities::getAlleProjektAufgaben($_GET['projektId']);
        $retVal = array();
        
        if(isset($_GET['datum'])) {
            $datum =strtotime($_GET['datum']);
        } else {
            $datum = time();
        }
        
        foreach ($cAufgaben as $currentAufgabe) {
            $retValUnteraufgaben = array();
            $cUnteraufgaben = AufgabeUtilities::loadChildrenAufgaben($currentAufgabe->getId());
            
            foreach ($cUnteraufgaben as $currentUnteraufgabe) {
                $istStunden = ArbeitstagUtilities::berechneSummeWochenIstStundenProProjektAufgabe($datum, $benutzer->getID(), $_GET['projektId'], $currentUnteraufgabe->getID());
                $istStunden = ($istStunden == null ? "0" : $istStunden);
                $retValUnteraufgaben[$currentUnteraufgabe->getId()] = array("id" => $currentUnteraufgabe->getId(), "bezeichnung" => $currentUnteraufgabe->getBezeichnung(), "wochenIstStd" => $istStunden);
            }
            
            $retVal[] = array(
                "id" => $currentAufgabe->getId(),
                "bezeichnung" => $currentAufgabe->getBezeichnung(),
                "unteraufgaben" => $retValUnteraufgaben
            );
        }
    } else if (isset($_GET['projektId'], $_GET['aufgabeId']) && $_GET['action'] == "datumsauswahl") {
        $retVal = array();
        setlocale(LC_TIME, "de_DE");
        for($i = -14; $i <= 7; $i++) {
            $theDate = strtotime($i." days");
            $plainFormatteddate = strftime("%Y-%m-%d", $theDate);
            $derTagAlsText = strftime("%a., %d.%m.%y", $theDate);
            if($i == -1) {
                $derTagAlsText = "Gestern, ".$derTagAlsText;
            } else if($i == 0) {
                $derTagAlsText = "Heute, ".$derTagAlsText;
            } else if($i == 1) {
                $derTagAlsText = "Morgen, ".$derTagAlsText;
            }
            
            // 
            $istStunden = ArbeitstagUtilities::getMitarbeiterProjektAufgabenArbeitstag($benutzer->getID(), $_GET['projektId'], $_GET['aufgabeId'], $plainFormatteddate);
            $istStunden = ($istStunden == null ? "" : " (".$istStunden->getIstStunden()." Std.)");
            $retVal[$plainFormatteddate] = $derTagAlsText.$istStunden;
        }
    } else if (isset($_GET['projektId'], $_GET['aufgabeId'], $_GET['datum']) && $_GET['action'] == "arbeitstag") {
        $unteraufgabe = $_GET['aufgabeId'];
        $datum = $_GET['datum'];
        $projektId = $_GET['projektId'];
        
        $arbeitstag = ArbeitstagUtilities::getMitarbeiterProjektAufgabenArbeitstag($benutzer->getID(), $projektId, $unteraufgabe , $datum);
        if($arbeitstag == null) {
            $retVal['stunden'] = ""; // leer, nicht 0
            $retVal['kommentar'] = "";
        } else {
            $retVal['stunden'] = $arbeitstag->getIstStunden();
            $retVal['kommentar'] = $arbeitstag->getKommentar();
        }
    } else if ($_GET['action'] == "logout") {
        $retVal = array(
            "status" => "ok",
            "vorname" => $benutzer->getVorname(),
            "login" => date("h:i", strtotime("-8 hours")),
            "logout" => date("d.m.Y H:i"),
            "duration" => "8 Stunden"
        );
    } else if (isset($_GET['unteraufgabeId'], $_GET['projektId'], $_GET['datum'], $_GET['stunden']) && $_GET['action'] == "buchen") {
        $timestamp = strtotime($_GET['datum']);
        $projektId = $_GET['projektId'];
        $aufgabeId = $_GET['unteraufgabeId'];
        $stunden = $_GET['stunden'];
        $kommentar = $_GET['kommentar'];
        
        $benSollStunden = BenutzerUtilities::getBenutzerSollWochenStunden($benutzer->getID(), $timestamp);
        $awArbeitswoche = ArbeitswocheUtilities::getOrCreateArbeitswoche($benutzer->getID(), $timestamp, $projektId);
        
        $projekt = new Projekt($projektId);
        $projektStart = strtotime($projekt->getStart());
        $projektEnde = strtotime($projekt->getEnde());
        if($timestamp < $projektStart || $timestamp > $projektEnde) {
            $status = "error";
            $text = "Auf dieses Projekt kann nur vom ".$projekt->getStart(true). " bis ".$projekt->getEnde(true)." gebucht werden.";
            $arbeitstagId = "error";
            $httpResponseCode = 406;
        } else if($awArbeitswoche->getEingabeKomplett() == 0) { 
            $arbeitstag = ArbeitstagUtilities::getMitarbeiterProjektAufgabenArbeitstag($benutzer->getID(), $projektId, $aufgabeId , $timestamp);
            $arbeitstagId = "";
            if($stunden > 0) {
                if($arbeitstag == null) {
                    $arbeitstag = ArbeitstagUtilities::speicherNeuenArbeitstag($timestamp, $awArbeitswoche->getID(), $benutzer->getID(), $projektId, $aufgabeId, $stunden, $benSollStunden[Date::getTagDerWoche($timestamp)], false);
                } else {
                    $arbeitstag->setIstStunden($stunden);
                }
                $arbeitstag->setKommentar($kommentar);
                $arbeitstag->speichern(true);
                $arbeitstagId = $arbeitstag->getID();
                // neu
                $awArbeitswoche->addArbeitstag($arbeitstag);
            } else if($arbeitstag != null) {
                // die stunden auf 0 gesetzt, der Tag wird geloescht
                ArbeitstagUtilities::resetMitarbeiterProjektAufgabeArbeitstag($benutzer->getID(), $projektId, $aufgabeId , $timestamp);
                $arbeitstagId = "deleted";
            }
            $istStunden = ArbeitstagUtilities::berechneSummeWochenIstStunden($timestamp, $benutzer->getID());
            $awArbeitswoche->setWochenStundenIst($istStunden);
            $awArbeitswoche->setWochenStundenDif($awArbeitswoche->getWochenStundenIst() - $awArbeitswoche->getWochenStundenSoll());

            $awArbeitswoche->speichern(true);
            if($awArbeitswoche->getWochenStundenDif() == 0) {
                $text = "du hast alle Stunden erfasst.";
            } else if($awArbeitswoche->getWochenStundenDif() < 0) {
                $text = "es fehlen noch ".($awArbeitswoche->getWochenStundenDif() * -1)." Stunde(n) diese Woche";
            } else {
                $text = "du hast diese Woche bereits " . $awArbeitswoche->getWochenStundenDif(). " Überstunde(n).";
            }
            $status = "ok";
            $httpResponseCode = 201;
        } else {
            $status = "error";
            $text = "Für diese Arbeitswoche ist die Eingabe bereits gesperrt.";    
            $arbeitstagId = "error";
            $httpResponseCode = 403;
        }
        //$eintrag->speichern(true);
        $retVal = array(
            "status" => $status,
            "text" => $benutzer->getVorname().", ".$text,
            "arbeitstag_id" => $arbeitstagId,
            "arbeitswoche_id" => $awArbeitswoche->getID()
        );
    } else {
        $retVal = array(
            "status" => "failed"
        );
    }
} else {
    $retVal = array(
        "status" => "failed"
    );
}
DB::getInstance()->disconnect();
header('Content-Type: application/json');
http_response_code($httpResponseCode);
echo json_encode($retVal);