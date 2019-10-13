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
        
        $messages[] = "Guten Morgen ". $benutzer->getVorname()."! Einen guten Start in den Tag!";
        $messages[] = "Mahlzeit ". $benutzer->getVorname()."! Du bist seit 7 Uhr eingestempelt und hast nun 4.5 Stunden an der Aufgabe 'Gehäusebau' für das Projekt 'Neubau Aachen' gearbeitet";
        $messages[] = "Hallo ". $benutzer->getVorname()."! Du bist seit 7 Uhr eingestempelt und hast nun 8 Stunden an der Aufgabe 'Pfeifen' für das Projekt 'Reinigung Münster' gearbeitet'";
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
    } else if ($_GET['action'] == "logout") {
        $retVal = array(
            "status" => "ok",
            "vorname" => $benutzer->getVorname(),
            "login" => date("h:i", strtotime("-8 hours")),
            "logout" => date("d.m.Y H:i"),
            "duration" => "8 Stunden"
        );
    } else if (isset($_GET['unteraufgabeId'], $_GET['projektId']) && $_GET['action'] == "login") {
        
        // Alten Eintrag updaten
        $letzteStempelzeit = StempeluhrUtilities::hatOffenenStempeluhrEintrag($benutzer->getId());
        $now = new DateTime('NOW');
        $letzteDauer = 0;
        if($letzteStempelzeit != null) {
            $letzteStempelzeit->setStatus(1);
            
            $letzterCheckin = date_create_from_format(DatabaseStorageObjekt::MYSQL_DATETIME_FORMAT, $letzteStempelzeit->getZeit());
            $dauer = $now->diff($letzterCheckin);
            $letzteDauer = $dauer->format('%i');
            $letzteStempelzeit->setDauer($letzteDauer);
            $letzteStempelzeit->speichern(false);
        }
        
        // Neuer Eintrag
        $unteraufgabe = new Aufgabe($_GET['unteraufgabeId']);
        $hauptaufgabe = new Aufgabe($unteraufgabe->getParentID());
        
        $eintrag = new Stempeluhr();
        $eintrag->setProjektId($_GET['projektId']);
        $eintrag->setUnteraufgabeId($unteraufgabe->getId());
        $eintrag->setAufgabeId($hauptaufgabe->getId());
        $eintrag->setZeit($now->format(DatabaseStorageObjekt::MYSQL_DATETIME_FORMAT));
        $eintrag->setMitarbeiterId($benutzer->getID());
        $eintrag->speichern(true);
        $retVal = array(
            "status" => "ok",
            "text" => "Los gehts, " .$benutzer->getVorname()."!",
            "st_id" => $eintrag->getID(),
            "duration_in_minutes" => $letzteDauer
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