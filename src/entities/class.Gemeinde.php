<?php

class Gemeinde extends SimpleDatabaseStorageObjekt
{

    private $iKID;
    
    private $strKundenNr;

    private $strKirche;

    private $strRAnschrift;

    private $strRGemeinde;

    private $iBID;

    private $iDistanz;

    private $strFahrtzeit;

    private $strAktiv;

    private $iAID;

    private $iKircheAdressId;

    private $iRechnungAdressId;

    private $iKircheAdress = null;
 // not persistent
    private $iRechnungAdress = null;
 // not persistent
    public function __construct($iID = 0, $primaryKey = "g_id", $tableName = "gemeinde", $tablePrefix = "g_")
    {
        parent::__construct($iID, $primaryKey, $tableName, $tablePrefix);
    }

    public function __toString()
    {
        return "Gemeinde[ID: " . $this->getID() . ", BEZ: " . $this->getKirche() . "]";
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add("k_id", $this->getKID());
        $ht->add("g_kundennr", $this->getKundenNr());
        $ht->add("g_kirche", $this->getKirche());
        $ht->add("g_ranschrift", $this->getRAnschrift());
        $ht->add("g_rgemeinde", $this->getRGemeinde());
        $ht->add("b_id", $this->getBID());
        $ht->add("b_distanz", $this->getDistanz());
        $ht->add("b_fahrzeit", $this->getFahrtzeit());
        $ht->add("g_aktiv", $this->getAktiv());
        $ht->add("a_hauptid", $this->getAID());
        $ht->add("g_kirche_aid", $this->getKircheAdressId());
        $ht->add("g_rechnung_aid", $this->getRechnungAdressId());
        
        return $ht;
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setID($rs['g_id']);
        $this->setKID($rs['k_id']);
        $this->setKundennr($rs['g_kundennr']);
        $this->setKirche($rs['g_kirche']);
        $this->setRAnschrift($rs['g_ranschrift']);
        $this->setRGemeinde($rs['g_rgemeinde']);
        $this->setBID($rs['b_id']);
        $this->setDistanz($rs['b_distanz']);
        $this->setFahrtzeit($rs['b_fahrzeit']);
        $this->setAktiv($rs['g_aktiv']);
        $this->setAID($rs['a_hauptid']);
        $this->setKircheAdressId($rs['g_kirche_aid']);
        $this->setRechnungAdressId($rs['g_rechnung_aid']);
        $this->setCreatedAt($rs['g_createdate']);
        
        $this->isPersistent(true);
    }

    public function loeschen()
    {
        Trace::addStart(__METHOD__);
        $sql = "UPDATE gemeinde SET g_aktiv = 0 WHERE g_id = " . $this->getID();
        $sqlOrgel = "UPDATE orgel x SET x.g_id = NULL WHERE g_id =" . $this->getID();
        $sqlAnsprechpartner = "DELETE FROM gemeindeansprechpartner WHERE g_id=" . $this->getID();
        
        $db = DB::getInstance();
        $db->NonSelectQuery($sql);
        $db->NonSelectQuery($sqlOrgel);
        $db->NonSelectQuery($sqlAnsprechpartner);
        Trace::addExit(__METHOD__);
    }

    public function speichern($objektNachSpeichernNeuLaden = true)
    {
        if ($this->getKircheAdresse() != null) {
            $this->getKircheAdresse()->speichern(true);
            $this->setKircheAdressId($this->getKircheAdresse()
                ->getID());
        }
        
        if ($this->getRechnungAdresse() != null) {
            $this->getRechnungAdresse()->speichern(true);
            $this->setRechnungAdressId($this->getRechnungAdresse()
                ->getID());
        }
        parent::speichern($objektNachSpeichernNeuLaden);
    }

    public function getAID()
    {
        return $this->iAID;
    }

    public function getBID()
    {
        return $this->iBID;
    }

    public function getDistanz()
    {
        return $this->iDistanz;
    }

    public function getKID()
    {
        return $this->iKID;
    }

    public function getAktiv()
    {
        return $this->strAktiv;
    }

    public function getFahrtzeit()
    {
        return $this->strFahrtzeit;
    }

    public function getRAnschrift()
    {
        return $this->strRAnschrift;
    }

    public function getRGemeinde()
    {
        return $this->strRGemeinde;
    }

    public function setAID($iAID)
    {
        if ($this->iAID != $iAID) {
            $this->iAID = $iAID;
            $this->setChanged(true);
        }
    }

    public function setBID($iBID)
    {
        if ($this->iBID != $iBID) {
            $this->iBID = $iBID;
            $this->setChanged(true);
        }
    }

    public function setDistanz($iDistanz)
    {
        if ($this->iDistanz != $iDistanz) {
            $this->iDistanz = $iDistanz;
            $this->setChanged(true);
        }
    }

    public function setKID($iKID)
    {
        if ($this->iKID != $iKID) {
            $this->iKID = $iKID;
            $this->setChanged(true);
        }
    }

    public function setAktiv($strAktiv)
    {
        if ($this->strAktiv != $strAktiv) {
            $this->strAktiv = $strAktiv;
            $this->setChanged(true);
        }
    }

    public function setFahrtzeit($strFahrtzeit)
    {
        if ($this->strFahrtzeit != $strFahrtzeit) {
            $this->strFahrtzeit = $strFahrtzeit;
            $this->setChanged(true);
        }
    }

    public function setRAnschrift($strRAnschrift)
    {
        if ($this->strRAnschrift != $strRAnschrift) {
            $this->strRAnschrift = $strRAnschrift;
            $this->setChanged(true);
        }
    }

    public function setRGemeinde($strRGmeinde)
    {
        if ($this->strRGemeinde != $strRGmeinde) {
            $this->strRGemeinde = $strRGmeinde;
            $this->setChanged(true);
        }
    }

    public function getKirche()
    {
        return $this->strKirche;
    }

    public function setKirche($strKirche)
    {
        if ($this->strKirche != $strKirche) {
            $this->strKirche = $strKirche;
            $this->setChanged(true);
        }
    }

    public function isKatholisch()
    {
        if ($this->getKID() == 2)
            return true;
        return false;
    }

    public function isEvangelisch()
    {
        if ($this->getKID() == 1)
            return true;
        return false;
    }

    public function isSonstiges()
    {
        if ($this->getKID() == 3)
            return true;
        return false;
    }

    public function isLuthrisch()
    {
        if ($this->getKID() == 4)
            return true;
        return false;
    }

    public function isMehthodistisch()
    {
        if ($this->getKID() == 5)
            return true;
        return false;
    }

    /**
     * Gibt die Ansprechpartner der Gemeinde zurück
     *
     * @param String $strOrderBy            
     * @return
     *
     */
    public function getAnsprechpartner($strOrderBy = null)
    {
        return AnsprechpartnerUtilities::getGemeindeAnsprechpartner($this->getID(), $strOrderBy);
    }

    public function getOrgeln($strOrderBy = null)
    {
        return OrgelUtilities::getGemeindeOrgeln($this->getID(), $strOrderBy);
    }

    public function getLand()
    {
        return $this->land;
    }

    public function getRLand()
    {
        return $this->RLand;
    }

    public function setLand($land)
    {
        $this->setChanged(true);
        $this->land = $land;
    }

    public function setRLand($RLand)
    {
        $this->setChanged(true);
        $this->RLand = $RLand;
    }

    public function getKircheAdressId()
    {
        return $this->iKircheAdressId;
    }

    public function setKircheAdressId($adressId)
    {
        if ($this->iKircheAdressId != $adressId) {
            $this->iKircheAdressId = $adressId;
            $this->setChanged(true);
        }
    }

    public function getKircheAdresse()
    {
        if ($this->iKircheAdress == null) {
            $this->iKircheAdress = new Adresse($this->getKircheAdressId());
        }
        return $this->iKircheAdress;
    }

    public function getRechnungAdressId()
    {
        return $this->iRechnungAdressId;
    }

    public function setRechnungAdressId($adressId)
    {
        if ($this->iRechnungAdressId != $adressId) {
            $this->iRechnungAdressId = $adressId;
            $this->setChanged(true);
        }
    }
    
    public function getKundenNr()
    {
        return $this->strKundenNr;
    }
    
    public function setKundenNr($pKundenNr)
    {
        if ($this->strKundenNr != $pKundenNr) {
            $this->strKundenNr = $pKundenNr;
            $this->setChanged(true);
        }
    }

    /**
     *
     * @return Adresse
     */
    public function getRechnungAdresse()
    {
        if ($this->iRechnungAdress == null) {
            $this->iRechnungAdress = new Adresse($this->getRechnungAdressId());
        }
        return $this->iRechnungAdress;
    }
}

?>
