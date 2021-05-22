<?php

class ProjektRechnungUtilities
{

    /**
     * 
     * @param string $strOrderBy
     * @return DatabaseStorageObjektCollection Alle Rechnungen für alle Projekte
     */
    public static function getAlleProjektRechnungen($strOrderBy = "")
    {
        $sql = "SELECT pr.*, p.proj_bezeichnung, a.au_bezeichnung FROM projekt_rechnung pr, aufgabe a, projekt p WHERE pr.proj_id = p.proj_id AND pr.pa_id = a.au_id ";
        if ($strOrderBy == "")
            $strOrderBy = " ORDER BY pr_datum ASC";
        $sql .= " " . $strOrderBy;
        return ProjektRechnungUtilities::queryDBAsProjektRechungBean($sql);
    }
    
    public static function getProjektRechnungen($pid, $strOrderBy = "")
    {
        $sql = "SELECT * FROM projekt_rechnung WHERE proj_id = " . $pid;
        if ($strOrderBy == "")
            $strOrderBy = " ORDER BY pr_datum ASC";
        $sql .= " " . $strOrderBy;
        return ProjektRechnungUtilities::queryDB($sql);
    }

    /**
     * Summe aller Rechnungen zu einer Aufgabe eines Projekts
     *
     * @param int $pid            
     * @param int $aid            
     * @return double
     */
    public static function getRechnungsSummeProjektAufgabe($projektID, $aufgabeID)
    {
        $sql = "SELECT 
    			sum(pr_betrag) as betrag 
    		FROM 
    			projekt_rechnung pr 
    		WHERE 
    			proj_id = " . $projektID . " AND
    			pa_id = " . $aufgabeID . " 
    		ORDER BY 
    			pr_datum ASC";
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            return $res[0]['betrag'];
        }
        return - 1;
    }

    public static function getProjektRechnungssummenByAufgabe($projektID)
    {
        $sql = "SELECT 
    			pa_id, sum(pr_betrag) as betrag 
    		FROM 
    			projekt_rechnung pr 
    		WHERE 
    			proj_id = " . $projektID . "
    		GROUP BY
    			pa_id
    		ORDER BY 
    			pr_datum ASC";
        $retVal = array();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $curr) {
                $retVal[$curr['pa_id']] = $curr['betrag'];
            }
        }
        return $retVal;
    }

    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new ProjektRechnung();
                $tmp->doLoadFromArray($objekt);
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }
    
    private static function queryDBAsProjektRechungBean($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new ProjektRechnungsListeBean();
                $tmp->doLoadFromArray($objekt);
                
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }
}
?>