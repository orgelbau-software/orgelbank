<?php

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
     * @param int $projektID            
     * @param int $wochenStart            
     * @return Reisekosten
     */
    public static function getReisekosten($benutzerID, $projektID, $kw, $jahr)
    {
        $sql = "SELECT 
    			* 
    		FROM 
    			reisekosten 
    		WHERE 
    			be_id = " . $benutzerID . " AND 
    			proj_id = " . $projektID . " AND
                rk_kw = '" . $kw . "' AND
                rk_jahr = '" . $jahr . "'";
        $res = DB::getInstance()->SelectQuery($sql);
        $retVal = null;
        if ($res === false) {
            $retVal = new Reisekosten();
            $retVal->setProjektID($projektID);
            $retVal->setBenutzerID($benutzerID);
            $retVal->setKW($kw);
            $retVal->setJahr($jahr);
        } else {
            $x = $res[0];
            $retVal = new Reisekosten();
            $retVal->doLoadFromArray($x);
            $retVal->setPersistent(true);
        }
        return $retVal;
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
