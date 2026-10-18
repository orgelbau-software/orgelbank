<?php

/** @package  */
class ReisekostenUtilities
{

    /**
     * Erstellt einen Array mit den Summen der Reisekosten eines Projekts
     *
     * @param int $pid            
     * @return array
     */
    public static function getProjektReisekosten($pid)
    {
        $sql = "SELECT
  			sum(rk_spesen) as spesen,
  			sum(rk_hotel) as hotel,
  			sum(rk_kmkosten) as kmkosten,
  			sum(rk_gesamt) as gesamt
  			FROM
  				reisekosten
  			WHERE 
  				proj_id = " . $pid . "
  			GROUP BY
  				proj_id";
        $db = DB::getInstance();
        $res = $db->SelectQuery($sql);
        if ($res !== false) {
            return $res[0];
        }
        return array(
            "spesen" => 0,
            "hotel" => 0,
            "kmkosten" => 0,
            "gesamt" => 0
        );
    }

    /**
     * Gibt ein Reisekosten Objekt mit den übergebenen Parametern zurück
     *
     * @param int $benutzerID                    
     * @param string $datum
     * @return Reisekosten
     */
    public static function ladeReisekostenEintrag($benutzerID, $datum)
    {
        $sql = "SELECT 
    			* 
    		FROM 
    			reisekosten 
    		WHERE 
    			be_id = " . $benutzerID . " AND 
                rk_datum = '" . $datum . "'";
        $res = DB::getInstance()->SelectQuery($sql);
        $retVal = null;
        
        if ($res === false) {
            $retVal = null;
        } else {
            $x = $res[0];
            $retVal = new Reisekosten();
            $retVal->doLoadFromArray($x);
            $retVal->setPersistent(true);
        }
        return $retVal;
    }
    
    /**
     * Gibt ein Reisekosten Objekt mit den übergebenen Parametern zurück
     *
     * @param int $benutzerID
     * @param int $projektID
     * @param int $wochenStart
     * @return Reisekosten
     */
    public static function getReisekostenSummeProKW($benutzerID, $kw, $jahr)
    {
        $sql = "SELECT
                    COALESCE(SUM(rk_hotel), 0) AS rk_hotel,
                    COALESCE(SUM(rk_spesen), 0) AS rk_spesen,
                    COALESCE(SUM(rk_kmkosten), 0) AS rk_kmkosten,
                    COALESCE(SUM(rk_km), 0) AS rk_km,
                    COALESCE(SUM(rk_gesamt), 0) AS rk_gesamt
                FROM
                    reisekosten
                WHERE
                    be_id = " . intval($benutzerID) . " AND
                    rk_kw = '" . intval($kw) . "' AND
                    rk_jahr = '" . intval($jahr) . "'";
        $res = DB::getInstance()->SelectQuery($sql);
        if ($res === false) {
            throw new RuntimeException("Die Reisekostensummen konnten nicht geladen werden.");
        }
        $x = $res[0];
        $retVal = new Reisekosten();
        $retVal->setBenutzerID($benutzerID);
        $retVal->setHotel($x['rk_hotel']);
        $retVal->setSpesen($x['rk_spesen']);
        $retVal->setKMKosten($x['rk_kmkosten']);
        $retVal->setGesamt($x['rk_gesamt']);
        $retVal->setKM($x['rk_km']);
        $retVal->setPersistent(false);
        return $retVal;
    }

    /**
     * Löscht einen Reisekosten-Datensatz für einen Benutzer an einem bestimmten Datum.
     *
     * @param int $benutzerID
     * @param string $datum
     */
    public static function loescheReisekostenDatensatz($benutzerID, $datum)
    {
        $sql = "DELETE FROM reisekosten WHERE be_id = " . intval($benutzerID) . " AND rk_datum = '" . $datum . "'";
        DB::getInstance()->NonSelectQuery($sql);
    }

    /**
     *
     * @param String $sql            
     * @return DatabaseStorageObjektCollection
     */
    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new Reisekosten();
                $tmp->doLoadFromArray($objekt);
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }
}

?>
