<?php
include "../../../conf/config.inc.php";
DB::getInstance()->connect();
if (isset($_GET['action']) && $_GET['action'] == "benutzer") {
    $cBenutzer = BenutzerUtilities::getZeiterfassungsBenutzer();
    $retVal = array();
    foreach ($cBenutzer as $currentBenutzer) {
        $retVal[$currentBenutzer->getId()] = $currentBenutzer->getBenutzername();
    }
} else if (isset($_GET['action'], $_GET['benutzerId'], $_GET['pin'])) {
    $benutzer = new Benutzer($_GET['benutzerId']);
    $benutzer = $benutzer->getBenutzername();
    if (false == BenutzerUtilities::authorisiereBenutzerdaten($benutzer, $_GET['pin'], true)) {
        $retVal = array();
        $retVal['status'] = "error";
    } else if ($_GET['action'] == "pincheck") {
        // Pincheck is successful
        $retVal = array();
        $retVal['status'] = "ok";
    } else if ($_GET['action'] == "projekte") {
        $cProjekte = ProjektUtilities::getProjekte();
        $projekte = array();
        foreach ($cProjekte as $currentProjekt) {
            $gemeinde = new Gemeinde($currentProjekt->getGemeindeId());
            $projekte[$currentProjekt->getId()] = $currentProjekt->getBezeichnung() . ", " . $gemeinde->getKirche();
        }
        
        $retVal = array("anzahl" => $cProjekte->getSize(), "projekte" => $projekte);
    } else if (isset($_GET['projektId']) && $_GET['action'] == "unteraufgaben") {
        $cAufgaben = ProjektAufgabeUtilities::getAlleProjektAufgaben($_GET['projektId']);
        $retVal = array();
        foreach ($cAufgaben as $currentAufgabe) {
            $retValUnteraufgaben = array();
            $cUnteraufgaben = AufgabeUtilities::loadChildrenAufgaben($currentAufgabe->getId());
            
            foreach($cUnteraufgaben as $currentUnteraufgabe) {
                $retValUnteraufgaben[$currentUnteraufgabe->getId()] = $currentUnteraufgabe->getBezeichnung();
            }
            
            $retVal[] = array("id" => $currentAufgabe->getId(), "bezeichnung" => $currentAufgabe->getBezeichnung(), "unteraufgaben" => $retValUnteraufgaben);
        }
    } else if ($_GET['action'] == "logout") {
        $retVal = array(
            "status" => "ok",
            "login" => date("h:i", strtotime("-8 hours")),
            "logout" => date("d.m.Y H:i"),
            "duration" => "8 Stunden"
        );
    } else if (isset($_GET['unteraufgabeId'], $_GET['projektId']) && $_GET['action'] == "login") {
        $retVal = array(
            "text" => "Los gehts!"
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