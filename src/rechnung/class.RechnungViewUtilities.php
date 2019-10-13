<?php

class RechnungViewUtilities
{

    public static function getRechnungByIdAndType($id, $typ)
    {
        $sql = "SELECT * FROM rechnung_view WHERE r_id = " . $id . " AND r_typid = " . $typ;
        return RechnungViewUtilities::queryDB($sql);
    }

    public static function getRechnungen($strOrderBy = "default")
    {
        $strOrderBy == "default" ? $strOrderBy = "r_datum DESC" : null;
        $sql = "SELECT * FROM rechnung_view ORDER BY " . $strOrderBy;
        return RechnungViewUtilities::queryDB($sql);
    }

    public static function getRechnungsDatumListAsArray()
    {
        $sql = "SELECT r_datum as datum FROM rechnung_view ORDER BY r_datum DESC";
        $result = DB::getInstance()->SelectQuery($sql);
        if ($result === false) {
            $result = array();
        }
        return $result;
    }

    public static function getFilteredRechnung($strWhere)
    {
        $sql = "SELECT * FROM rechnung_view " . $strWhere;
        return RechnungViewUtilities::queryDB($sql);
    }

    private static function queryDB($sql)
    {
        $oCol = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $rs) {
                
                // wird nicht über doLoadFromArray geladen, da Präfix auf VIEW zeigt
                $tmp = new RechnungView();
                $tmp->setID($rs['r_id']);
                $tmp->setNummer($rs['r_nummer']);
                $tmp->setRechnungsTypId($rs['r_typid']);
                $tmp->setRechnungsTyp($rs['r_typ']);
                $tmp->setNettoBetrag($rs['r_nettobetrag']);
                $tmp->setBruttoBetrag($rs['r_bruttobetrag']);
                $tmp->setGemeindeID($rs['g_id']);
                $tmp->setGemeindeName($rs['g_kirche']);
                $tmp->setDatum($rs['r_datum']);
                $tmp->setEingangsDatum($rs['r_eingangsdatum']);
                $tmp->setEingangsBetrag($rs['r_eingangsbetrag']);
                $tmp->setEingangsAnmerkung($rs['r_eingangsanmerkung']);
                
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }
}

?>
