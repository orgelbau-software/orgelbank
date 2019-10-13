<?php

class RechnungsPositionUtilities
{

    public static function getRechnungsPositionen($iRechnungsID)
    {
        $sql = "SELECT * FROM rechnung_position WHERE r_id = " . $iRechnungsID;
        return RechnungsPositionUtilities::queryDB($sql);
    }

    private static function queryDB($sql)
    {
        $oCol = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            
            foreach ($res as $rs) {
                $tmp = new RechnungsPosition();
                $tmp->doLoadFromArray($rs);
                
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }
}