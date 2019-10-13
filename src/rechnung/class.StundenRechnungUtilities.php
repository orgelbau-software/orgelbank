<?php

class StundenrechnungUtilities
{

    private static function queryDB($sql)
    {
        $oCol = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $rs) {
                $tmp = new StundenRechnung();
                $tmp->doLoadFromArray($rs);
                
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }
}
?>