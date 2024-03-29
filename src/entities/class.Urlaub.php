<?php

/**
 * 
 * @author Stephan
 *
 */
class Urlaub extends SimpleDatabaseStorageObjekt
{

    public const STATUS_ANGELEGT = 1;
    
    public const STATUS_MANUELL = 2;
    
    public const STATUS_ZEITERFASSUNG = 3;
    
    public const TYP_URLAUB = "U";
    
    public const TYP_KORREKTUR = "K";
    
    public const TYP_ZUSATZ = "Z";

    private $datumVon;

    private $datumBis;

    private $stunden;

    private $verbleibend;

    private $resturlaub;

    private $summe;

    private $status;

    private $benutzerId;

    private $bemerkung;

    // Transient
    private $benutzername;

    public function __construct($iID = 0)
    {
        parent::__construct($iID, "u_id", "urlaub", "u_");
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setDatumVon($rs['u_datum_von']);
        $this->setDatumBis($rs['u_datum_bis']);
        $this->setStunden($rs['u_stunden']);
        $this->setBenutzerId($rs['be_id']);
        $this->setVerbleibend($rs['u_verbleibend']);
        $this->setResturlaub($rs['u_resturlaub']);
        $this->setSumme($rs['u_summe']);
        $this->setStatus($rs['u_status']);
        $this->setBemerkung($rs['u_bemerkung']);
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add("u_datum_von", $this->getDatumVon());
        $ht->add("u_datum_bis", $this->getDatumBis());
        $ht->add("u_stunden", $this->getStunden());
        $ht->add("be_id", $this->getBenutzerId());
        $ht->add("u_verbleibend", $this->getVerbleibend());
        $ht->add("u_resturlaub", $this->getResturlaub());
        $ht->add("u_summe", $this->getSumme());
        $ht->add("u_status", $this->getStatus());
        $ht->add("u_bemerkung", $this->getBemerkung());
        
        return $ht;
    }

    /**
     *
     * @return the $datumVon
     */
    public function getDatumVon($formatiert = false)
    {
        if ($formatiert) {
            if($this->datumVon == "0000-00-00") {
                return "";
            }
            return date("d.m.Y", strtotime($this->datumVon));
        }
        return $this->datumVon;
    }

    /**
     *
     * @return the $datumBis
     */
    public function getDatumBis($formatiert = false)
    {
        if ($formatiert) {
            if($this->datumBis == "0000-00-00") {
                return "";
            }
            return date("d.m.Y", strtotime($this->datumBis));
        }
        return $this->datumBis;
    }

    /**
     *
     * @return the $stunden
     */
    public function getStunden()
    {
        return $this->stunden;
    }

    /**
     *
     * @return the $verbleibend
     */
    public function getVerbleibend()
    {
        return $this->verbleibend;
    }

    /**
     *
     * @return the $resturlaub
     */
    public function getResturlaub()
    {
        return $this->resturlaub;
    }

    /**
     *
     * @return the $summe
     */
    public function getSumme()
    {
        return $this->summe;
    }

    /**
     *
     * @return the $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *
     * @param field_type $datumVon            
     */
    public function setDatumVon($datumVon)
    {
        $this->datumVon = $datumVon;
    }

    /**
     *
     * @param field_type $datumBis            
     */
    public function setDatumBis($datumBis)
    {
        $this->datumBis = $datumBis;
    }

    /**
     *
     * @param field_type $stunden            
     */
    public function setStunden($stunden)
    {
        $this->stunden = $stunden;
    }

    /**
     *
     * @param field_type $verbleibend            
     */
    public function setVerbleibend($verbleibend)
    {
        $this->verbleibend = $verbleibend;
    }

    /**
     *
     * @param field_type $resturlaub            
     */
    public function setResturlaub($resturlaub)
    {
        $this->resturlaub = $resturlaub;
    }

    /**
     *
     * @param field_type $summe            
     */
    public function setSumme($summe)
    {
        $this->summe = $summe;
    }

    /**
     *
     * @param field_type $status            
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     *
     * @return the $benutzerId
     */
    public function getBenutzerId()
    {
        return $this->benutzerId;
    }

    /**
     *
     * @param field_type $benutzerId            
     */
    public function setBenutzerId($benutzerId)
    {
        $this->benutzerId = $benutzerId;
    }

    /**
     *
     * @return the $benutzername
     */
    public function getBenutzername()
    {
        return $this->benutzername;
    }

    /**
     *
     * @param field_type $benutzername            
     */
    public function setBenutzername($benutzername)
    {
        $this->benutzername = $benutzername;
    }

    /**
     *
     * @return the $bemerkung
     */
    public function getBemerkung()
    {
        return $this->bemerkung;
    }

    /**
     *
     * @param field_type $bemerkung            
     */
    public function setBemerkung($bemerkung)
    {
        $this->bemerkung = $bemerkung;
    }
}
?>