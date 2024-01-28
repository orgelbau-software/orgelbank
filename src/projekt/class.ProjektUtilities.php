<?php

class ProjektUtilities
{

    
    public static function getBenutzerAufgaben($iBenutzerID)
    {
        $sql = "SELECT DISTINCT
					p.*
				FROM
					benutzer b, 
					aufgabe_mitarbeiter am, 
					projekt p, 
					gemeinde g,
                    adresse ad,
					projekt_aufgabe pa, 
					aufgabe a LEFT JOIN aufgabe a2 on a.au_id = a2.au_parentid AND a2.au_geloescht = 0
				WHERE b.be_id = " . $iBenutzerID . " 
					AND b.be_id = am.be_id
					AND a.au_id = am.au_id
					AND p.proj_id = pa.proj_id 
					AND pa.au_id = a.au_id
					AND p.g_id = g.g_id
                    AND g.g_kirche_aid = ad.ad_id
					AND a.au_geloescht = 0
					AND p.proj_geloescht = 0
					AND p.proj_archiviert = 0 
				ORDER BY ";
        
        // Sortierung nach Ort oder Gemeinde
//         if(ConstantLoader::getGemeindeListeStandardSortierung() == "ort") {
//             $sql .= "ad.ad_ort, ";
//         } else {
//             $sql .= "g_kirche, ";
//         }
        $sql .= " p.proj_sortierung, "; 
        $sql .= " p.proj_bezeichnung, 
					a.au_bezeichnung,
					a2.au_bezeichnung";
        return ProjektUtilities::queryDB($sql);
    }

    /**
     * Überprüft ob die übergeben ProjektID ein Verwaltungsprojekt ist
     *
     * @param int $pid            
     * @return boolean
     */
    public static function isVerwaltungsProjekt($pid)
    {
        return in_array($pid, ConstantLoader::getVerwaltungsProjekte());
    }

    /**
     * Überprüft ob die übergeben ProjektID ein Urlaubsprojekt ist
     *
     * @param int $pid            
     * @return boolean
     */
    public static function isUrlaubsAufgabe($pid)
    {
        return in_array($pid, ConstantLoader::getUrlaubUnteraufgaben());
    }

    /**
     * Überprüft ob die übergeben ProjektID ein Krankheitsprojekt ist
     *
     * @param int $pid            
     * @return boolean
     */
    public static function isKrankProjekt($pid)
    {
        return in_array($pid, ConstantLoader::getKrankProjekte());
    }

    /**
     * Ermittelt alle archivierten Projekte
     *
     * @return DatabaseStorageObjektCollection
     */
    public static function getArchivierteProjekte()
    {
        $sql = "SELECT
					*
				FROM 
					projekt
				WHERE
					proj_geloescht = 0 AND
					proj_archiviert = 1";
        return ProjektUtilities::queryDB($sql);
    }

    /**
     * Ermittelt alle Projekte (normale & Verwaltungsprojekte)
     *
     * @return DatabaseStorageObjektCollection
     */
    public static function getProjekte()
    {
        $sql = "SELECT
					*
				FROM 
					projekt
				WHERE
					proj_geloescht = 0 AND 
					proj_archiviert = 0";
        return ProjektUtilities::queryDB($sql);
    }

    /**
     * Gibt die normalen Projekte zurück.
     * Also die, die keine Verwaltungsprojekte sind
     *
     * @return DatabaseStorageObjektCollection
     */
    public static function getAnzeigeProjekte($where = "")
    {
        $sql = "SELECT
					p.*
				FROM 
					projekt p, gemeinde g
				WHERE
					p.g_id = g.g_id AND
					proj_geloescht = 0 AND 
					proj_archiviert = 0";
        $exclude = ConstantLoader::getVerwaltungsProjekte();
        
        foreach ($exclude as $id) {
            $sql .= " AND proj_id <> " . $id;
        }
        
        if ($where != "") {
            $sql .= " " . $where;
        }
        
        return ProjektUtilities::queryDB($sql);
    }

    /**
     * Interner Query
     *
     * @param String $sql            
     * @return DatabaseStorageObjektCollection
     */
    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new Projekt();
                $tmp->doLoadFromArray($objekt);
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }

    public static function updateProjektOrder($pid, $orderNr)
    {
        $sql = "UPDATE projekt SET proj_sortierung = " . $orderNr . " WHERE proj_id = " . $pid;
        DB::getInstance()->NonSelectQuery($sql);
    }

    public static function updateMitarbeiterOrder($beId, $orderNr)
    {
        $sql = "UPDATE benutzer SET be_sortierung = " . $orderNr . " WHERE be_id = " . $beId;
        DB::getInstance()->NonSelectQuery($sql);
    }
    
    public static function updateProjektAufgabeOrder($pProjektId, $pAufgabeId,  $pSortierung)
    {
        $sql = "UPDATE projekt_aufgabe SET pa_reihenfolge = " . $pSortierung . " WHERE proj_id = " . $pProjektId. " AND au_id = ".$pAufgabeId.";";
        echo $sql;
        DB::getInstance()->NonSelectQuery($sql);
    }
    
    
    
    public static function countMitarbeiterUeberstunden($pMitarbeiterId, $pJahr = null)
    {
        $sql = "SELECT sum(aw_stunden_dif) as ueberstunden FROM arbeitswoche WHERE be_id = " . $pMitarbeiterId;
        if($pJahr != null) {
            $sql .= " AND aw_jahr = ".$pJahr." ";
        }
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            return $res[0]['ueberstunden'];
        } else {
            return -1;
        }
        
    }
}

?>
