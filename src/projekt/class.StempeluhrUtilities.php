<?php

class StempeluhrUtilities
{

    public static function ladeAlleStempeluhrEintraege()
    {
        $sql = "SELECT * FROM stempeluhr_view ORDER BY st_zeit DESC";
        
        return self::queryDBforBeans($sql);
    }
    
    /**
     * 
     * @param unknown $pBenutzerId
     * @return Stempeluhr
     */
    public static function hatOffenenStempeluhrEintrag($pBenutzerId)
    {
        $sql = "SELECT * FROM stempeluhr WHERE st_status = 0 AND st_zeit > '" . date("Y-m-d") . " 00:00:00'";
        
        $retVal = self::queryDB($sql);
        if($retVal->getSize() == 0) {
            return null;
        }
        return $retVal[0];
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
                $tmp = new Stempeluhr();
                $tmp->doLoadFromArray($objekt);
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }
    
    /**
     *
     * @param String $sql
     * @return DatabaseStorageObjektCollection
     */
    private static function queryDBforBeans($sql)
    {
        $oCol = new ArrayList();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new StempeluhrViewBean();
                $tmp->init($objekt);
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }
}