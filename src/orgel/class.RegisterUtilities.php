<?php

class RegisterUtilities
{

    /**
     * Gibt die häufigsten Register in der Orgelbank zurück
     *
     * @param int $count            
     * @return DatabaseStorageObjektCollection
     */
    public static function getTOPRegister($count = 20)
    {
        $sql = "SELECT * 
    			FROM (
    				SELECT 
            			d.*, count(*) as anzahl, rg_reihenfolge 
            		FROM 
            			disposition d, register_groessen rg
            		WHERE 
            			d.d_fuss = rg.rg_fuss
            		GROUP 
            			BY d_name 
            		ORDER BY 
            			count(*) DESC
            		LIMIT 0," . $count . ") as x, register_groessen rg
            WHERE
            	x.d_fuss = rg.rg_fuss
    		ORDER BY
    			d_name ASC ";
        return RegisterUtilities::queryDB($sql);
    }

    /**
     * Liefert die am meisten verwendeten Register und klammert die aus, die bereits in der Orgel enthalten sind
     *
     * @param unknown_type $orgelID            
     * @param unknown_type $count            
     * @return unknown
     */
    public static function getTOPRegisterFuerOrgel($orgelID, $count = 20)
    {
        $sql = "SELECT * 
    			FROM (
    				SELECT 
            			d.*, count(*) as anzahl, rg_reihenfolge 
            		FROM 
            			disposition d, register_groessen rg
            		WHERE 
            			d.d_fuss = rg.rg_fuss 
            				AND d_id NOT IN (
            					SELECT d_id FROM disposition WHERE o_id = " . $orgelID . ")
            		GROUP 
            			BY d_name 
            		ORDER BY 
            			count(*) DESC
            		LIMIT 0," . $count . ") as x, register_groessen rg
            WHERE
            	x.d_fuss = rg.rg_fuss
    		ORDER BY
    			rg.rg_reihenfolge ASC ";
        FB::log($sql, "Register TOP");
        return RegisterUtilities::queryDB($sql);
    }

    /**
     * Prüft ob ein Register mit der ID existiert.
     *
     * @param int $id            
     * @return boolean
     */
    public static function exists($id)
    {
        $sql = "SELECT * FROM disposition WHERE d_id= " . $id;
        $oDB = DB::getInstance();
        $oDB->connect();
        $r = $oDB->getMysqlNumRows($sql);
        return $r > 0;
    }

    public static function getRegisterGroessen()
    {
        $oDB = DB::getInstance();
        $sql = "SELECT rg_fuss, rg_reihenfolge FROM register_groessen ORDER BY rg_reihenfolge";
        
        $a = new ArrayList();
        if (($r = $oDB->SelectQuery($sql)) !== false) {
            foreach ($r as $g) {
                $a->add(new RegisterGroessenBean($g['rg_reihenfolge'], $g['rg_fuss']));
            }
        }
        return $a;
    }

    public static function getLetztesEingepflegtesRegister($iOrgelID)
    {
        $sql = "SELECT * FROM disposition WHERE o_id = " . $iOrgelID . " ORDER BY d_lastchange DESC, d_id DESC LIMIT 1";
        $c = RegisterUtilities::queryDB($sql);
        $retVal = $c->getValueOf(0);
        
        // wenn zu einer Orgel noch kein Register existiert, dann ist es NULL
        if ($retVal == null) {
            $retVal = new Register();
        }
        return $retVal;
    }

    public static function getNaechsteRegisterPosition($iOrgelID, $iManualID)
    {
        if ($iManualID == null || $iManualID == 0) {
            return 1;
        }
        $sql = "SELECT max(d_reihenfolge)+1 as max FROM disposition WHERE o_id = " . $iOrgelID . " AND m_id = " . $iManualID;
        $oDB = DB::getInstance();
        if (($r = DB::getInstance()->SelectQuery($sql)) !== false) {
            return $r[0]['max'] != "" ? $r[0]['max'] : 1;
        }
        return 1;
    }

    public static function getRegisterAnzahl($iOrgelID)
    {
        $sql = "SELECT count(*) as count FROM disposition WHERE o_id = " . $iOrgelID . " AND d_name <> 'Tremulant'";
        if (($r = DB::getInstance()->SelectQuery($sql)) !== false) {
            return $r[0]['count'] != "" ? $r[0]['count'] : 1;
        }
        return 1;
    }

    public static function ladeOrgelRegister($iOrgelID, $strOrderBy = null)
    {
        $sql = "SELECT * FROM disposition WHERE o_id = " . $iOrgelID . " ";
        if ($strOrderBy != null)
            $sql .= $strOrderBy;
        return RegisterUtilities::queryDB($sql);
    }

    public static function getDispositionAsArray($orgelID)
    {
        $sql = "SELECT * FROM disposition WHERE o_id = " . $orgelID . " ORDER BY m_id ASC, d_reihenfolge ASC";
        $disposition = RegisterUtilities::queryDB($sql);
        $retVal = array();
        foreach ($disposition as $register) {
            if (! isset($retVal[$register->getManual()])) {
                // $retVal[$register->getManual()] = $register->getManual() < 6 ? $register->getManual() . " Manual" : "Pedal";
                $retVal[$register->getManual()] = array();
            }
            
            $retVal[$register->getManual()][] = array(
                0 => $register->getManual() < 6 ? $register->getManual() . ". Manual" : "Pedal",
                1 => $register->getName(),
                2 => $register->getFuss()
            );
        }
        return $retVal;
    }

    /**
     * Query mit Anzahl
     *
     * @param String $sql            
     * @return DatabaseStorageObjektCollection
     */
    private static function queryDB($sql)
    {
        $oDB = DB::getInstance();
        $oCol = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $rs) {
                $tmp = new Register();
                $tmp->doLoadFromArray($rs);
//                 $tmp->setID($rs['d_id']);
//                 $tmp->setOrgelID($rs['o_id']);
//                 $tmp->setManual($rs['m_id']);
//                 $tmp->setName($rs['d_name']);
//                 $tmp->setFuss($rs['d_fuss']);
//                 $tmp->setTyp($rs['d_typ']);
//                 $tmp->setReihenfolge($rs['d_reihenfolge']);
//                 $tmp->setChangeAt($rs['d_lastchange']);
//                 $tmp->setCreatedAt($rs['d_createdate']);
                
                if (isset($rs['anzahl']))
                    $tmp->setAnzahl($rs['anzahl']);
                
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }
}
?>
