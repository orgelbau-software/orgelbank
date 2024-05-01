<?php

/**
 * @author swatermeyer
 */
class BenutzerUtilities
{

    /**
     * Ermittelt die Benutzer Soll-Wochenstunden aus der Datenbank
     *
     * @param String $benutzerID            
     * @return int Stunden Anzahl
     */
    public static function getBenutzerSollStunden($benutzerID)
    {
        $sql = "SELECT 
					*
				FROM
					benutzer
				WHERE
					be_id = " . $benutzerID;
        $r = BenutzerUtilities::queryDB($sql);
        return $r[0]->getStdGesamt();
    }

    /**
     *
     * Ermittelt die Soll Stunden abzgl. Feiertage
     *
     * @param int $benutzerID            
     * @param int $wochenTimeStamp            
     */
    public static function getBenutzerSollWochenStunden($benutzerID, $wochenTimeStamp)
    {
        $sql = "SELECT 
					*
				FROM
					benutzer
				WHERE
					be_id = " . $benutzerID;
        $r = BenutzerUtilities::queryDB($sql);
        $r = $r[0];
        
        // Bisschen schummeln...
        $benutzerStd = array();
        $benutzerStd[0] = $r->getStdMontag();
        $benutzerStd[1] = $r->getStdDienstag();
        $benutzerStd[2] = $r->getStdMittwoch();
        $benutzerStd[3] = $r->getStdDonnerstag();
        $benutzerStd[4] = $r->getStdFreitag();
        $benutzerStd[5] = $r->getStdSamstag();
        $benutzerStd[6] = $r->getStdSonntag();
        
        $retVal = array();
        $arWoche = Date::berechneArbeitswocheTimestamp($wochenTimeStamp);
        
        // TODO: Weihnachten und Silvester jeweils ein halber Tag?? -> Klären!
        $date = new Date();
        for ($i = 0; $i < 7; $i ++) {
            if (ConstantLoader::getFeiertagAutomatischFrei() &&  $date->isFeiertag(date("Y-m-d", $arWoche[$i]))) {
                $retVal[$i] = "0.00";
            } else {
                $retVal[$i] = $benutzerStd[$i];
            }
        }
        
        return $retVal;
    }

    public static function getMitarbeiterAufgabe($iAufgabeID)
    {
        $sql = "SELECT 
					b.*, IFNULL(am.au_id,0) as freigeschaltet, " . $iAufgabeID . " as au_id
				FROM 
					benutzer b LEFT JOIN aufgabe_mitarbeiter am ON b.be_id = am.be_id AND am.au_id = " . $iAufgabeID . " 
				WHERE 
					be_geloescht = 0 AND
					be_demo = 0
				ORDER BY
					be_sortierung ASC";
        return BenutzerUtilities::queryDBforAufgabeMitarbeiter($sql);
    }

    public static function loadByBenutzername($benutzername)
    {
        $sql = "SELECT 
					*
				FROM
					benutzer
				WHERE
					be_benutzername = '" . $benutzername . "'";
        $r = BenutzerUtilities::queryDB($sql);
        $r = $r[0];
        return $r;
    }
    
    /**
     * 
     * @param unknown $passwort
     * @param string $pKlarText
     * @return boolean|Benutzer
     */
    public static function loadByPin($passwort, $pKlarText = false)
    {
        if($pKlarText == true) {
            $passwort = PasswordUtility::encrypt($passwort);
        }
        $sql = "SELECT
					*
				FROM
					benutzer
				WHERE
					be_passwort = '" . $passwort . "' AND
					be_aktiviert = 1 AND
                    be_zeiterfassung = 1;";
        if(DB::getInstance()->getMysqlNumRows($sql) > 1) {
            // Fehler, es sollte nicht mehr Benutzer mit dem gleichen Passwort geben.
            return false;
        }
        
        $r = BenutzerUtilities::queryDB($sql);
        $r = $r[0];
        return $r;
    }

    /**
     * 
     * @param String $benutzernamen
     * @param String $passwort
     * @param boolean $pKlarText
     * @return boolean
     */
    public static function authorisiereBenutzerdaten($benutzernamen, $passwort, $pKlarText = false)
    {
        if($pKlarText == true) {
            $passwort = PasswordUtility::encrypt($passwort);
        }
        $sql = "SELECT
					*
				FROM 
					benutzer
				WHERE 
					be_benutzername = '" . $benutzernamen . "' AND 
					be_passwort = '" . $passwort . "' AND
					be_aktiviert = 1";
        return DB::getInstance()->getMysqlNumRows($sql) == 1;
    }

    public static function exists($username)
    {
        $sql = "SELECT
					* 
					FROM 
						benutzer 
					WHERE 
						be_benutzername = '" . $username . "'";
        return DB::getInstance()->getMysqlNumRows($sql) == 1;
    }

    /**
     * Liefert alle Benutzer, die nicht als geloescht markiert sind
     *
     * @param String $strOrderBy            
     * @return DatabaseStorageObjektCollection
     */
    public static function getBenutzer($strOrderBy = "")
    {
        $sql = "SELECT
					*
				FROM 
					benutzer
				WHERE
					be_geloescht = 0 AND
    				be_demo = 0";
        if ($strOrderBy == "") {
            $strOrderBy = " ORDER BY be_sortierung ";
        }
        $sql .= " " . $strOrderBy;
        return BenutzerUtilities::queryDB($sql);
    }

    /**
     * Gibt alle Benutzer zurück, die für die Zeiterfassung freigeschaltet sind
     *
     * @param unknown_type $strOrderBy            
     * @return unknown
     */
    public static function getZeiterfassungsBenutzer($strOrderBy = "")
    {
        $sql = "SELECT
					*
				FROM 
					benutzer
				WHERE
					be_geloescht = 0 
    				AND be_demo = 0 
					AND be_zeiterfassung = 1";
        if ($strOrderBy == "") {
            $strOrderBy = " ORDER BY be_sortierung ";
        }
        $sql .= " " . $strOrderBy;
        return BenutzerUtilities::queryDB($sql);
    }

    /**
     * Liefert alle Benutzer, auch die geloeschten
     *
     * @param String $strOrderBy            
     * @return DatabaseStorageObjektCollection
     */
    public static function getAlleBenutzer($strOrderBy = "")
    {
        $sql = "SELECT
					*
				FROM 
					benutzer";
        $sql .= " " . $strOrderBy;
        return BenutzerUtilities::queryDB($sql);
    }

    public static function berechneUeberstunden($iBenutzerId)
    {
        $sql = "SELECT sum(aw_stunden_dif) as summe FROM arbeitswoche WHERE be_id = " . $iBenutzerId;
        $r = DB::getInstance()->SelectQuery($sql);
        if (($r = DB::getInstance()->SelectQuery($sql)) != false) {
            $r = $r[0]['summe'];
            return $r;
        }
        return - 1;
    }

    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new Benutzer();
                $tmp->doLoadFromArray($objekt);
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }

    private static function queryDBforAufgabeMitarbeiter($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new AufgabeMitarbeiter();
                $tmp->setID($objekt['be_id']);
                $tmp->setVorname($objekt['be_vorname']);
                $tmp->setNachname($objekt['be_nachname']);
                $tmp->setBenutzername($objekt['be_benutzername']);
                $tmp->setBenutzerlevel($objekt['be_benutzerlevel']);
                $tmp->setPasswort($objekt['be_passwort']);
                $tmp->setAktiviert($objekt['be_aktiviert']);
                $tmp->setAufgabeID($objekt['au_id']);
                $tmp->setEintrittsDatum($objekt['be_eintrittsdatum']);
                
                if ($objekt['freigeschaltet'] == 0) {
                    $tmp->setFreigeschaltet(false);
                } else {
                    $tmp->setFreigeschaltet(true);
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