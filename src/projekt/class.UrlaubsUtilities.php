<?php

class UrlaubsUtilities
{

    public static function bucheBenutzerUrlaub($pBenutzer, $pDatum, $pHalberOderGanzerTag)
    {
        return UrlaubsUtilities::bucheUrlaub($pBenutzer, $pDatum, $pDatum, $pHalberOderGanzerTag, Urlaub::TYP_URLAUB, Urlaub::STATUS_ZEITERFASSUNG, "");
    }

    /**
     * 
     * @param int $pBenutzerID
     * @param string $pDatumVon Y-m-d
     * @param string $pDatumBis Y-m-d
     * @param int $pTage
     * @param int $pUrlaubsTyp
     * @param string $pBemerkung
     * @return HTMLStatus|boolean
     */
    public static function bucheUrlaub($pBenutzerID, $pDatumVon, $pDatumBis, $pTage, $pUrlaubsTyp, $pStatus, $pBemerkung = "")
    {
        $bereitsExistierenderUrlaubsEintrag = UrlaubsUtilities::getBestimmtenUrlaubsEintragBenutzer($pBenutzerID, $pDatumVon);
        if($bereitsExistierenderUrlaubsEintrag != null) {
            return new HTMLStatus("Fuer den Tag ".$pDatumVon." gibt es bereits einen eingetragenen Urlaubstag.", HTMLStatus::$STATUS_ERROR, false);
        }
        
        $letzterUrlaub = UrlaubsUtilities::getLetzterUrlaubsEintrag($pBenutzerID);
        if ($letzterUrlaub == null) {
            return new HTMLStatus("Der letzte Urlaubstag für " . $pBenutzerID . " konnte nicht ermittelt werden.", false);
        }
        
        if (UrlaubsUtilities::isKorrekturOrZusatzBuchung($pUrlaubsTyp) && $pBemerkung == "") {
            return new HTMLStatus("Bei Korrekturbuchungen muss eine Bemerkung angegeben werden.", HTMLStatus::$STATUS_ERROR, false);
        }
        
        $urlaub = new Urlaub();
        $urlaub->setBenutzerId($pBenutzerID);
        $urlaub->setBemerkung(htmlspecialchars($pBemerkung));
        $urlaub->setTage(intval($pTage));
        
        $urlaub->setDatumVon($pDatumVon);
        if ($pDatumBis == "") {
            $urlaub->setDatumBis(null);
        } else {
            $urlaub->setDatumBis($pDatumBis);
        }
        
        // Wichtig fuer Korrekturen
        $urlaub->setResturlaub($letzterUrlaub->getResturlaub());
        
        if (UrlaubsUtilities::isKorrekturOrZusatzBuchung($pUrlaubsTyp)) {
            $rest = $urlaub->getTage() * - 1;
        } else {
            $rest = $urlaub->getTage();
        }
        
        if (! UrlaubsUtilities::isKorrekturOrZusatzBuchung($pUrlaubsTyp) && $letzterUrlaub->getResturlaub() > 0) {
            if ($rest > $letzterUrlaub->getResturlaub()) {
                $urlaub->setResturlaub(0);
                $rest = $rest - $letzterUrlaub->getResturlaub();
            } else {
                $urlaub->setResturlaub($letzterUrlaub->getResturlaub() - $rest);
                $rest = 0;
            }
        }
        
        if ($rest > 0 && $letzterUrlaub->getVerbleibend() < $rest) {
            return new HTMLStatus("Der Mitarbeiter möchte " . $rest . " Tage Urlaub buchen, hat aber nur noch " . $urlaub->getVerbleibend() . " Tage übrig (Resturlaub: " . $urlaub->getResturlaub() . ")", HTMLStatus::$STATUS_WARN, false);
        } else {
            $urlaub->setVerbleibend($letzterUrlaub->getVerbleibend() - $rest);
            $urlaub->setSumme($urlaub->getVerbleibend() + $urlaub->getResturlaub());
            $urlaub->setStatus($pStatus);
            $urlaub->speichern();
        }
        return true;
    }

    public static function getLetzteUrlaubsTagsIdProBenutzer()
    {
        $sql = "SELECT u.be_id, MAX(u.u_id) as u_id FROM urlaub u GROUP BY be_id";
        
        $retVal = array();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $retVal[$objekt['be_id']] = $objekt['u_id'];
            }
        }
        return $retVal;
    }

    /**
     *
     * @param int $pBenutzerId            
     * @return NULL|Urlaub
     */
    public static function getUrlaubsTageProBenutzer($pBenutzerId, $jahr = "")
    {
        $sql = "SELECT u.* FROM urlaub u WHERE be_id = " . $pBenutzerId . " ";
        if ($jahr != "") {
            $sql .= "  AND DATE(u.u_datum_von) >= '" . $jahr . "-01-01' ";
        }
        $sql .= " ORDER BY u.u_id";
        return UrlaubsUtilities::queryDB($sql);
    }

    /**
     *
     * @param int $pBenutzerId
     * @return NULL|Urlaub
     */
    public static function getBestimmtenUrlaubsEintragBenutzer($pBenutzerId, $pDatum)
    {
        $sql = "SELECT u.* FROM urlaub u WHERE be_id = " . $pBenutzerId . " AND DATE(u.u_datum_von) = '" . $pDatum."';";
        $r = UrlaubsUtilities::queryDB($sql);
        $retVal = null;
        if ($r != null && $r[0] != null) {
            $retVal = $r[0];
        }
        return $retVal;
    }
    
    /**
     *
     * @param int $pBenutzerId            
     * @return NULL|Urlaub
     */
    public static function getLetzterUrlaubsEintrag($pBenutzerId)
    {
        $sql = "SELECT u.* FROM urlaub u WHERE be_id = " . $pBenutzerId . " ORDER BY u.u_id DESC LIMIT 1";
        $r = UrlaubsUtilities::queryDB($sql);
        
        $retVal = null;
        if ($r != null && $r[0] != null) {
            $retVal = $r[0];
        }
        return $retVal;
    }

    public static function getUrlaubsEintraege($where = "", $orderby = "")
    {
        $sql = "SELECT
					u.*, b.be_benutzername
				FROM
					urlaub u LEFT JOIN benutzer b on u.be_id = b.be_id ";
        if ($where != "") {
            $where = "WHERE " . $where;
        }
        $sql .= $where;
        
        if ($orderby == "") {
            $orderby = " ORDER BY u_datum_von ASC";
        }
        $sql .= $orderby;
        return UrlaubsUtilities::queryDB($sql);
    }

    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new Urlaub();
                $tmp->doLoadFromArray($objekt);
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                if (isset($objekt['be_benutzername'])) {
                    $tmp->setBenutzername($objekt['be_benutzername']);
                }
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }
    
    public static function isKorrekturOrZusatzBuchung($strTyp)
    {
        if ($strTyp == null || $strTyp == "") {
            return false;
        }
        
        if ($strTyp == Urlaub::TYP_URLAUB) {
            return false;
        }
        
        if ($strTyp == Urlaub::TYP_KORREKTUR) {
            return true;
        }
        
        if ($strTyp == Urlaub::TYP_ZUSATZ) {
            return true;
        }
        return false;
    }
}