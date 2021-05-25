<?php

class ArbeitswocheUtilities
{

    private static $dbInstance;

    
    
    /**
     * Zentralisiert bei der Einführung der mobilen Zeiterfassung. 
     * 
     * @param unknown $pBenutzerId Die benutzerId
     * @param unknown $pTimeStamp das Timestamp der Woche
     * @return Arbeitswoche|NULL|DatabaseStorageObjekt
     */
    public static function getOrCreateArbeitswoche($pBenutzerId, $pTimeStamp, $pid = 0) {
        $arWochentageTS = Date::berechneArbeitswocheTimestamp($pTimeStamp);
        
        $kw = date("W", $arWochentageTS[4]); // ISO 8601 Der Donnerstag der Woche ist entscheidend. Problemfall 2019
        $jahr = date("Y", $arWochentageTS[4]); // ISO 8601 Der Donnerstag der Woche ist entscheidend. Problemfall 2019
        
        $retVal = ArbeitswocheUtilities::ladeArbeitswoche($pBenutzerId, $kw, $jahr);
        if ($retVal == null) {
            
            $benSollStunden = BenutzerUtilities::getBenutzerSollWochenStunden($pBenutzerId, $pTimeStamp);
            $tmpSollStundenAbzglFeiertage = 0;
            foreach ($benSollStunden as $key => $val) {
                $tmpSollStundenAbzglFeiertage += $val;
            }
            
            $retVal = ArbeitswocheUtilities::createArbeitswoche($arWochentageTS[4]); // ISO-8601 - Der Donnerstag ist entscheinded. Problem 2019
            $retVal->setBenutzerID($pBenutzerId);
            $retVal->setWochenStundenSoll($tmpSollStundenAbzglFeiertage);
            $retVal->speichern(true);
        } else {
//             $summeStunden = ArbeitstagUtilities::berechneSummeWochenIstStundenProProjekt($arWochentageTS[1], $pBenutzerId, $pid);
//             $awArbeitswoche->setWochenStundenIst($awArbeitswoche->getWochenStundenIst() - $summeStunden);
        }
        return $retVal;
    }
    /**
     * Laedt die entsprechende Arbeitswoche
     *
     * @param int $benutzerId            
     * @param int $kw            
     * @param int $jahr            
     */
    public static function ladeArbeitswoche($benutzerId, $kw, $jahr)
    {
        $sql = "SELECT * FROM arbeitswoche WHERE be_id = " . $benutzerId . " AND aw_kw = " . $kw . " AND aw_jahr = " . $jahr;
        $r = ArbeitswocheUtilities::queryDB($sql);
        if ($r->getSize() >= 1) {
            return $r->getValueOf(0);
        }
        return null;
    }

    /**
     * Erstellt eine neues Arbeitswoche-Objekt anhand des Timestamps
     *
     * @param int $timestamp            
     * @return Arbeitswoche
     */
    public static function createArbeitswoche($timestamp)
    {
        $aw = Date::berechneArbeitswocheTimestamp($timestamp);
        $d = new Date();
        $retVal = new Arbeitswoche();
        $retVal->setBenutzerID(- 1);
        $retVal->setEingabeGebucht(0);
        $retVal->setEingabeKomplett(0);
        $retVal->setEingabeMoeglich(0);
        $retVal->setJahr(date("Y", $timestamp));
        $retVal->setKalenderWoche($d->getKW($timestamp));
        $retVal->setWochenStart(date("Y-m-d", $aw[0]));
        $retVal->setPersistent(false);
        return $retVal;
    }

    /**
     * Löscht die bereits erfassten Arbeitswochen mit dem gleichen Benutzer, Kalenderwoche und Jahr.
     *
     * @param Arbeitswoche $a            
     */
    public static function deletePreviousArbeitswoche(Arbeitswoche $a)
    {
        $sql = "DELETE FROM arbeitswoche WHERE be_id = " . $a->getBenutzerID() . " AND aw_kw = " . $a->getKalenderWoche() . " AND aw_jahr = " . $a->getJahr();
        DB::getInstance()->NonSelectQuery($sql);
    }

    public static function ladeNichtKompletteArbeitswochen()
    {
        $sql = "SELECT * FROM arbeitswoche WHERE aw_eingabe_komplett = 0 ORDER BY aw_jahr DESC, aw_kw DESC";
        return ArbeitswocheUtilities::queryDB($sql);
    }

    public static function ladeArbeitswochen()
    {
        $sql = "SELECT * FROM arbeitswoche ORDER BY aw_jahr DESC, aw_kw DESC";
        return ArbeitswocheUtilities::queryDB($sql);
    }
    
    public static function ladeArbeitswochenByBenutzerId($pBenutzerId)
    {
        $sql = "SELECT * FROM arbeitswoche WHERE be_id = ".$pBenutzerId." ORDER BY aw_jahr ASC, aw_kw ASC";
        return ArbeitswocheUtilities::queryDB($sql);
    }
    

    public static function getArbeitswochen($wochenStart)
    {
        $sql = "SELECT * FROM arbeitswoche WHERE aw_wochenstart = '" . $wochenStart . "'";
        return ArbeitswocheUtilities::queryDB($sql);
    }

    public static function bucheArbeitswoche($timestamp)
    {
        if ($timestamp == null)
            return;
        
        $aw = Date::berechneArbeitswocheTimestamp($timestamp);
        $wocheStart = date("Y-m-d", $aw[0]);
        $wocheEnde = date("Y-m-d", $aw[6]);
        
        $sql = "UPDATE arbeitstag SET at_gesperrt = 1 WHERE at_datum >= '" . $wocheStart . "' AND at_datum <= '" . $wocheEnde . "'";
        Log::sql($sql);
        DB::getInstance()->NonSelectQuery($sql);
        
        $sql = "UPDATE arbeitswoche SET aw_eingabe_moeglich = 0, aw_eingabe_gebucht = 1 WHERE aw_wochenstart = '" . $wocheStart . "'";
        DB::getInstance()->NonSelectQuery($sql);
    }

    /**
     * Gibt ein Datenbankobjekt zurück
     *
     * @return DB
     */
    private static function getDB()
    {
        if (ArbeitswocheUtilities::$dbInstance == null)
            ArbeitswocheUtilities::$dbInstance = DB::getInstance();
        return ArbeitswocheUtilities::$dbInstance;
    }

    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new Arbeitswoche();
                $tmp->doLoadFromArray($objekt);
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }
}
?>