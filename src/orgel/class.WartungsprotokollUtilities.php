<?php

class WartungsprotokollUtilities
{

    public static function getWartungsprotokolle()
    {
        $sql = "SELECT * FROM wartungsprotokolle";
        $r = WartungsprotokollUtilities::queryDB($sql);
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