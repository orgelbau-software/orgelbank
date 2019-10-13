<?php

class Stempeluhr extends SimpleDatabaseStorageObjekt
{

    private $mitarbeiterId;

    private $aufgabeId;

    private $unteraufgabeId;

    private $projektId;

    private $zeit;
    
    private $status;
    
    private $dauer;

    public function __construct($iID = 0)
    {
        parent::__construct($iID, "st_id", "stempeluhr", "st_");
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->mitarbeiterId = $rs["be_id"];
        $this->aufgabeId = $rs["st_hau_id"];
        $this->unteraufgabeId = $rs["st_uau_id"];
        $this->zeit = $rs['st_zeit'];
        $this->status = $rs['st_status'];
        $this->dauer = $rs['st_dauer'];
        $this->projektId = $rs['proj_id'];
    }

    protected function doNew()
    {}

    protected function generateHashtable()
    {
        $ht = new HashTable();
        $ht->add("be_id", $this->getMitarbeiterId());
        $ht->add("proj_id", $this->getProjektId());
        $ht->add("st_hau_id", $this->getAufgabeId());
        $ht->add("st_uau_id", $this->getUnteraufgabeId());
        $ht->add("st_zeit", $this->getZeit());
        $ht->add("st_dauer", $this->getDauer());
        $ht->add("st_status", $this->getStatus());
        return $ht;
    }

    /**
     *
     * @return the $mitarbeiterId
     */
    public function getMitarbeiterId()
    {
        return $this->mitarbeiterId;
    }

    /**
     *
     * @return the $aufgabeId
     */
    public function getAufgabeId()
    {
        return $this->aufgabeId;
    }

    /**
     *
     * @return the $unteraufgabeId
     */
    public function getUnteraufgabeId()
    {
        return $this->unteraufgabeId;
    }

    /**
     *
     * @return the $projektId
     */
    public function getProjektId()
    {
        return $this->projektId;
    }

    /**
     *
     * @return the $zeit
     */
    public function getZeit()
    {
        if($this->zeit == "") {
            $this->zeit = $this->createDateAsString();
        }
        return $this->zeit;
    }

    /**
     *
     * @param field_type $mitarbeiterId            
     */
    public function setMitarbeiterId($mitarbeiterId)
    {
        if ($this->mitarbeiterId != $mitarbeiterId) {
            $this->mitarbeiterId = $mitarbeiterId;
            $this->setChanged(true);
        }
    }

    /**
     *
     * @param field_type $aufgabeId            
     */
    public function setAufgabeId($aufgabeId)
    {
        if ($this->aufgabeId != $aufgabeId) {
            $this->aufgabeId = $aufgabeId;
            $this->setChanged(true);
        }
    }

    /**
     *
     * @param field_type $unteraufgabeId            
     */
    public function setUnteraufgabeId($unteraufgabeId)
    {
        if ($this->unteraufgabeId != $unteraufgabeId) {
            $this->unteraufgabeId = $unteraufgabeId;
            $this->setChanged(true);
        }
    }

    /**
     *
     * @param field_type $projektId            
     */
    public function setProjektId($projektId)
    {
        if ($this->projektId != $projektId) {
            $this->projektId = $projektId;
            $this->setChanged(true);
        }
    }

    /**
     *
     * @param field_type $zeit            
     */
    public function setZeit($zeit)
    {
        if ($this->zeit != $zeit) {
            $this->zeit = $zeit;
            $this->setChanged(true);
        }
    }
    /**
     * @return the $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return the $dauer
     */
    public function getDauer()
    {
        return $this->dauer;
    }

    /**
     * @param field_type $status
     */
    public function setStatus($status)
    {
        if ($this->status != $status) {
            $this->status = $status;
            $this->setChanged(true);
        }
    }

    /**
     * @param field_type $dauer
     */
    public function setDauer($dauer)
    {
        if ($this->dauer != $dauer) {
            $this->dauer = $dauer;
            $this->setChanged(true);
        }
    }

    
    
}

?>
