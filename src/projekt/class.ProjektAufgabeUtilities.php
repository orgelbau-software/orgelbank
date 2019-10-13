<?php

/**
 * @author swatermeyer
 * @version $Revision: $
 *
 */
class ProjektAufgabeUtilities
{

    public static function addAufgabeToProjekt($aufgabeID, $projektID)
    {
        $sql = "INSERT INTO projekt_aufgabe VALUES	(" . $projektID . ", " . $aufgabeID . ")";
        DB::getInstance()->NonSelectQuery($sql);
    }

    public static function resetProjektAufgaben($iProjektID)
    {
        $sql = "DELETE FROM projekt_aufgabe WHERE proj_id = " . $iProjektID;
        DB::getInstance()->NonSelectQuery($sql);
    }

    public static function getAlleProjektAufgaben($pid = "", $strOrderBy = "")
    {
        $sql = "SELECT
					*
				FROM 
					aufgabe a, projekt_aufgabe pa 
				WHERE 
					a.au_id = pa.au_id AND ";
        
        // Keine geloeschten Aufgaben anzeigen
        $sql .= " au_geloescht = 0 ";
        
        if ($pid != "")
            $sql .= " AND proj_id = " . $pid . " ";
        if ($strOrderBy == "")
            $strOrderBy = "ORDER BY 
                                pa_reihenfolge ASC,
                               au_bezeichnung ASC";
        $sql .= $strOrderBy;
        return ProjektAufgabeUtilities::queryDB($sql);
    }

    public static function getSelectedProjektAufgaben($iProjektID)
    {
        $sql = "SELECT
					a.*, pa.pa_plankosten, pa.proj_id, 
                    CASE WHEN pa.au_id > 0 THEN 1 ELSE 0 END as selected,
                    CASE WHEN pa.pa_reihenfolge IS NULL THEN 99 ELSE pa_reihenfolge END as pa_reihenfolge
				FROM 
					aufgabe a LEFT JOIN projekt_aufgabe pa ON a.au_id = pa.au_id AND pa.proj_id = " . $iProjektID . "
				WHERE 
					au_geloescht = 0 AND
					au_parentid = 0 
				ORDER BY
                    pa_reihenfolge ASC,
					a.au_bezeichnung ASC";
        return ProjektAufgabeUtilities::queryDB($sql);
    }

    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new ProjektAufgabe();
                $tmp->setID($objekt['au_id']);
                $tmp->setPKaufgabeID($objekt['au_id']);
                $tmp->setPKprojektID($objekt['proj_id']);
                $tmp->setBeschreibung($objekt['au_beschreibung']);
                $tmp->setBezeichnung($objekt['au_bezeichnung']);
                $tmp->setPlankosten($objekt['pa_plankosten']);
                
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

    public static function copyAufgabeToProjektAufgabe(Aufgabe $a)
    {
        $retVal = new ProjektAufgabe();
        $retVal->setID($a->getID());
        $retVal->setPKAufgabeID($a->getID());
        $retVal->setBeschreibung($a->getBeschreibung());
        $retVal->setBezeichnung($a->getBezeichnung());
        $retVal->setPlankosten(0);
        $retVal->setPersistent(false);
        return $retVal;
    }
}
?>