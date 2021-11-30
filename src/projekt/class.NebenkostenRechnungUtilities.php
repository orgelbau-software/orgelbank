<?php

class NebenkostenRechnungUtilities
{

    public static function getNebenkostenRechnungen($pid, $strOrderBy = "")
    {
        $sql = "SELECT * FROM nebenkosten_rechnung WHERE proj_id = " . $pid;
        if ($strOrderBy == "")
            $strOrderBy = " ORDER BY nk_datum ASC";
        $sql .= " " . $strOrderBy;
        return NebenkostenRechnungUtilities::queryDB($sql);
    }

    /**
     * Summe aller Rechnungen zu einer Aufgabe eines Projekts
     *
     * @param int $pid
     * @param int $aid
     * @return double
     */
    public static function getNebenkostenRechnungenSumme($projektID)
    {
        $sql = "SELECT
    			sum(nk_betrag) as betrag
    		FROM
    			nebenkosten_rechnung nk
    		WHERE
    			proj_id = " . $projektID . "
    		ORDER BY
    			nk_datum ASC";
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            return $res[0]['betrag'];
        }
        return - 1;
    }
    
    
    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new NebenkostenRechnung();
                $tmp->doLoadFromArray($objekt);
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }
}
?>