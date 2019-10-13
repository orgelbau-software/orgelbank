<?php

class KonfessionUtilities
{

    public static function getKonfessionen($orderBy = "")
    {
        $sql = "SELECT 
					*
				FROM
					konfession k ";
        if ($orderBy != "")
            $sql .= $orderBy;
        
        return KonfessionUtilities::queryDB($sql);
    }

    public static function getKonfessionenAsArray($orderBy = "")
    {
        $sql = "SELECT 
					*
				FROM
					konfession k ";
        if ($orderBy != "")
            $sql .= $orderBy;
        
        $c = KonfessionUtilities::queryDB($sql);
        $retVal = array();
        foreach ($c as $k) {
            $retVal[$k->getID()] = $k->getBezeichnung();
        }
        return $retVal;
    }

    public static function getKonfessionenKurzformAsArray($orderBy = "")
    {
        $sql = "SELECT 
					*
				FROM
					konfession k ";
        if ($orderBy != "")
            $sql .= $orderBy;
        
        $c = KonfessionUtilities::queryDB($sql);
        $retVal = array();
        foreach ($c as $k) {
            $retVal[$k->getID()] = $k->getKurzform();
        }
        return $retVal;
    }

    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new Konfession();
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