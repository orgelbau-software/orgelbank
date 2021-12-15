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
    $sqlDelete = "DELETE FROM arbeitswoche WHERE aw_kw = " . $aw->getKalenderWoche() . " AND aw_jahr = " . $aw->getJahr();
//     $retVal['delete'] = $sqlDelete;
    
    $sqlInsert = "INSERT IGNORE INTO 
			arbeitswoche
    		(
    		be_id,
    		aw_wochenstart,
    		aw_kw,
    		aw_jahr,
    		aw_stunden_ist,
    		aw_stunden_soll,
    		aw_stunden_dif,
    		aw_status,
    		aw_lastchange,
    		aw_createdate
    		)
				SELECT
					b.be_id,
					'" . $aw->getWochenStart() . "',
					" . $aw->getKalenderWoche() . ",
					" . $aw->getJahr() . ",
					0,
					b.be_std_gesamt,
					b.be_std_gesamt * -1,
					1,
					NOW(),
					NOW()
				FROM 
					benutzer b
				WHERE
					b.be_zeiterfassung = 1 AND
					b.be_geloescht = 0 AND
					b.be_aktiviert = 1";
    
    $retVal['insert'] = $sqlInsert;
    
    // SQL ausführen
    $db = DB::getInstance();
    $db->connect();
    
//     $db->NonSelectQuery($sqlDelete);
//     $retVal['geloeschte_eintraege'] = $db->getAffectedRows();
    $angelegteWochen = $db->NonSelectQuery($sqlInsert);
    if($db->getAffectedRows() == 0) {
        $retVal['http_status'] = "201";
    } else {
        $retVal['http_status'] = "200";
    }
    $retVal['angelegte_eintraege'] = $db->getAffectedRows();
    
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