<?php
/**
 * Script legt für jeden Mitarbeiter einen Datensatz für die nächste Arbeitswoche an.
 * 
 * Ausführungszeit: Jeden Montag um 0:00 Uhr
 */

// Config einbinden
include "../../conf/config.inc.php";

$ts = time();
$aw = ArbeitswocheUtilities::createArbeitswoche($ts);

$sqlDelete = "DELETE FROM arbeitswoche WHERE aw_kw = " . $aw->getKalenderWoche() . " AND aw_jahr = " . $aw->getJahr();

$sqlInsert = "INSERT INTO 
			arbeitswoche
    		(
    		be_id,
    		aw_wochenstart,
    		aw_kw,
    		aw_jahr,
    		aw_stunden_ist,
    		aw_stunden_soll,
    		aw_stunden_dif,
    		aw_eingabe_komplett,
    		aw_eingabe_moeglich,
    		aw_eingabe_gebucht,
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
					0,
					1,
					0,
					NOW(),
					NOW()
				FROM 
					benutzer b
				WHERE
					b.be_zeiterfassung = 1 AND
					b.be_geloescht = 0 AND
					b.be_aktiviert = 1";

// SQL ausführen
$db = DB::getInstance();
$db->connect();

$db->NonSelectQuery($sqlDelete);
$db->NonSelectQuery($sqlInsert);

$db->disconnect();

?>