<?php

class ArbeitstagUtilities
{

    private static $dbInstance;

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
            if($i + 1 != $length) {
                $sql .= " OR ";
            }
        }
        
        $sql .= ") AND at_datum between CAST('" . $pKalenderjahr . "-01-01' AS DATE) AND CAST('" . $pKalenderjahr . "-12-31' AS DATE)";
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

    public static function setBenutzerArbeitswocheKomplett($timestamp, $benutzerID)
    {
        $c = Date::berechneArbeitswoche($timestamp, "Y-m-d");
        $sql = "UPDATE 
					arbeitstag
				SET
					at_komplett = 1
				WHERE
					be_id = " . $benutzerID . " AND
					at_datum >= '" . $c['0'] . "' AND 
					at_datum <= '" . $c['6'] . "'";
        DB::getInstance()->NonSelectQuery($sql);
    }

    public static function setBenutzerArbeitswocheInkomplett($timestamp, $benutzerID)
    {
        $c = Date::berechneArbeitswoche($timestamp, "Y-m-d");
        $sql = "UPDATE 
					arbeitstag
				SET
					at_komplett = 0
				WHERE
					be_id = " . $benutzerID . " AND
					at_datum >= '" . $c['0'] . "' AND 
					at_datum <= '" . $c['6'] . "'";
        DB::getInstance()->NonSelectQuery($sql);
    }

    public static function isArbeitswocheGesperrt($timestamp)
    {
        $c = Date::berechneArbeitswoche($timestamp, "Y-m-d");
        $sql = "SELECT 
					* 
				FROM 
					arbeitstag 
				WHERE 
					at_datum >= '" . $c[0] . "' AND 
					at_datum <= '" . $c[6] . "' AND 
					at_gesperrt = 1";
        if (DB::getInstance()->getMysqlNumRows($sql) > 0)
            return true;
        return false;
    }

    public static function isBenutzerArbeitswocheGesperrt($timestamp, $benutzerID)
    {
        $c = Date::berechneArbeitswoche($timestamp, "Y-m-d");
        $sql = "SELECT 
					* 
				FROM 
					arbeitstag 
				WHERE
					be_id = " . $benutzerID . " AND
					at_gesperrt = 1 AND
					at_datum >= '" . $c[0] . "' AND 
					at_datum <= '" . $c[6] . "'";
        if (DB::getInstance()->getMysqlNumRows($sql) > 0)
            return true;
        return false;
    }

    public static function isBenutzerArbeitswocheKomplett($timestamp, $benutzerID)
    {
        $c = Date::berechneArbeitswoche($timestamp, "Y-m-d");
        $retVal = false;
        $sql = "SELECT 
					* 
				FROM 
					arbeitstag 
				WHERE
					be_id = " . $benutzerID . " AND
					at_komplett = 1 AND
					at_datum >= '" . $c[0] . "' AND 
					at_datum <= '" . $c[6] . "'";
        if (DB::getInstance()->getMysqlNumRows($sql) > 0) {
            $retVal = true;
        }
        
        Log::sql($sql);
        $s = $retVal ? "true" : "false";
        Log::debug("BenutzerArbeitswocheKomplett: " . $s);
        return $retVal;
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
				aw_eingabe_komplett = 0 AND 
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