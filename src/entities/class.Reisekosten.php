<?php

class Reisekosten extends SimpleDatabaseStorageObjekt
{

    private $projektID;

    private $benutzerID;

    private $spesen;

    private $hotel;

    private $KM;

    private $kmKosten;

    private $gesamt;

    private $kw;

    private $jahr;

    public function __construct($iID = 0)
    {
        parent::__construct($iID, "rk_id", "reisekosten", "rk_");
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setProjektID($rs['proj_id']);
        $this->setBenutzerID($rs['be_id']);
        $this->setHotel($rs['rk_hotel']);
        $this->setSpesen($rs['rk_spesen']);
        $this->setKM($rs['rk_km']);
        $this->setKMKosten($rs['rk_kmkosten']);
        $this->setGesamt($rs['rk_gesamt']);
        $this->setKW($rs['rk_kw']);
        $this->setJahr($rs['rk_jahr']);
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add("proj_id", $this->getProjektID());
        $ht->add("be_id", $this->getBenutzerID());
        $ht->add("rk_hotel", $this->getHotel());
        $ht->add("rk_spesen", $this->getSpesen());
        $ht->add("rk_km", $this->getKM());
        $ht->add("rk_kmkosten", $this->getKMKosten());
        $ht->add("rk_gesamt", $this->getGesamt());
        $ht->add("rk_kw", $this->getKW());
        $ht->add("rk_jahr", $this->getJahr());
        
        return $ht;
    }

    public function speichern($reload = false)
    {
        $this->summieren();
        if ($this->getID() > 0 || $this->gesamt > 0) {
            parent::speichern($reload);
        }
    }

    public function summieren()
    {
        $retVal = 0;
        $retVal += $this->kmKosten;
        $retVal += $this->spesen;
        $retVal += $this->hotel;
        
        $this->gesamt = $retVal;
        
        return $retVal;
    }

    public function getBenutzerID()
    {
        return $this->benutzerID;
    }

    public function getGesamt()
    {
        return $this->gesamt;
    }

    public function getHotel()
    {
        return $this->hotel;
    }

    public function getKM()
    {
        return $this->KM;
    }

    public function getKMKosten()
    {
        return $this->kmKosten;
    }

    public function getProjektID()
    {
        return $this->projektID;
    }

    public function getSpesen()
    {
        return $this->spesen;
    }

    public function setBenutzerID($benutzerID)
    {
        $this->benutzerID = $benutzerID;
    }

    public function setGesamt($gesamt)
    {
        $this->gesamt = $gesamt;
    }

    public function setHotel($hotel)
    {
        $this->hotel = $hotel;
    }

    public function setKM($km)
    {
        $this->KM = $km;
    }

    public function setKMKosten($kmKosten)
    {
        $this->kmKosten = $kmKosten;
    }

    public function setProjektID($projektID)
    {
        $this->projektID = $projektID;
    }

    public function setSpesen($spesen)
    {
        $this->spesen = $spesen;
    }

    public function getKW()
    {
        return $this->kw;
    }

    public function getJahr()
    {
        return $this->jahr;
    }

    public function setKW($kw)
    {
        $this->kw = $kw;
    }

    public function setJahr($jahr)
    {
        $this->jahr = $jahr;
    }
}
?>