<?php

/**
 * @author swatermeyer
 * @since 26.02.2009
 */
class UrlaubsBuchungUtilities
{

    /**
     * Enter description here...
     *
     * @param double $dblStunden            
     * @param int $benutzerID            
     * @param String $bemerkung            
     * @return boolean TRUE wenn die Buchung erfolgreich war, FALSE wenn nicht genügend Urlaub vorhanden
     */
    public static function bucheUrlaub($dblStunden, $benutzerID, $bemerkung = "")
    {
        $b = new Benutzer($benutzerID);
        $verbuchen = $dblStunden;
        
        if ($verbuchen >= 0 && $b->getUrlaubRest() > 0) {
            if ($b->getUrlaubRest() > $verbuchen) {
                $b->setUrlaubRest($b->getUrlaubRest() - $dblStunden);
                $verbuchen = 0;
            } else {
                $verbuchen -= $b->getUrlaubRest();
                $b->setUrlaubRest(0);
            }
        }
        
        if ($verbuchen > 0 && $b->getUrlaubAktuell() > 0) {
            $b->setUrlaubAktuell($b->getUrlaubAktuell() - $verbuchen);
            $verbuchen = 0;
        }
        
        if($verbuchen == 0) {
            $b->speichern(true);
        }
        
        return $verbuchen == 0;
    }

    private static function queryDB($sql)
    {
        $oDSOC = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $objekt) {
                $tmp = new Benutzer();
                $tmp->setID($objekt['be_id']);
                $tmp->setVorname($objekt['be_vorname']);
                $tmp->setNachname($objekt['be_nachname']);
                $tmp->setBenutzername($objekt['be_benutzername']);
                $tmp->setBenutzerlevel($objekt['be_benutzerlevel']);
                $tmp->setPasswort($objekt['be_passwort']);
                $tmp->setAktiviert($objekt['be_aktiviert']);
                
                $tmp->setStdMontag($objekt['be_std_montag']);
                $tmp->setStdDienstag($objekt['be_std_dienstag']);
                $tmp->setStdMittwoch($objekt['be_std_mittwoch']);
                $tmp->setStdDonnerstag($objekt['be_std_donnerstag']);
                $tmp->setStdFreitag($objekt['be_std_freitag']);
                $tmp->setStdSamstag($objekt['be_std_samstag']);
                $tmp->setStdSonntag($objekt['be_std_sonntag']);
                $tmp->setStdGesamt($objekt['be_std_gesamt']);
                
                $tmp->setCreatedAt($objekt['be_createdate']);
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                $oDSOC->add($tmp);
            }
        }
        return $oDSOC;
    }
}
?>