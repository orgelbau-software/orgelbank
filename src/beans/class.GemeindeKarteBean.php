<?php

class GemeindeKarteBean implements Bean
{

    private $orgelId;

    private $anzahlRegister;

    private $massnahmen;

    private $letztePflege;
    
    private $naechstePflege;

    private $pflegevertrag;

    private $zyklus;

    private $kirche;

    private $bezirkId;

    private $strasse;

    private $PLZ;

    private $ort;

    private $lat;

    private $lng;

    private $adressId;

    public function init($rs)
    {
        $this->setOrgelId($rs['o_id']);
        $this->setAnzahlRegister($rs['o_anzahlregister']);
        $this->setMassnahmen($rs['o_massnahmen']);
        $this->setLetztePflege($rs['o_letztepflege']);
        $this->setNaechstePflege($rs['naechstepflege']);
        $this->setPflegevertrag($rs['o_pflegevertrag']);
        $this->setZyklus($rs['o_zyklus']);
        $this->setKirche($rs['g_kirche']);
        $this->setBezirkId($rs['b_id']);
        $this->setPLZ($rs['ad_plz']);
        $this->setOrt($rs['ad_ort']);
        $this->setLat($rs['ad_lat']);
        $this->setLng($rs['ad_lng']);
        $this->setAdressId($rs['ad_id']);
    }

    public function getOrgelId()
    {
        return $this->orgelId;
    }

    public function getAnzahlRegister()
    {
        return $this->anzahlRegister;
    }

    public function getMassnahmen()
    {
        return $this->massnahmen;
    }

    public function getLetztePflege()
    {
        return $this->letztePflege;
    }
    
    public function getNaechstePflege()
    {
        return $this->naechstePflege;
    }

    public function getZyklus()
    {
        return $this->zyklus;
    }

    public function getKirche()
    {
        return $this->kirche;
    }

    public function getBezirkId()
    {
        return $this->bezirkId;
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

    public function getLat()
    {
        return $this->lat;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function setOrgelId($orgelId)
    {
        $this->orgelId = $orgelId;
    }

    public function setAnzahlRegister($anzahlRegister)
    {
        $this->anzahlRegister = $anzahlRegister;
    }

    public function setMassnahmen($massnahmen)
    {
        $this->massnahmen = $massnahmen;
    }

    public function setLetztePflege($letztePflege)
    {
        $this->letztePflege = $letztePflege;
    }
    
    public function setNaechstePflege($naechstePflege)
    {
        $this->naechstePflege = $naechstePflege;
    }

    public function setZyklus($zyklus)
    {
        $this->zyklus = $zyklus;
    }

    public function setKirche($kirche)
    {
        $this->kirche = $kirche;
    }

    public function setBezirkId($bezirkId)
    {
        $this->bezirkId = $bezirkId;
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

    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    public function getPflegevertrag()
    {
        return $this->pflegevertrag;
    }

    public function setPflegevertrag($pflegevertrag)
    {
        $this->pflegevertrag = $pflegevertrag;
    }

    public function getAdressId()
    {
        return $this->adressId;
    }

    public function setAdressId($adressId)
    {
        $this->adressId = $adressId;
    }
}