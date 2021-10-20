<?php

/**
 * @author swatermeyer
 * @version $Revision: $
 *
 */
class AufgabeUtilities
{

    public static function loadChildrenAufgaben($iParentID, $orderby = "", $ladeGeloeschteAufgaben = false)
    {
        $sql = "SELECT
					*
				FROM
					aufgabe
				WHERE ";
        
        // keine geloeschte aufgaben anzeigen
        if ($ladeGeloeschteAufgaben == false) {
            $sql .= " au_geloescht = 0 AND ";
        }
        
        $sql .= " au_parentid = " . $iParentID;
        if ($orderby == "")
            $orderby .= " ORDER BY au_bezeichnung ASC";
        $sql .= $orderby;
        return AufgabeUtilities::queryDB($sql);
    }

    public static function getHauptAufgaben($orderby = "")
    {
        $sql = "SELECT
					*
				FROM 
					aufgabe 
				WHERE 
					au_geloescht = 0 AND
					au_parentid = 0 ";
        if ($orderby == "")
            $orderby = "ORDER BY au_bezeichnung ASC";
        $sql .= $orderby;
        return AufgabeUtilities::queryDB($sql);
    }

    public static function addMitarbeiterAufgabeFreischalten($iAufgabeID, $iMitarbeiterID)
    {
        $sql = "INSERT INTO aufgabe_mitarbeiter VALUES(" . $iAufgabeID . ", " . $iMitarbeiterID . ")";
        DB::getInstance()->NonSelectQuery($sql);
    }

    public static function resetAufgabeMitarbeiterZuordnung($iAufgabeID)
    {
        $sql = "DELETE FROM aufgabe_mitarbeiter WHERE au_id = " . $iAufgabeID;
        DB::getInstance()->NonSelectQuery($sql);
    }
    
    public static function resetMitarbeiterAufgabeZuordnung($iMitarbeiterID)
    {
        $sql = "DELETE FROM aufgabe_mitarbeiter WHERE be_id = " . $iMitarbeiterID;
        DB::getInstance()->NonSelectQuery($sql);
    }
    
    public static function getMitarbeiterAufgaben($iMitarbeiterID) {
        $sql = "SELECT * FROM aufgabe a, aufgabe_mitarbeiter am WHERE a.au_id = am.au_id and am.be_id = ".$iMitarbeiterID;
        return AufgabeUtilities::queryDB($sql);
    }

    public static function aufgabeExists($aufgabe)
    {
        $sql = "SELECT 
					* 
					FROM 
						aufgabe 
					WHERE 
						au_bezeichnung = '" . $aufgabe . "'";
        return DB::getInstance()->getMysqlNumRows($sql);
    }

    public static function getAlleAufgaben($strOrderBy = "")
    {
        $sql = "SELECT
					*
				FROM 
					aufgabe a
				";
        
        // Geloeschte Aufgaben ausblenden
        // $sql .= " WHERE au_geloescht = 0 ";
        if ($strOrderBy == "")
            $strOrderBy = "ORDER BY au_bezeichnung ASC";
        $sql .= $strOrderBy;
        return AufgabeUtilities::queryDB($sql);
    }

    public static function getAlleAufgabenAsArray()
    {
        $a = AufgabeUtilities::getAlleAufgaben();
        $retVal = array();
        foreach ($a as $x) {
            $retVal[$x->getID()] = array(
                "bezeichnung" => $x->getBezeichnung(),
                "parentid" => $x->getParentID()
            );
        }
        return $retVal;
    }

    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new Aufgabe();
                $tmp->doLoadFromArray($objekt);
                if (isset($objekt['selected'])) {
                    $tmp->setSelected($objekt['selected']);
                }
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }

    public static function loescheUnteraufgaben($parentAufgabeId)
    {
        $unteraufgaben = AufgabeUtilities::loadChildrenAufgaben($parentAufgabeId, "", false);
        foreach ($unteraufgaben as $current) {
            $current->loeschen();
            $current->speichern(false);
        }
    }
}
?>