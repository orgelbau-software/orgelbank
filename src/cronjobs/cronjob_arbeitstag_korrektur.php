<?php
/**
 * Ausführungszeit: Jeden Montag um 0:00 Uhr
 */

// Config einbinden
include_once "../../conf/config.inc.php";

$retVal = array();
$retVal['http_status'] = "500";
$retVal['message'] = "";

try {
    // SQL ausführen
    $db = DB::getInstance();
    $db->connect();
    
    $sql = "SELECT * FROM arbeitswoche";
    $r = $db->SelectQuery($sql);
    if($r != null && count($r) > 0) {
        foreach ($r as $obj) {
            $berechnet = ArbeitstagUtilities::berechneSummeWochenIstStunden(strtotime($obj['aw_wochenstart']), $obj['be_id']);
            $gespeichert = $obj['aw_stunden_ist'];
            if ($berechnet != $gespeichert && ! ($berechnet == "" && $gespeichert == 0)) {
                $sql = "UPDATE arbeitswoche SET aw_stunden_ist = " . $berechnet . ", aw_stunden_dif = aw_stunden_soll - " . $berechnet . " WHERE aw_id = " . $obj['aw_id'] . ";";
                
                $msg = "Gespeichert: " . $gespeichert . ", Berechnet: " . $berechnet . ", BenutzerId: " . $obj['be_id'] . ", Wochenstart: " . $obj['aw_wochenstart'];
                $retVal[] = array(
                    "sql" => $sql,
                    "msg",
                    $msg
                );
                
                $db->NonSelectQuery($sql);
            }
        }
    }
    $db->disconnect();
    
    $retVal['http_status'] = "200";
    $retVal['message'] = "OK";
} catch(Throwable $t) {
    $retVal['http_status'] = "500";
    $retVal['message'] = $t->getMessage();
    BenutzerController::doHilfeRufenCronjob($retVal);
}
header('Content-Type: application/json');
http_response_code($retVal['http_status']);
echo json_encode($retVal);
?>