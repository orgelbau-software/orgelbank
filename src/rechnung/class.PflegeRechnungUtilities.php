<?php

class PflegeRechnungUtilities
{

    /**
     * Gibt die letzte Rechnung einer Gemeinde zurück
     *
     * @param int $iGemeindeID            
     * @return PflegeRechnung
     */
    public static function getLetztePflegeRechnung($iGemeindeID)
    {
        $sql = "SELECT * FROM rechnung_pflege rp WHERE g_id = " . $iGemeindeID . " ORDER BY rp_datum DESC LIMIT 1";
        $c = PflegerechnungUtilities::queryDB($sql);
        
        if ($c->getSize() == 1)
            return $c->getValueOf(0);
        return null;
    }

    /**
     *
     * @param Integer $iGemeindeID            
     * @param String $datum
     *            Das Datum ab wann die letzte Rechnung gesucht werden soll.
     */
    public static function getLetztePflegeRechnungVor($iGemeindeID, $datum)
    {
        $datum = date("Y-m-d", strtotime($datum));
        $sql = "SELECT * FROM rechnung_pflege rp WHERE g_id = " . $iGemeindeID . " AND rp_datum < '" . $datum . "' ORDER BY rp_datum DESC LIMIT 1";
        $c = PflegerechnungUtilities::queryDB($sql);
        if ($c->getSize() == 1) {
            return $c->getValueOf(0);
        } else {
	        return null;
        }
    }

    /**
     * Gibt die Plfegerechnungen einer Gemeinde zurück
     *
     * Wird im IFrame in den Gemeindedetails verwendet
     *
     * @param int $iGemeindeID            
     * @param String $strOrderBy            
     * @return DatabaseStorageObjektCollection
     */
    public static function zeigeGemeindeRechnung($iGemeindeID, $strOrderBy = null)
    {
        $sql = "SELECT 
					*
				FROM 
					rechnung_pflege 
				WHERE g_id = " . $iGemeindeID;
        if ($strOrderBy != null)
            $sql .= $strOrderBy;
        return PflegeRechnungUtilities::queryDB($sql);
    }

    private static function queryDB($sql)
    {
        $oCol = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $rs) {
                $tmp = new PflegeRechnung();
                $tmp->doLoadFromArray($rs);
                
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }
}
?>