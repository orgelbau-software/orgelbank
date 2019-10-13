<?php

class AdressBean
{

    private $adressId;

    private $strasse;

    private $PLZ;

    private $ort;

    private $land;

    private $hausnummer;

    public function init($rs)
    {
        $this->setAdressId($rs['ad_id']);
        $this->setStrasse($rs['ad_strasse']);
        $this->setHausnummer($rs['ad_hsnr']);
        $this->setPLZ($rs['ad_plz']);
        $this->setOrt($rs['ad_ort']);
        $this->setLand($rs['ad_land']);
    }

    public function getHausnummer()
    {
        return $this->hausnummer;
    }

    public function setHausnummer($hausnummer)
    {
        $this->hausnummer = $hausnummer;
    }

    public function getAdressId()
    {
        return $this->adressId;
    }

    public function getStrasse()
    {
        return $this->strasse;
    }

    public function getPLZ()
    {
        return $this->PLZ;
    }

    public function getOrt()
    {
        return $this->ort;
    }

    public function getLand()
    {
        return $this->land;
    }

    public function setAdressId($adressId)
    {
        $this->adressId = $adressId;
    }

    public function setStrasse($strasse)
    {
        $this->strasse = $strasse;
    }

    public function setPLZ($PLZ)
    {
        $this->PLZ = $PLZ;
    }

    public function setOrt($ort)
    {
        $this->ort = $ort;
    }

    public function setLand($land)
    {
        $this->land = $land;
    }
}