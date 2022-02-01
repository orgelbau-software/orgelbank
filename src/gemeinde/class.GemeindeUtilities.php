<?php

class GemeindeUtilities
{

    /**
     * Added "NaechstePflege" am 18.02.2018
     * 
     * @param string $pPflegevertrag            
     * @param string $pOffeneWartungen            
     * @param string $pBezirkId            
     * @return ArrayList
     */
    public static function loadGemeindeLandkarte($pPflegevertrag = "", $pOffeneWartungen = "", $pBezirkId = "")
    {
        $sql = "SELECT
			 o.o_id, o.o_anzahlregister, o.o_massnahmen, o.o_letztepflege, o.o_pflegevertrag, o.o_zyklus, g.g_kirche, g.b_id, a.*, 
                CASE
					WHEN DATE_ADD(o_letztepflege, INTERVAL o_zyklus YEAR) < '" . date("Y") . "-01-01' 
					THEN CONCAT(" . date("Y") . ", SUBSTR(o_letztepflege,5))
					ELSE DATE_ADD(o_letztepflege, INTERVAL o_zyklus YEAR)
					END AS naechstepflege
		FROM
			orgel o,
			gemeinde g,
			adresse a
		WHERE
    		o.o_aktiv = 1 AND
			o.g_id = g.g_id AND
			g.g_kirche_aid = a.ad_id AND
            o.o_letztepflege < '" . date("Y") . "-01-01' AND
			(a.ad_geostatus = 'OK' OR a.ad_geostatus = 'PARTIAL_OK') ";
        if ($pBezirkId != "") {
            $sql .= " AND b_id = " . $pBezirkId . " ";
        }
        if ($pPflegevertrag != "") {
            $sql .= " AND o.o_pflegevertrag = " . $pPflegevertrag . " ";
        }
        
        $sql .= " ORDER BY
		  ad_id ";
        return GemeindeUtilities::queryGemeindeKarteBean($sql);
    }

    public static function generateGemeindeCopyJS()
    {
        $tpl = new Template("js_gemeinde_copy.tpl");
        $tplDS = new BufferedTemplate("js_gemeinde_copy_ds.tpl");
        $c = KonfessionUtilities::getKonfessionen(" ORDER BY k_name ASC");
        
        foreach ($c as $konf) {
            $tplDS->replace("KID", $konf->getID());
            $tplDS->replace("Bezeichnung", $konf->getBezeichnung() . "e Kirchengemeinde");
            $tplDS->next();
        }
        
        $tpl->replace("Konfessionen", $tplDS->getOutput());
        return $tpl;
    }

    public static function getAnsprechpartnerGemeinden($iAnsprechpartnerID, $strOrderBy = null)
    {
        $sql = "SELECT 
    		  g.g_id, g.g_kirche, a.ad_ort
    	   FROM 
    		  gemeinde g, 
    		  gemeindeansprechpartner ga,
    		  adresse a
    		WHERE 
    		  g.g_id = ga.g_id AND
    		  g.g_kirche_aid = a.ad_id AND  
    		  ga.a_id = " . $iAnsprechpartnerID;
        if ($strOrderBy != null)
            $sql .= $strOrderBy;
        return GemeindeUtilities::queryDBForKircheOrtBean($sql);
    }

    public static function getDruckAnsichtGemeinden($strOrderBy = null)
    {
        $sql = "SELECT
					g.*, ad.*, a.*
				FROM
					gemeinde g LEFT JOIN ansprechpartner a ON g.a_hauptid = a.a_id,
    		        adresse ad 
				WHERE 
    		        g.g_kirche_aid = ad.ad_id AND
					g_aktiv = 1 ";
        if ($strOrderBy != null)
            $sql .= $strOrderBy;
        return GemeindeUtilities::queryDBmitHAP($sql);
    }

    public static function getGemeinden($strOrderBy = null)
    {
        $sql = "SELECT 
                    * 
                FROM 
                    gemeinde g, 
                    adresse a 
                WHERE 
                    g.g_kirche_aid = a.ad_id AND 
                    g.g_aktiv = 1 ";
        if ($strOrderBy != null)
            $sql .= $strOrderBy;
        return GemeindeUtilities::queryDBForKircheOrtBean($sql);
    }

    public static function getGemeindenAusserVonAnsprechpartner($ansprechpartnerID, $strOrderBy = null)
    {
        $sql = "SELECT DISTINCT
    			g.g_id, g.g_kirche, a.ad_ort 
    		FROM 
    			gemeinde g,
    			adresse a
    		WHERE 
    			g.g_kirche_aid = a.ad_id AND 
    			g.g_aktiv = 1 AND
    		    g_id NOT IN (SELECT g_id FROM gemeindeansprechpartner ga WHERE ga.a_id = " . $ansprechpartnerID . ") ";
        if ($strOrderBy != null)
            $sql .= $strOrderBy;
        return GemeindeUtilities::queryDBForKircheOrtBean($sql);
    }

    public static function getGesuchteGemeinden($suchstring, $strOrderBy = null)
    {
        $sql = "SELECT
					g.*, ad.*
				FROM
					gemeinde g, adresse ad
				WHERE
  			        g_kirche_aid = ad.ad_id AND 
					g_aktiv = 1 AND (
					g_kirche LIKE '%" . $suchstring . "%' OR
					ad_ort LIKE '%" . $suchstring . "%' OR
					ad_plz LIKE '" . $suchstring . "%' OR
					b_id LIKE '" . $suchstring . "' OR
					k_id LIKE '" . $suchstring . "'
					) ";
        if ($strOrderBy != null)
            $sql .= $strOrderBy;
        return GemeindeUtilities::queryGemeindeListeBean($sql);
    }

    public static function getAnzahlGemeinden()
    {
        $sql = "SELECT COUNT(*) as anzahl FROM gemeinde WHERE g_aktiv = 1";
        $oDB = DB::getInstance();
        if (($r = $oDB->SelectQuery($sql)) !== false) {
            return $r[0]['anzahl'];
        }
        return - 1;
    }

    private static function queryDB($sql)
    {
        $oCol = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $rs) {
                $tmp = new Gemeinde();
                $tmp->setID($rs['g_id']);
                $tmp->setKID($rs['k_id']);
                $tmp->setKirche($rs['g_kirche']);
                // $tmp->setStrasse($rs['g_strasse']);
                // $tmp->setHausnummer($rs['g_hausnummer']);
                // $tmp->setPLZ($rs['g_plz']);
                // $tmp->setOrt($rs['g_ort']);
                $tmp->setRAnschrift($rs['g_ranschrift']);
                $tmp->setRGemeinde($rs['g_rgemeinde']);
                // $tmp->setRStrasse($rs['g_rstrasse']);
                // $tmp->setRHausnummer($rs['g_rhausnummer']);
                // $tmp->setRPLZ($rs['g_rplz']);
                // $tmp->setROrt($rs['g_rort']);
                $tmp->setBID($rs['b_id']);
                $tmp->setDistanz($rs['b_distanz']);
                $tmp->setFahrtzeit($rs['b_fahrzeit']);
                $tmp->setAktiv($rs['g_aktiv']);
                $tmp->setAID($rs['a_hauptid']);
                $tmp->setKircheAdressId($rs['g_kirche_aid']);
                $tmp->setRechnungAdressId($rs['g_rechnung_aid']);
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }

    private static function queryDBForKircheOrtBean($sql)
    {
        $oCol = new ArrayList();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $rs) {
                $tmp = new KircheOrtBean();
                $tmp->init($rs);
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }

    private static function queryGemeindeListeBean($sql)
    {
        $oCol = new ArrayList();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $rs) {
                $tmp = new GemeindeListeBean();
                $tmp->init($rs);
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }

    private static function queryGemeindeKarteBean($sql)
    {
        $oCol = new ArrayList();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $rs) {
                $tmp = new GemeindeKarteBean();
                $tmp->init($rs);
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }

    private static function queryDBmitHAP($sql)
    {
        $oCol = new ArrayList();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $rs) {
                $tmp = new GemeindeListeExportBean();
                $tmp->init($rs);
                
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }
}

?>
