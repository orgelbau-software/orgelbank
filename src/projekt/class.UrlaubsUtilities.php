<?php

class UrlaubsUtilities
{

    public static function getLetzteUrlaubsTagsIdProBenutzer() {
        $sql = "SELECT u.be_id, MAX(u.u_id) as u_id FROM urlaub u GROUP BY be_id";
        
        $retVal = array();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $retVal[$objekt['be_id']] = $objekt['u_id'];
            }
        }
        return $retVal;
    }
    /**
     * 
     * @param int $pBenutzerId
     * @return NULL|Urlaub
     */
    public static function getLetzterUrlaubsEintrag($pBenutzerId) {
        $sql = "SELECT u.* FROM urlaub u WHERE be_id = ".$pBenutzerId. " ORDER BY u.u_id DESC LIMIT 1";
        $r = UrlaubsUtilities::queryDB($sql);
        
        $retVal = null;
        if($r != null && $r[0] != null) {
            $retVal = $r[0];
        }
        return $retVal;
    }
    
    public static function getUrlaubsEintraege($where = "", $orderby = "")
    {
        $sql = "SELECT
					u.*, b.be_benutzername
				FROM
					urlaub u LEFT JOIN benutzer b on u.be_id = b.be_id ";
        if ($where != "") {
            $where = "WHERE ".$where;
        }
        $sql .= $where;
        
        if ($orderby == "") {
            $orderby = " ORDER BY u_datum_von ASC";
        }
        $sql .= $orderby;
        return UrlaubsUtilities::queryDB($sql);
    }

    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new Urlaub();
                $tmp->doLoadFromArray($objekt);
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                if(isset($objekt['be_benutzername'])) {
                    $tmp->setBenutzername($objekt['be_benutzername']);
                }
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }
}