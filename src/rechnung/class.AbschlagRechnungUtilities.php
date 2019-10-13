<?php

class AbschlagrechnungUtilities
{

    public static function getGemeindeAbschlagsRechnungen($iGemeindeID, $strOrderBy = null)
    {
        $sql = "SELECT 
					*
				FROM 
					rechnung_abschlag 
				WHERE g_id = " . $iGemeindeID;
        if ($strOrderBy != null)
            $sql .= $strOrderBy;
        return AbschlagrechnungUtilities::queryDB($sql);
    }

    public static function getAbschlagsRechnungenOhneEndRechnung($iGemeindeID, $strOrderBy = null)
    {
        $sql = "SELECT 
					*
				FROM 
					rechnung_abschlag 
				WHERE 
					re_id = 0 AND
					g_id = " . $iGemeindeID;
        if ($strOrderBy != null)
            $sql .= $strOrderBy;
        return AbschlagrechnungUtilities::queryDB($sql);
    }

    public static function getAbschlagsRechnungenFuerEndRechnung($iEndRechnungID, $strOrderBy = null)
    {
        $sql = "SELECT 
					*
				FROM 
					rechnung_abschlag 
				WHERE 
					re_id = " . $iEndRechnungID;
        if ($strOrderBy != null) {
            $sql .= $strOrderBy;
        } else {
            $sql .= " ORDER BY ra_datum DESC";
        }
        return AbschlagrechnungUtilities::queryDB($sql);
    }

    public static function updateAbschlagsRechnungWithEndRechnungId($aID, $eID)
    {
        $sql = "UPDATE rechnung_abschlag SET re_id = " . $eID . " WHERE ra_id=" . $aID . " AND re_id = 0";
        $sql = "UPDATE rechnung_abschlag SET re_id = " . $eID . " WHERE ra_id=" . $aID;
        $instance = DB::getInstance();
        $instance->NonSelectQuery($sql);
    }

    private static function queryDB($sql)
    {
        $oCol = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $rs) {
                $tmp = new AbschlagsRechnung();
                $tmp->doLoadFromArray($rs);
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }
}
?>
