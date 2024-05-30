<?php

class OrgelUtilities
{

    /**
     * Fuer die Orgel-Liste und Orgel-Details.
     * 
     * @param Orgel|OrgelListeBean $pOrgel
     * @return string Beispiel: II/Pedal
     */
    public static function getOrgelManualeUebersicht($pOrgel)
    {
        // Manuale aus der Datenbank lesen
        if ($pOrgel->getManual5() == 1) {
            $manual = "V";
        } elseif ($pOrgel->getManual4() == 1) {
            $manual = "IV";
        } elseif ($pOrgel->getManual3() == 1) {
            $manual = "III";
        } elseif ($pOrgel->getManual2() == 1) {
            $manual = "II";
        } elseif ($pOrgel->getManual1() == 1) {
            $manual = "I";
        } else {
            $manual = "keine Manuale";
        }
        if ($pOrgel->getPedal() == 1) {
            $manual = $manual . "/Pedal";
        }
        return $manual;
    }

    public static function getOrgelManuale(Orgel $o)
    {
        $a = array();
        if ($o->getManual1() == 1)
            $a[1] = "1. Manual";
        if ($o->getManual2() == 1)
            $a[2] = "2. Manual";
        if ($o->getManual3() == 1)
            $a[3] = "3. Manual";
        if ($o->getManual4() == 1)
            $a[4] = "4. Manual";
        if ($o->getManual5() == 1)
            $a[5] = "5. Manual";
        if ($o->getPedal() == 1)
            $a[6] = "Pedal";
        return $a;
    }

    public static function getDruckAnsichtOrgeln($strOrderBy = null)
    {
        $sql = "SELECT
    			o_id, g.g_id, o_baujahr, o_erbauer, o_manual1, o_manual2, o_manual3, o_manual4, o_manual5, o_pedal, o_kostenhauptstimmung, o_kostenteilstimmung,
				o_zyklus, o_pflegevertrag, 
    			o_anzahlregister, o_letztepflege, g_kirche, b_id, ad_ort, ad_plz, a_vorname, a_name, a_funktion, a_telefon, a_id
			FROM
				orgel o
  					LEFT JOIN (SELECT ge.*, ad.* FROM gemeinde ge, adresse ad WHERE ge.g_kirche_aid = ad.ad_id AND ge.g_aktiv = 1) g ON o.g_id = g.g_id
    		        LEFT JOIN(SELECT * FROM ansprechpartner WHERE ansprechpartner.a_aktiv = 1) a ON g.a_hauptid = a.a_id
			WHERE
				o.o_aktiv =  1 ";
        if ($strOrderBy != null)
            $sql .= $strOrderBy;
        return OrgelUtilities::queryDruckAnsichtOrgeln($sql);
    }

    /**
     * Liste aller aktiven Orgeln
     *
     * @param string $strOrderBy            
     * @return ArrayList
     */
    public static function getOrgelListe($strOrderBy = null)
    {
        $sql = "SELECT
    			o_id, g.g_id, o_baujahr, o_erbauer, o_manual1, o_manual2, o_manual3, o_manual4, o_manual5, o_pedal, o_anzahlregister, o_letztepflege, g_kirche, b_id, ad_ort, ad_plz
			FROM
				orgel o
  					LEFT JOIN (SELECT ge.*, ad.* FROM gemeinde ge, adresse ad WHERE ge.g_kirche_aid = ad.ad_id AND ge.g_aktiv = 1) g ON o.g_id = g.g_id
			WHERE
				o.o_aktiv =  1 ";
        if ($strOrderBy != null)
            $sql .= $strOrderBy;
        return OrgelUtilities::queryDBOrgelGemeinde($sql);
    }

    /**
     * Liste aller aktiven Orgeln
     *
     * @param string $suchstring
     * @param array $orgelstatus 
     * @param string $strOrderBy
     * @return ArrayList
     */
    public static function getGesuchteOrgeln($suchstring, $orgelstatus = array(), $strOrderBy = null)
    {
        $sql = "SELECT
    			o_id, g.g_id, o_baujahr, o_erbauer, o_manual1, o_manual2, o_manual3, o_manual4, o_manual5, o_pedal, o_anzahlregister, o_letztepflege, g_kirche, b_id, ad_ort, ad_plz
			FROM
				orgel o
  					LEFT JOIN (SELECT ge.*, ad.* FROM gemeinde ge, adresse ad WHERE ge.g_kirche_aid = ad.ad_id AND ge.g_aktiv = 1) g ON o.g_id = g.g_id
			WHERE
				o.o_aktiv =  1 ";
        if($suchstring != "") {
            $sql .= " AND ( 
                    o_baujahr LIKE '%" . $suchstring . "%' OR
					o_erbauer LIKE '%" . $suchstring . "%' OR
                    o_massnahmen LIKE '%" . $suchstring . "%' OR
					g_kirche LIKE '" . $suchstring . "%' OR
					ad_ort LIKE '" . $suchstring . "' OR
					ad_plz LIKE '" . $suchstring . "'
			) ";
        }

        if(count($orgelstatus) > 0) {
            $sql .= " AND ( ";
            foreach($orgelstatus as $current) {
                $sql .= "o.ost_id = ".$current." OR ";
            }
            $sql = substr($sql,0, -3); // Das letzte OR abschneiden
            $sql .= " ) ";
        }
        if ($strOrderBy != null)
            $sql .= $strOrderBy;

        return OrgelUtilities::queryDBOrgelGemeinde($sql);
    }

    public static function getOrgelListeAnstehendeWartungen($pSQLAdd = null, $strOrderBy = null)
    {
        $sql = "SELECT 
					o.*,
					g.*,
					CASE
						WHEN DATE_ADD(o_letztepflege, INTERVAL o_zyklus YEAR) < '" . date("Y") . "-01-01' 
							THEN CONCAT(" . date("Y") . ", SUBSTR(o_letztepflege,5))
							ELSE DATE_ADD(o_letztepflege, INTERVAL o_zyklus YEAR)
					END AS naechstepflege
				FROM 
					orgel o LEFT JOIN 
						(SELECT ge.*, ad.* FROM gemeinde ge, adresse ad WHERE ge.g_kirche_aid = ad.ad_id AND ge.g_aktiv = 1) g					
					ON o.g_id = g.g_id 
				WHERE 
					o_letztepflege = '0000-00-00' OR (
					o.o_pflegevertrag = 1 AND
					o.o_aktiv = 1 )";
        if ($pSQLAdd != null) {
            $sql .= $pSQLAdd;
        }
        
        if ($strOrderBy != null) {
            $sql .= $strOrderBy;
        } else {
            $sql .= " ORDER BY 
      				naechstepflege ASC,
      				g.b_id ASC,
      		        g.ad_ort ASC";
        }
        return OrgelUtilities::queryDBOrgelGemeinde($sql);
    }

    public static function getOrgelListeEingeplanteWartungen($pSQLAdd = null, $strOrderBy = null)
    {
        // Missbrauch des Baujahrs um die WartungsId zu uebertragen
        $sql = "SELECT
					o.*,
					g.*,
                    w.w_id as o_baujahr
				FROM
                    wartung w,
					orgel o LEFT JOIN
						(SELECT ge.*, ad.* FROM gemeinde ge, adresse ad WHERE ge.g_kirche_aid = ad.ad_id AND ge.g_aktiv = 1) g
					ON o.g_id = g.g_id
				WHERE
                    w.w_changeby = 'system' AND
                    w.o_id = o.o_id
			    ORDER BY
      				g.b_id ASC,
      		        g.ad_ort ASC";
        return OrgelUtilities::queryDBOrgelGemeinde($sql);
    }

    public static function getGemeindeOrgeln($gid, $strOrderBy = null)
    {
        $sql = "SELECT * FROM orgel o WHERE o.g_id = " . $gid . " AND o.o_aktiv = '1' ";
        if ($strOrderBy == null)
            $sql .= $strOrderBy;
        return OrgelUtilities::queryDB($sql);
    }

    public static function getOrgeln($strOrderBy = null)
    {
        $sql = "SELECT * FROM orgel o WHERE o.o_aktiv = '1' ";
        if ($strOrderBy == null)
            $sql .= $strOrderBy;
        return OrgelUtilities::queryDB($sql);
    }

    public static function getAnzahlOrgeln()
    {
        $sql = "SELECT o_id FROM orgel WHERE o_aktiv = 1";
        $oDB = DB::getInstance();
        $res = $oDB->SelectQuery($sql);
        return ($res ? count($res) : 0);
    }

    private static function queryDB($sql)
    {
        $oCol = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $rs) {
                $tmp = new Orgel();
                $tmp->doLoadFromArray($rs);
                
                $tmp->setChangeAt($rs['o_lastchange']);
                $tmp->setCreatedAt($rs['o_createdate']);
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }

    /**
     * queried
     *
     * @param string $sql            
     * @return ArrayList
     */
    private static function queryDruckAnsichtOrgeln($sql)
    {
        $oCol = new ArrayList();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $rs) {
                $tmp = new OrgelListeExportBean();
                $tmp->init($rs);
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }

    /**
     * queried
     *
     * @param string $sql            
     * @return ArrayList
     */
    private static function queryDBOrgelGemeinde($sql)
    {
        $oDB = DB::getInstance();
        $oCol = new ArrayList();
        $res = $oDB->SelectQuery($sql);
        if ($res != null) {
            foreach ($res as $rs) {
                $tmp = new OrgelListeBean();
                $tmp->init($rs);
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }
}

