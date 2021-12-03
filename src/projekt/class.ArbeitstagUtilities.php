<?php

class ArbeitstagUtilities
{

    private static $dbInstance;

    /**
     * Im Rahmen der mobilen Zeiterfassung hierhin ausgelagert.
     */
    public static function speicherNeuenArbeitstag($pTimeStamp, $awArbeitswocheId, $pBenutzerID, $pProjektID, $pAufgabeID, $pIstStunden, $pSollStunden, $pEingabeKomplett)
    {
        $at = new Arbeitstag();
        $at->setArbeitswocheID($awArbeitswocheId);
        $at->setAufgabeID($pAufgabeID);
        $at->setBenutzerID($pBenutzerID);
        $at->setDatum(date("Y-m-d", $pTimeStamp));
        $at->setProjektID($pProjektID);
        $at->setIstStunden($pIstStunden);
        
        $date = new Date();
        if ($date->isFeiertag($pTimeStamp)) {
            $at->setSollStunden(0);
        } else {
            $at->setSollStunden($pSollStunden);
        }
        
        if ($pEingabeKomplett) {
            Log::debug("Setze Arbeitstag KOMPLETT: " . $at->getDatum());
            $at->markKomplett();
        } else {
            Log::debug("Setze Arbeitstag NICHT KOMPLETT: " . $at->getDatum());
            $at->markOffen();
        }
        
        $at->speichern(true);
        return $at;
    }

    public static function getMitarbeiterArbeitstagProUnteraufgabe($pBenutzerId, $pUnteraufgabeId, $pKalenderjahr)
    {
        $sql = "SELECT
					*
				FROM
					arbeitstag
				WHERE
					be_id = " . $pBenutzerId . " AND (";
        $length = count($pUnteraufgabeId);
        for ($i = 0; $i < $length; $i ++) {
            $sql .= "au_id = " . $pUnteraufgabeId[$i];
            if ($i + 1 != $length) {
                $sql .= " OR ";
            }
        }
        
        $sql .= ") AND at_datum between CAST('" . $pKalenderjahr . "-01-01' AS DATE) AND CAST('" . $pKalenderjahr . "-12-31' AS DATE)";
        echo $sql;
        $res = ArbeitstagUtilities::queryDB($sql);
        return $res;
    }

    /**
     * Ermittelt alle Arbeitsstunden eines Benutzer in einem bestimmten Zeitraum
     *
     * @param int $benutzerID            
     * @param date $wochenStart            
     * @param date $wochenEnde            
     * @return Array
     */
    public static function getMitarbeiterZeitraumStundeAsArray($benutzerID, $wochenStart, $wochenEnde)
    {
        $sql = "SELECT
					*
				FROM
					arbeitstag
				WHERE
					be_id = " . $benutzerID . " AND
					at_datum >= '" . $wochenStart . "' AND 
					at_datum <= '" . $wochenEnde . "'";
        $res = ArbeitstagUtilities::queryDB($sql);
        $retVal = array();
        if ($res != null) {
            foreach ($res as $curr) {
                $retVal[$wochenStart][$curr->getProjektID()][$curr->getAufgabeID()][] = array(
                    "proj_id" => $curr->getProjektID(),
                    "au_id" => $curr->getAufgabeID(),
                    "at_datum" => $curr->getDatum(),
                    "at_stunden_ist" => $curr->getIstStunden()
                );
            }
        }
        return $retVal;
    }

    /**
     *
     * Ermittelt die geleisteten Ist-Stunden pro Mitarbeiter in einem bestimmten Zeitraum
     *
     * @param int $benutzerID            
     * @param
     *            SQL Date $wochenStart
     * @param
     *            SQL Date $wochenEnde
     * @return Array mit Key = ProjektID, Value=Summe der Stunden
     */
    public static function getMitarbeiterZeitraumStundenProProjekt($benutzerID, $wochenStart, $wochenEnde)
    {
        $sql = "SELECT
					sum(at_stunden_ist) as summe,
					proj_id
				FROM
					arbeitstag
				WHERE
					be_id = " . $benutzerID . " AND
					at_datum >= '" . $wochenStart . "' AND 
					at_datum <= '" . $wochenEnde . "'
				GROUP BY
					proj_id";
        $res = DB::getInstance()->SelectQuery($sql);
        $retVal = array();
        if ($res != null && $res !== false) {
            foreach ($res as $curr) {
                $retVal[$curr['proj_id']] = $curr['summe'];
            }
        }
        return $retVal;
    }

    public static function markBenutzerArbeitswocheOffen($timestamp, $benutzerID)
    {
        return self::changeBenutzerArbeitswocheStatus($timestamp, $benutzerID, Arbeitstag::$STATUS_OFFEN);
    }

    public static function markBenutzerArbeitswocheKomplett($timestamp, $benutzerID)
    {
        return self::changeBenutzerArbeitswocheStatus($timestamp, $benutzerID, Arbeitstag::$STATUS_KOMPLETT);
    }

    public static function markBenutzerArbeitswocheGebucht($timestamp, $benutzerID)
    {
        return self::changeBenutzerArbeitswocheStatus($timestamp, $benutzerID, Arbeitstag::$STATUS_GEBUCHT);
    }

    private static function changeBenutzerArbeitswocheStatus($timestamp, $benutzerID, $status)
    {
        $c = Date::berechneArbeitswoche($timestamp, "Y-m-d");
        $sql = "UPDATE
					arbeitstag
				SET
					at_status = " . $status . "
				WHERE
					be_id = " . $benutzerID . " AND
					at_datum >= '" . $c['0'] . "' AND
					at_datum <= '" . $c['6'] . "'";
        DB::getInstance()->NonSelectQuery($sql);
        
        $sql = "UPDATE 
                    arbeitswoche 
                SET 
                    aw_status = ".$status." 
                WHERE 
                    be_id = ".$benutzerID." AND
                    aw_wochenstart = '" . $c['0'] . "'";
        DB::getInstance()->NonSelectQuery($sql);
    }

    public static function isArbeitswocheGebucht($timestamp)
    {
        $c = Date::berechneArbeitswoche($timestamp, "Y-m-d");
        $sql = "SELECT 
					* 
				FROM 
					arbeitstag 
				WHERE 
					at_datum >= '" . $c[0] . "' AND 
					at_datum <= '" . $c[6] . "' AND 
					at_status = " . Arbeitstag::$STATUS_GEBUCHT;
        if (DB::getInstance()->getMysqlNumRows($sql) > 0)
            return true;
        return false;
    }

    public static function isBenutzerArbeitswocheGebucht($timestamp, $benutzerID)
    {
        return self::isBenutzerArbeitswocheInStatus($timestamp, $benutzerID, Arbeitstag::$STATUS_GEBUCHT);
    }

    public static function isBenutzerArbeitswocheKomplett($timestamp, $benutzerID)
    {
        return self::isBenutzerArbeitswocheInStatus($timestamp, $benutzerID, Arbeitstag::$STATUS_KOMPLETT);
    }

    private static function isBenutzerArbeitswocheInStatus($timestamp, $benutzerID, $iStatus)
    {
        $c = Date::berechneArbeitswoche($timestamp, "Y-m-d");
        $sql = "SELECT
					*
				FROM
					arbeitstag
				WHERE
					be_id = " . $benutzerID . " AND
					at_status = " . $iStatus . " AND
					at_datum >= '" . $c[0] . "' AND
					at_datum <= '" . $c[6] . "'";
        if (DB::getInstance()->getMysqlNumRows($sql) > 0) {
            return true;
        }
        return false;
    }

    public static function loadArbeitstagByTimestamp($timestamp, $benutzerID)
    {
        $sql = "SELECT 
					*
				FROM
					arbeitstag
				WHERE
					be_id = " . $benutzerID . " AND
					at_datum = '" . date("Y-m-d", $timestamp) . "'
				LIMIT 1";
        $at = ArbeitstagUtilities::queryDB($sql);
        $at = $at[0];
        return $at;
    }

    public static function getMitarbeiterProjektAufgabenDatum($benutzerID, $projektID, $aufgabeID, $pDatum)
    {
        $sql = "SELECT
					*
				FROM
					arbeitstag
				WHERE
					be_id = " . $benutzerID . " AND
					au_id = " . $aufgabeID . " AND
					proj_id = " . $projektID . " AND
					at_datum = '" . $pDatum . "'";
        return ArbeitstagUtilities::queryDB($sql);
    }

    /**
     *
     * @param unknown $benutzerID            
     * @param unknown $sqlDatumStart            
     * @param unknown $sqlDatumEnde            
     * @param unknown $projektID            
     * @param unknown $aufgabeID            
     * @return DatabaseStorageObjektCollection
     */
    public static function getMitarbeiterProjektAufgabenZeitraumStunde($benutzerID, $sqlDatumStart, $sqlDatumEnde, $projektID, $aufgabeID)
    {
        $sql = "SELECT
					*
				FROM
					arbeitstag
				WHERE
					be_id = " . $benutzerID . " AND
					au_id = " . $aufgabeID . " AND 
					proj_id = " . $projektID . " AND
					at_datum >= '" . $sqlDatumStart . "' AND 
					at_datum <= '" . $sqlDatumEnde . "'";
        return ArbeitstagUtilities::queryDB($sql);
    }

    /**
     *
     * @param unknown $benutzerID            
     * @param unknown $projektID            
     * @param unknown $aufgabeID            
     * @param unknown $pDatum            
     * @return Arbeitstag
     */
    public static function getMitarbeiterProjektAufgabenArbeitstag($benutzerID, $projektID, $aufgabeID, $pTimestamp)
    {
        if (DateTime::createFromFormat('Y-m-d', $pTimestamp) !== false) {
            $datum = $pTimestamp;
        } else {
            $datum = date("Y-m-d", $pTimestamp);
        }
        $sql = "SELECT
					*
				FROM
					arbeitstag
				WHERE
					be_id = " . $benutzerID . " AND
					au_id = " . $aufgabeID . " AND
					proj_id = " . $projektID . " AND
					at_datum = '" . $datum . "'";
        $results = ArbeitstagUtilities::queryDB($sql);
        if ($results->getSize() == 1) {
            return $results->getValueOf(0);
        } else {
            return null;
        }
    }

    public static function resetMitarbeiterProjektAufgabeArbeitstag($benutzerID, $projektID, $aufgabeID, $pTimestamp)
    {
        if (DateTime::createFromFormat('Y-m-d', $pTimestamp) !== false) {
            $datum = $pTimestamp;
        } else {
            $datum = date("Y-m-d", $pTimestamp);
        }
        
        $sql = "DELETE
				FROM
					arbeitstag
				WHERE
					be_id = " . $benutzerID . " AND
					proj_id = " . $projektID . " AND
					au_id = " . $aufgabeID . " AND
					at_datum = '" . $datum . "'";
        // echo $sql."<br><br>";
        ArbeitstagUtilities::getDB()->NonSelectQuery($sql);
    }

    public static function resetMitarbeiterZeitraumAufgabe($benutzerID, $sqlDatumStart, $sqlDatumEnde, $projektID, $aufgabeID)
    {
        $sql = "DELETE 
				FROM
					arbeitstag
				WHERE
					be_id = " . $benutzerID . " AND
					proj_id = " . $projektID . " AND 
					au_id = " . $aufgabeID . " AND 
					at_datum >= '" . $sqlDatumStart . "' AND 
					at_datum <= '" . $sqlDatumEnde . "'";
        // echo $sql."<br><br>";
        ArbeitstagUtilities::getDB()->NonSelectQuery($sql);
    }

    public static function resetMitarbeiterArbeitstagAufgabe($benutzerID, $sqlDatum, $projektID, $aufgabeID)
    {
        $sql = "DELETE 
				FROM
					arbeitstag
				WHERE
					be_id = " . $benutzerID . " AND
					proj_id = " . $projektID . " AND 
					au_id = " . $aufgabeID . " AND 
					at_datum = '" . $sqlDatum . "';";
        // echo $sql."<br><br>";
        ArbeitstagUtilities::getDB()->NonSelectQuery($sql);
    }

    public static function berechneSummeWochenIstStunden($timestamp, $benutzerId)
    {
        $arWochentageTS = Date::berechneArbeitswocheTimestamp($timestamp);
        $sql = "SELECT sum(at_stunden_ist) as summe FROM arbeitstag WHERE be_id = " . $benutzerId . " AND at_datum >= '" . date("Y-m-d", $arWochentageTS[0]) . "' AND at_datum <= '" . date("Y-m-d", $arWochentageTS[6]) . "'";
        Log::sql($sql);
        if (($r = DB::getInstance()->SelectQuery($sql)) != false) {
            return $r[0]['summe'];
        }
        return - 1;
    }

    public static function berechneSummeWochenIstStundenProProjekt($timestamp, $benutzerId, $pid)
    {
        $arWochentageTS = Date::berechneArbeitswocheTimestamp($timestamp);
        $sql = "SELECT sum(at_stunden_ist) as summe FROM arbeitstag WHERE be_id = " . $benutzerId . " AND at_datum >= '" . date("Y-m-d", $arWochentageTS[0]) . "' AND at_datum <= '" . date("Y-m-d", $arWochentageTS[6]) . "' AND proj_id = " . $pid;
        Log::sql($sql);
        $r = DB::getInstance()->SelectQuery($sql);
        if (($r = DB::getInstance()->SelectQuery($sql)) != false) {
            return $r[0]['summe'];
        }
        return - 1;
    }

    public static function berechneSummeWochenIstStundenProProjektAufgabe($timestamp, $benutzerId, $pProjektId, $pAufgabeId)
    {
        $arWochentageTS = Date::berechneArbeitswocheTimestamp($timestamp);
        $sql = "SELECT sum(at_stunden_ist) as summe 
                FROM arbeitstag 
                WHERE 
                    be_id = " . $benutzerId . " AND 
                    at_datum >= '" . date("Y-m-d", $arWochentageTS[0]) . "' AND 
                    at_datum <= '" . date("Y-m-d", $arWochentageTS[6]) . "' AND 
                    proj_id = " . $pProjektId . " AND
                    au_id = " . $pAufgabeId;
        Log::sql($sql);
        $r = DB::getInstance()->SelectQuery($sql);
        if (($r = DB::getInstance()->SelectQuery($sql)) != false) {
            return $r[0]['summe'];
        }
        return - 1;
    }

    public static function ladeBisherigeArbeitwochen()
    {
        $sql = "SELECT DISTINCT 
    			at_datum as arbeitstag 
    		FROM 
    			arbeitstag 
    		ORDER BY 
    			at_datum DESC";
        
        $retVal = array();
        if (($r = ArbeitstagUtilities::getDB()->SelectQuery($sql)) !== false) {
            $date = new Date();
            foreach ($r as $aw) {
                $woche = Date::berechneArbeitswocheTimestamp(strtotime($aw['arbeitstag']));
                $tmp["kw"] = $date->getKW($woche[0]);
                $tmp["jahr"] = $date->getYear($woche[0]);
                $tmp["von"] = $woche[0];
                $tmp["bis"] = $woche[6];
                
                $retVal[] = $tmp;
                $tmp = array();
            }
        }
        return $retVal;
    }

    public static function ladeInkompletteMitarbeiter($timestamp)
    {
        $date = new Date();
        $sql = "SELECT 
    			aw.be_id,
    			b.be_benutzername,
    			aw_stunden_ist,
    			aw_Stunden_soll,
    			aw_stunden_dif
			FROM
  				arbeitswoche aw, benutzer b
			WHERE
				aw.be_id = b.be_id AND
				aw_status = 1 AND 
				aw_kw = " . $date->getKW($timestamp) . " AND
				aw_jahr = " . $date->getYear($timestamp) . "
			GROUP BY
				aw.be_id";
        return ArbeitstagUtilities::getDB()->SelectQuery($sql);
    }

    /**
     * Gibt ein Datenbankobjekt zurück
     *
     * @return DB
     */
    private static function getDB()
    {
        if (ArbeitstagUtilities::$dbInstance == null)
            ArbeitstagUtilities::$dbInstance = DB::getInstance();
        return ArbeitstagUtilities::$dbInstance;
    }

    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new Arbeitstag();
                $tmp->doLoadFromArray($objekt);
                
                if (isset($objekt['selected'])) {
                    $tmp->setSelected($objekt['selected']);
                }
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }
}
?>