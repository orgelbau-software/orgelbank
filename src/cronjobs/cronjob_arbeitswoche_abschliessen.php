<?php
/**
 * Script legt für jeden Mitarbeiter einen Datensatz für die nächste Arbeitswoche an.
 * 
 * Ausführungszeit: Jeden Montag um 0:00 Uhr
 */

// Config einbinden
include_once "../../conf/config.inc.php";

$ts = time();
$aw = ArbeitswocheUtilities::createArbeitswoche($ts);

$retVal = array();
$retVal['http_status'] = "200";

try {
    
    // SQL ausführen
    $db = DB::getInstance();
    $db->connect();
    
    // Fertig
    $date = new DateTimeImmutable();
    $newDate = $date->sub(new DateInterval('P14D'));
    
    $sql = "UPDATE arbeitswoche set aw_status = 2 WHERE aw_status = 1 AND aw_wochenstart < '" . $newDate->format('Y-m-d') . "'";
    $retVal['woche_sql_fertig'] = $sql;
    
    $db->NonSelectQuery($sql);
    $retVal['woche_anzahl_status_fertig'] = $db->getAffectedRows();
    
    $sql = "UPDATE arbeitstag as at set at_status = 2 WHERE at_status = 1 AND aw_id = (SELECT aw_id FROM arbeitswoche aw WHERE aw.aw_id = at.aw_id AND aw_status = 2)";
    $retVal['tag_sql_fertig'] = $sql;
    $db->NonSelectQuery($sql);
    $retVal['tag_anzahl_status_fertig'] = $db->getAffectedRows();
    
    // Freigegeben
    $date = new DateTimeImmutable();
    $newDate = $date->sub(new DateInterval('P21D'));
    
    $sql = "UPDATE arbeitswoche set aw_status = 3 WHERE aw_status = 2 AND aw_wochenstart < '" . $newDate->format('Y-m-d') . "'";
    $retVal['woche_sql_freigabe'] = $sql;
    
    $db->NonSelectQuery($sql);
    $retVal['woche_anzahl_status_freigegeben'] = $db->getAffectedRows();
    
    // $angelegteWochen = $db->NonSelectQuery($sqlInsert);
    if ($db->getAffectedRows() == 0) {
        $retVal['http_status'] = "201";
    } else {
        $retVal['http_status'] = "200";
    }
    
    $sql = "UPDATE arbeitstag as at set at_status = 3 WHERE at_status = 2 AND aw_id = (SELECT aw_id FROM arbeitswoche aw WHERE aw.aw_id = at.aw_id AND aw_status = 3)";
    $retVal['tag_sql_freigabe'] = $sql;
    $db->NonSelectQuery($sql);
    $retVal['tag_anzahl_status_freigegeben'] = $db->getAffectedRows();
    
    $db->disconnect();
} catch (Throwable $t) {
    $retVal['http_status'] = "500";
    $retVal['message'] = $t->getMessage();
    BenutzerController::doHilfeRufenCronjob($retVal);
}
header('Content-Type: application/json');
http_response_code($retVal['http_status']);
echo json_encode($retVal);
?>