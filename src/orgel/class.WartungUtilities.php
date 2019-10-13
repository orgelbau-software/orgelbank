<?php

class WartungUtilities
{

    /**
     * Löscht die übergebene Wartung der Orgel<br/>
     * Setzt dann das "LetztePflege" Datum an dem Orgel-Objekt auf die vorherige Wartung zurück oder auf unbekannt.
     *
     * @param int $iWartungsId            
     */
    public static function deleteWartung($iWartungsId)
    {
        $iWartungsId = intval($iWartungsId);
        $oWartung = new Wartung($iWartungsId);
        $oWartung->loeschen();
        
        $oOrgel = new Orgel($oWartung->getOrgelId());
        $vorletzteWartung = WartungUtilities::getOrgelLetzteWartung($oOrgel->getID());
        if (null != $vorletzteWartung) {
            $vorletzteWartung = $vorletzteWartung->getDatum();
        } else {
            $vorletzteWartung = "0000-00-00";
        }
        $oOrgel->setLetztePflege($vorletzteWartung);
        $oOrgel->speichern();
    }

    public static function getOrgelWartungen($orgelId, $strOrderBy = "")
    {
        $sql = "SELECT * FROM wartung w WHERE o_id = " . $orgelId . " ";
        if ($strOrderBy != "")
            $sql .= $strOrderBy;
        return WartungUtilities::queryDB($sql);
    }

    /**
     * Gibt das letzte WartungsObjekt zurück oder NULL
     *
     * @param int $orgelId            
     * @return Wartung
     */
    public static function getOrgelLetzteWartung($orgelId)
    {
        $sql = "SELECT * FROM wartung w WHERE o_id = " . $orgelId . " ORDER BY w_datum DESC";
        $r = WartungUtilities::queryDB($sql);
        if ($r->getSize() > 0) {
            return $r[0];
        } else {
            return null;
        }
    }

    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new Wartung();
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