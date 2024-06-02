<?php

class AnsprechpartnerUtilities
{

    public static function existiertVerbindung($iAnsprechpartnerID, $iGemeindeID)
    {
        $sql = "SELECT * FROM gemeindeansprechpartner ga WHERE ga.g_id = " . $iGemeindeID . " AND ga.a_id = " . $iAnsprechpartnerID . ";";
        $oDB = DB::getInstance();
        $res = $oDB->SelectQuery($sql);
        if($res === false) {
            return false;
        } else if (0 == sizeof($res)) {
            return false;
        } else {
            return true;
        }
    }

    public static function neuerGemeindeAnsprechpartner($iAnsprechpartnerID, $iGemeindeID)
    {
        $oDB = DB::getInstance();
        // Ein Update versuchen, wenn noch kein HAP eingetragen ist. Ist bereits einer eingetragen, aendert das Stmt nichts
        $sql = "UPDATE gemeinde SET a_hauptid = " . $iAnsprechpartnerID . " WHERE g_id=" . $iGemeindeID . " AND a_hauptid = ''";
        $oDB->NonSelectQuery($sql);
        
        $sql = "INSERT INTO gemeindeansprechpartner VALUES(" . $iGemeindeID . ", " . $iAnsprechpartnerID . ")";
        $oDB->NonSelectQuery($sql);
    }

    /**
     * Löscht den Ansprechpartner zur Gemeinde<br/>
     * Setzt implizit auch den Hauptansprechpartner der Gemeinde zurück.
     *
     * @param int $iAnsprechpartnerID            
     * @param int $iGemeindeID            
     */
    public static function loescheGemeindeAnsprechpartner($iAnsprechpartnerID, $iGemeindeID)
    {
        $oDB = DB::getInstance();
        $sql = "DELETE FROM gemeindeansprechpartner WHERE g_id = " . $iGemeindeID . " AND a_id = " . $iAnsprechpartnerID;
        $oDB->NonSelectQuery($sql);
        $sql = "UPDATE gemeinde SET a_hauptid = '' WHERE g_id = " . $iGemeindeID . " AND a_hauptid = " . $iAnsprechpartnerID;
        $oDB->NonSelectQuery($sql);
        
        $sql = "UPDATE gemeinde SET a_hauptid = (SELECT ga.a_id FROM gemeindeansprechpartner ga, ansprechpartner a WHERE ga.a_id = a.a_id AND a.a_aktiv = 1 AND ga.g_id=" . $iGemeindeID . " LIMIT 1) WHERE g_id = " . $iGemeindeID;
        $oDB->NonSelectQuery($sql);
    }

    public static function getSuchAnsprechpartner($strSuchbegriff, $strOrderBy = null)
    {
        $sql = "SELECT 
					a.*,
                    CASE
                        WHEN a_name <> \"\" THEN a_name
                        WHEN a_firma <> \"\" THEN a_firma
                        ELSE a_name
                    END anzeigename
				FROM 
					ansprechpartner a LEFT JOIN adresse ad ON a.ad_id = ad.ad_id
				WHERE
					a.a_id <> 1 AND
					a_aktiv = 1 AND(
					a_anrede LIKE '%" . $strSuchbegriff . "%' OR
					a_funktion LIKE '%" . $strSuchbegriff . "%' OR
					a_vorname LIKE '%" . $strSuchbegriff . "%' OR
					a_name LIKE '%" . $strSuchbegriff . "%' OR
                    a_firma LIKE '%" . $strSuchbegriff . "%' OR
					ad_plz LIKE '%" . $strSuchbegriff . "%') ";
        if ($strOrderBy != null)
            $sql .= $strOrderBy;
        return AnsprechpartnerUtilities::queryDB($sql);
    }

    public static function getAktiveAnsprechpartner($strOrderBy = null)
    {
        $sql = "SELECT
					a.*
				FROM
					ansprechpartner a LEFT JOIN adresse ad ON a.ad_id = ad.ad_id
				WHERE
					a.a_id <> 1 AND
					a_aktiv = 1 ";
        if ($strOrderBy != null)
            $sql .= $strOrderBy;
        return AnsprechpartnerUtilities::queryDB($sql);
    }

    /**
     *
     * @param int $gid            
     * @param string $strOrderBy            
     * @return DatabaseStorageObjektCollection
     */
    public static function getGemeindeAnsprechpartner($gid, $strOrderBy = null)
    {
        $sql = "SELECT * FROM ansprechpartner a, gemeindeansprechpartner ga WHERE ga.a_id = a.a_id AND a.a_aktiv = 1 AND ga.g_id = " . $gid . " ";
        if ($strOrderBy != null)
            $sql .= $strOrderBy;
        return AnsprechpartnerUtilities::queryDB($sql);
    }

    /**
     * Gibt die Daten des Kunden zurück
     *
     * @return Ansprechpartner
     */
    public static function getKunde()
    {
        $id = ConstantLoader::getKundeId();
        return new Ansprechpartner($id);
    }

    /**
     * Enter description here...
     *
     * @param string $sql            
     * @return DatabaseStorageObjektCollection
     */
    private static function queryDB($sql)
    {
        $oCol = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $rs) {
                $tmp = new Ansprechpartner();
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
