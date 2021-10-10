<?php

class WartungsprotokollUtilities
{

    public static function getWartungsprotokolle()
    {
        $sql = "SELECT * FROM wartungsprotokolle";
        $r = WartungsprotokollUtilities::queryDB($sql);
        return $r;
    }
    
    public static function deleteWartungsprotokoll($pProtokollId) {
        $sql = "DELETE FROM wartungsprotokolle WHERE wp_id = ".$pProtokollId;
        DB::getInstance()->NonSelectQuery($sql);
    }

    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new Wartungsprotokoll();
                $tmp->doLoadFromArray($objekt);
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }
}