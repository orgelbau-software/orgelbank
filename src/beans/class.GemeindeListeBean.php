<?php

class GemeindeListeBean
{

    private $gemeindePLZ;

    private $gemeindeOrt;

    private $gemeindeLand;

    private $gemeindeBezirk;

    private $gemeindeID;

    private $geostatus;

    private $KID;

    private $kirche;

    public function init($rs)
    {
        $this->setGemeindeID($rs['g_id']);
        $this->setKID($rs['k_id']);
        $this->setKirche($rs['g_kirche']);
        $this->setGemeindePLZ($rs['ad_plz']);
        $this->setGemeindeOrt($rs['ad_ort']);
        $this->setGemeindeBezirk($rs['b_id']);
        $this->setGemeindeLand($rs['ad_land']);
        $this->setGeostatus($rs['ad_geostatus']);
    }

    public function getKirche()
    {
        return $this->kirche;
    }

    public function setKirche($kirche)
    {
        $this->kirche = $kirche;
    }

    public function getGemeindePLZ()
    {
        return $this->gemeindePLZ;
    }

    public function getGemeindeOrt()
    {
        return $this->gemeindeOrt;
    }

    public function getGemeindeBezirk()
    {
        return $this->gemeindeBezirk;
    }

    public function getGemeindeID()
    {
        return $this->gemeindeID;
    }

    public function getKID()
    {
        return $this->KID;
    }

    public function setGemeindePLZ($gemeindePLZ)
    {
        $this->gemeindePLZ = $gemeindePLZ;
    }

    public function setGemeindeOrt($gemeindeOrt)
    {
        $this->gemeindeOrt = $gemeindeOrt;
    }

    public function setGemeindeBezirk($gemeindeBezirk)
    {
        $this->gemeindeBezirk = $gemeindeBezirk;
    }

    public function setGemeindeID($gemeindeID)
    {
        $this->gemeindeID = $gemeindeID;
    }

    public function setKID($KID)
    {
        $this->KID = $KID;
    }

    public function getGemeindeLand()
    {
        return $this->gemeindeLand;
    }

    public function setGemeindeLand($gemeindeLand)
    {
        $this->gemeindeLand = $gemeindeLand;
    }

    public function getGeostatus()
    {
        return $this->geostatus;
    }

    public function setGeostatus($geostatus)
    {
        $this->geostatus = $geostatus;
    }
}