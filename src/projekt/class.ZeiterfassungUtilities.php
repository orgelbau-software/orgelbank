<?php

class ZeiterfassungUtilities
{

    private static $dbInstance;

    private static function createExcludeBenutzerStatement($pid)
    {
        $p = new Projekt($pid);
        $b = explode(",", $p->getKeineZeitenFuer());
        $strWhere = "";
        foreach ($b as $id) {
            if ($id != "")
                $strWhere .= "b.be_id <> " . $id . " AND ";
        }
        return $strWhere;
    }

    public static function getProjektMitarbeiterStundenSumme($pid, $aid, $beachteExclude = true)
    {
        $exclude = "";
        if ($beachteExclude)
            $exclude = ZeiterfassungUtilities::createExcludeBenutzerStatement($pid);
        $sql = "SELECT 
    			be_benutzername, sum(lohnkosten) as lohnkosten, sum(at_stunden_ist) as stunden
    		FROM (
				SELECT 
					proj_id, a.au_id, au_parentid, a.be_id, at_stunden_ist, be_std_lohn, at_stunden_ist * be_std_lohn AS lohnkosten, b.be_benutzername 
				FROM 
					arbeitstag a, benutzer b, aufgabe aufg
				WHERE
					b.be_id = a.be_id AND
					aufg.au_id = a.au_id AND
					proj_id = " . $pid . " AND
					" . $exclude . "
					aufg.au_id = " . $aid . "
				) x 
			GROUP BY 
				be_benutzername";
        $res = DB::getInstance()->SelectQuery($sql);
        $retVal = array();
        if ($res != null && $res !== false) {
            foreach ($res as $curr) {
                $retVal[$curr['be_benutzername']] = array(
                    "lohnkosten" => $curr['lohnkosten'],
                    "stunden" => $curr['stunden']
                );
            }
        }
        return $retVal;
    }

    public static function getProjektAufgabeLohnkosten($pid, $aid, $beachteExclude = true)
    {
        $exclude = "";
        if ($beachteExclude)
            $exclude = ZeiterfassungUtilities::createExcludeBenutzerStatement($pid);
        $sql = "SELECT 
    			au_id, sum(lohnkosten) as lohnkosten, sum(at_stunden_ist) as stunden
    		FROM (
				SELECT 
					proj_id, a.au_id, au_parentid, a.be_id, at_stunden_ist, be_std_lohn, at_stunden_ist * be_std_lohn AS lohnkosten 
				FROM 
					arbeitstag a, benutzer b, aufgabe aufg
				WHERE
					b.be_id = a.be_id AND
					aufg.au_id = a.au_id AND
					proj_id = " . $pid . " AND
					" . $exclude . "
					aufg.au_parentid = " . $aid . "
				) x 
			GROUP BY 
				au_id";
        $res = DB::getInstance()->SelectQuery($sql);
        $retVal = array();
        if ($res != null && $res !== false) {
            foreach ($res as $curr) {
                $retVal[$curr['au_id']] = array(
                    "lohnkosten" => $curr['lohnkosten'],
                    "stunden" => $curr['stunden']
                );
            }
        }
        return $retVal;
    }

    public static function getProjektLohnkosten($pid, $beachteExclude = true)
    {
        $exclude = "";
        if ($beachteExclude)
            $exclude = ZeiterfassungUtilities::createExcludeBenutzerStatement($pid);
        $sql = "SELECT 
    			sum(lohnkosten) as lohnkosten
    		FROM (
				SELECT 
					proj_id, a.au_id, au_parentid, a.be_id, at_stunden_ist, be_std_lohn, at_stunden_ist * be_std_lohn AS lohnkosten 
				FROM 
					arbeitstag a, benutzer b, aufgabe aufg
				WHERE
					b.be_id = a.be_id AND
					aufg.au_id = a.au_id AND
					" . $exclude . "
					proj_id = " . $pid . " 
				) x";
        
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            return $res[0]['lohnkosten'];
        }
        return 0;
    }

    public static function getProjektLohnkostenByHauptaufgabe($pid, $beachteExclude = true)
    {
        $exclude = "";
        if ($beachteExclude) {
            $exclude = ZeiterfassungUtilities::createExcludeBenutzerStatement($pid);
        }
        
        $nurGebuchteStundenSQL = "";
        if(ConstantLoader::getProjektZeitenNurGebuchteStundenBeruecksichtigen() == true) {
            $nurGebuchteStundenSQL = " a.at_status = ".Arbeitstag::$STATUS_GEBUCHT. " AND ";
        } else {
            $nurGebuchteStundenSQL = "";
        }
        $sql = "SELECT 
    			au_parentid, sum(lohnkosten) as lohnkosten
    		FROM (
				SELECT 
					proj_id, a.au_id, au_parentid, a.be_id, at_stunden_ist, be_std_lohn, at_stunden_ist * be_std_lohn AS lohnkosten 
				FROM 
					arbeitstag a, benutzer b, aufgabe aufg
				WHERE
					b.be_id = a.be_id AND
					aufg.au_id = a.au_id AND ".$nurGebuchteStundenSQL."
					" . $exclude . "
					proj_id = " . $pid . " 
				) x 
			GROUP BY 
				au_parentid";
        $res = DB::getInstance()->SelectQuery($sql);
        $retVal = array();
        if ($res != null && $res !== false) {
            foreach ($res as $curr) {
                $retVal[$curr['au_parentid']] = $curr['lohnkosten'];
            }
        }
        return $retVal;
    }

    
    public static function getBenutzerProjektAufgabenImZeitraum($iBenutzerID, $startDatum, $endDatum) {
        
        
        $sql = "SELECT DISTINCT
					p.proj_id,
                    g.g_id,  
                    g.g_kirche, 
                    p.proj_bezeichnung,
                    b.be_id,  
                    b.be_benutzername,
                    a.au_id as unter_id, 
                    a.au_bezeichnung as unter_bez, 
                    CASE WHEN ISNULL(a2.au_id) THEN a.au_id ELSE a2.au_id END as haupt_id, 
                    CASE WHEN ISNULL(a2.au_bezeichnung) THEN '' ELSE a2.au_bezeichnung END as haupt_bez, 
                    pa.pa_sollstunden, 
                    pa.pa_iststunden
				FROM 
                    arbeitstag t, 
                    projekt p, 
                    gemeinde g, 
                    benutzer b, 
                    projekt_aufgabe pa, 
                    aufgabe a left join aufgabe a2 on a.au_parentid = a2.au_id 
                WHERE 
                    (t.at_datum BETWEEN CAST('".$startDatum."' AS DATE) AND CAST('".$endDatum."' AS DATE)) 
                    AND t.be_id = ".$iBenutzerID."
                    and t.proj_id = p.proj_id 
                    and p.g_id = g.g_id 
                    and t.be_id = b.be_id 
                    and t.au_id = a.au_id 
                    and pa.proj_id = p.proj_id 
                    and pa.au_id = a2.au_id";
        
        // Fuehrt dazu, dass nur Aufgaben angezeigt werden, die auch eine Unteraufgabe haben
        //$sql .= " AND a2.au_parentid <> 0 ";
        
        $sql .= " ORDER BY
					g_kirche,
					p.proj_bezeichnung,
					a2.au_bezeichnung,
					a.au_bezeichnung";
        
        return ZeiterfassungUtilities::queryDB($sql);
        
    }
    public static function getBenutzerProjektAufgaben($iBenutzerID, $iProjektID)
    {
        $sql = "SELECT
					b.be_id, 
					b.be_benutzername, 
					p.proj_id,
					p.proj_bezeichnung, 
					a.au_id as haupt_id, 
					a.au_bezeichnung as haupt_bez,
					CASE WHEN ISNULL(a2.au_id) THEN a.au_id ELSE a2.au_id END as unter_id,
					CASE WHEN ISNULL(a2.au_bezeichnung) THEN '' ELSE a2.au_bezeichnung END as unter_bez,
					g.g_id,
					g.g_kirche,
                    pa.pa_sollstunden,
                    pa.pa_iststunden
				FROM
					benutzer b, 
					aufgabe_mitarbeiter am, 
					projekt p, 
					gemeinde g, 
					projekt_aufgabe pa, 
					aufgabe a LEFT JOIN aufgabe a2 on a.au_id = a2.au_parentid AND a2.au_geloescht = 0
				WHERE b.be_id = " . $iBenutzerID . " 
					AND p.proj_id = " . $iProjektID . " 
					AND b.be_id = am.be_id
					AND a.au_id = am.au_id
					AND p.proj_id = pa.proj_id 
					AND pa.au_id = a.au_id
					AND p.g_id = g.g_id
					AND a.au_geloescht = 0
					AND p.proj_geloescht = 0
					AND p.proj_archiviert = 0";
        
        // Fuehrt dazu, dass nur Aufgaben angezeigt werden, die auch eine Unteraufgabe haben
        $sql .= " AND a2.au_parentid <> 0 ";
        
        $sql .= "ORDER BY
					g_kirche,
					p.proj_bezeichnung, 
					a.au_bezeichnung,
					a2.au_bezeichnung";
        return ZeiterfassungUtilities::queryDB($sql);
    }

    public static function getBenutzerAufgaben($iBenutzerID)
    {
        $sql = "SELECT
					b.be_id, 
					b.be_benutzername, 
					p.proj_id,
					p.proj_bezeichnung, 
					a.au_id as haupt_id, 
					a.au_bezeichnung as haupt_bez,
					CASE WHEN ISNULL(a2.au_id) THEN a.au_id ELSE a2.au_id END as unter_id,
					CASE WHEN ISNULL(a2.au_bezeichnung) THEN '' ELSE a2.au_bezeichnung END as unter_bez,
					g.g_id,
					g.g_kirche,
                    pa.pa_sollstunden,
                    pa.pa_iststunden
				FROM
					benutzer b, 
					aufgabe_mitarbeiter am, 
					projekt p, 
					gemeinde g, 
					projekt_aufgabe pa, 
					aufgabe a LEFT JOIN aufgabe a2 on a.au_id = a2.au_parentid AND a2.au_geloescht = 0
				WHERE b.be_id = " . $iBenutzerID . " 
					AND b.be_id = am.be_id
					AND a.au_id = am.au_id
					AND p.proj_id = pa.proj_id 
					AND pa.au_id = a.au_id
					AND p.g_id = g.g_id
					AND a.au_geloescht = 0
					AND p.proj_geloescht = 0
					AND p.proj_archiviert = 0 
				ORDER BY
					g_kirche,
					p.proj_bezeichnung, 
					a.au_bezeichnung,
					a2.au_bezeichnung";
        return ZeiterfassungUtilities::queryDB($sql);
    }

    private static function queryDB($sql)
    {
        $oDSOC = new ArrayList();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $o) {
                $tmp = new ZeiterfassungDTO();
                $tmp->setBenutzerID($o['be_id']);
                $tmp->setBenutzername($o['be_benutzername']);
                $tmp->setGemeindeID($o['g_id']);
                $tmp->setGemeindeBezeichnung($o['g_kirche']);
                $tmp->setHauptaufgabeID($o['haupt_id']);
                $tmp->setHauptaufgabeBezeichnung($o['haupt_bez']);
                $tmp->setUnteraufgabeID($o['unter_id']);
                $tmp->setUnteraufgabeBezeichnung($o['unter_bez']);
                $tmp->setProjektID($o['proj_id']);
                $tmp->setProjektBezeichnung($o['proj_bezeichnung']);
                $tmp->setSollStunden($o['pa_sollstunden']);
                $tmp->setIstStunden($o['pa_iststunden']);
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }
}

?>
