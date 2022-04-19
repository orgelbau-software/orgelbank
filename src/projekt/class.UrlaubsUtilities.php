<?php

class UrlaubsUtilities
{

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