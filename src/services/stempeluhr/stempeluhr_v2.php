<?php
use Sabre\VObject\DateTimeParser;

include "../../../conf/config.inc.php";
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
        
        $messages[] = "Guten Morgen ". $benutzer->getVorname()."! Einen guten Start in den Tag! Du musst noch 30 Stunden diese Woche buchen.";
        $messages[] = "Mahlzeit ". $benutzer->getVorname()."!  Du musst noch 2 Stunden diese Woche buchen.";
        $messages[] = "Hallo ". $benutzer->getVorname()."!  Du musst noch 9 Stunden diese Woche buchen.";
        $retVal['msg'] = $messages[rand(0,2)];
    } else if ($_GET['action'] == "projekte") {
        $cProjekte = ProjektUtilities::getProjekte();
        $projekte = array();
        foreach ($cProjekte as $currentProjekt) {
            $gemeinde = new Gemeinde($currentProjekt->getGemeindeId());
            $projekte[$currentProjekt->getId()] = array("bezeichnung" => $currentProjekt->getBezeichnung(), "ort" => $gemeinde->getKirche());
        }
        
        $retVal = array(
            "anzahl" => $cProjekte->getSize(),
            "projekte" => $projekte
        );
    } else if (isset($_GET['projektId']) && $_GET['action'] == "unteraufgaben") {
        $cAufgaben = ProjektAufgabeUtilities::getAlleProjektAufgaben($_GET['projektId']);
        $retVal = array();
        foreach ($cAufgaben as $currentAufgabe) {
            $retValUnteraufgaben = array();
            $cUnteraufgaben = AufgabeUtilities::loadChildrenAufgaben($currentAufgabe->getId());
            
            foreach ($cUnteraufgaben as $currentUnteraufgabe) {
                $retValUnteraufgaben[$currentUnteraufgabe->getId()] = $currentUnteraufgabe->getBezeichnung();
            }
            
            $retVal[] = array(
                "id" => $currentAufgabe->getId(),
                "bezeichnung" => $currentAufgabe->getBezeichnung(),
                "unteraufgaben" => $retValUnteraufgaben
            );
        }
    } else if (isset($_GET['projektId']) && $_GET['action'] == "unteraufgabedatum") {
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
            $retVal[$plainFormatteddate] = $derTagAlsText;
        }
    } else if (isset($_GET['projektId'], $_GET['aufgabeId']) && $_GET['action'] == "aufgabedatumdetails") {
        $unteraufgabe = $_GET['aufgabeId'];
        $kalenderjahr = date("Y");
        $tage = ArbeitstagUtilities::getMitarbeiterArbeitstagProUnteraufgabe($benutzer->getID(), $unteraufgabe, $kalenderjahr);
        $retVal = $tage; 
    } else if ($_GET['action'] == "logout") {
        $retVal = array(
            "status" => "ok",
            "vorname" => $benutzer->getVorname(),
            "login" => date("h:i", strtotime("-8 hours")),
            "logout" => date("d.m.Y H:i"),
            "duration" => "8 Stunden"
        );
    } else if (isset($_GET['unteraufgabeId'], $_GET['projektId'], $_GET['datum'], $_GET['stunden']) && $_GET['action'] == "login") {
        $timestamp = strtotime($_GET['datum']);
        $projektId = $_GET['projektId'];
        $aufgabeId = $_GET['unteraufgabeId'];
        $stunden = $_GET['stunden'];
        $kommentar = $_GET['kommentar'];
        
        $benSollStunden = BenutzerUtilities::getBenutzerSollWochenStunden($benutzer->getID(), $timestamp);
        $awArbeitswoche = ArbeitswocheUtilities::getOrCreateArbeitswoche($benutzer->getID(), $timestamp, $projektId);
        $arbeitstag = ArbeitstagUtilities::getMitarbeiterProjektAufgabenArbeitstag($benutzer->getID(), $projektId, $aufgabeId , $timestamp);
        
        if($arbeitstag == null) {
            $arbeitstag = ArbeitstagUtilities::speicherNeuenArbeitstag($timestamp, $awArbeitswoche->getID(), $benutzer->getID(), $projektId, $aufgabeId, $stunden, $benSollStunden[Date::getTagDerWoche($timestamp)], false);
        } else {
            $arbeitstag->setIstStunden($stunden);
        }
        $arbeitstag->setKommentar($kommentar);
        $arbeitstag->speichern(true);
        
        // neu
        $awArbeitswoche->addArbeitstag($arbeitstag);
        
        $istStunden = ArbeitstagUtilities::berechneSummeWochenIstStunden($timestamp, $benutzer->getID());
        $awArbeitswoche->setWochenStundenIst($istStunden);
        $awArbeitswoche->setWochenStundenDif($awArbeitswoche->getWochenStundenIst() - $awArbeitswoche->getWochenStundenSoll());

        $awArbeitswoche->speichern(true);
        if($awArbeitswoche->getWochenStundenDif() == 0) {
            $text = "du hast alle Stunden erfasst.";
        } else if($awArbeitswoche->getWochenStundenDif() < 0) {
            $text = "es fehlen noch ".($awArbeitswoche->getWochenStundenDif() * -1)." Stunden diese Woche";
        } else {
            $text = "du hast diese Woche bereits " . $awArbeitswoche->getWochenStundenDif(). " Überstunden.";
        }
        //$eintrag->speichern(true);
        $retVal = array(
            "status" => "ok",
            "text" => $benutzer->getVorname().", ".$text,
            "arbeitstag_id" => $arbeitstag->getID(),
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
echo json_encode($retVal);