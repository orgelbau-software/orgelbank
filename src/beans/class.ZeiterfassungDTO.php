<?php

class ZeiterfassungDTO
{

    private $benutzerID;

    private $benutzername;

    private $projektID;

    private $projektBezeichnung;

    private $hauptaufgabeID;

    private $unteraufgabeID;

    private $hauptaufgabeBezeichnung;

    private $unteraufgabeBezeichnung;

    private $gemeindeID;

    private $gemeindeBezeichnung;

    private $stunden;

    private $sollStunden;

    private $istStunden;

    public function getBenutzerID()
    {
        return $this->benutzerID;
    }

    public function getBenutzername()
    {
        return $this->benutzername;
    }

    public function getGemeindeBezeichnung()
    {
        return $this->gemeindeBezeichnung;
    }

    public function getGemeindeID()
    {
        return $this->gemeindeID;
    }

    public function getHauptaufgabeBezeichnung()
    {
        return $this->hauptaufgabeBezeichnung;
    }

    public function getHauptaufgabeID()
    {
        return $this->hauptaufgabeID;
    }

    public function getProjektBezeichnung()
    {
        return $this->projektBezeichnung;
    }

    public function getProjektID()
    {
        return $this->projektID;
    }

    public function getUnteraufgabeBezeichnung()
    {
        return $this->unteraufgabeBezeichnung;
    }

    public function getUnteraufgabeID()
    {
        return $this->unteraufgabeID;
    }

    public function setBenutzerID($benutzerID)
    {
        $this->benutzerID = $benutzerID;
    }

    public function setBenutzername($benutzername)
    {
        $this->benutzername = $benutzername;
    }

    public function setGemeindeBezeichnung($gemeindeBezeichnung)
    {
        $this->gemeindeBezeichnung = $gemeindeBezeichnung;
    }

    public function setGemeindeID($gemeindeID)
    {
        $this->gemeindeID = $gemeindeID;
    }

    public function setHauptaufgabeBezeichnung($hauptaufgabeBezeichnung)
    {
        $this->hauptaufgabeBezeichnung = $hauptaufgabeBezeichnung;
    }

    public function setHauptaufgabeID($hauptaufgabeID)
    {
        $this->hauptaufgabeID = $hauptaufgabeID;
    }

    public function setProjektBezeichnung($projektBezeichnung)
    {
        $this->projektBezeichnung = $projektBezeichnung;
    }

    public function setProjektID($projektID)
    {
        $this->projektID = $projektID;
    }

    public function setUnteraufgabeBezeichnung($unteraufgabeBezeichnung)
    {
        $this->unteraufgabeBezeichnung = $unteraufgabeBezeichnung;
    }

    public function setUnteraufgabeID($unteraufgabeID)
    {
        $this->unteraufgabeID = $unteraufgabeID;
    }

    public function getStunden()
    {
        return $this->stunden;
    }

    public function setStunden($stunden)
    {
        $this->stunden = $stunden;
    }

    /**
     *
     * @return the $sollStunden
     */
    public function getSollStunden()
    {
        return $this->sollStunden;
    }

    /**
     *
     * @return the $istStunden
     */
    public function getIstStunden()
    {
        return $this->istStunden;
    }

    /**
     *
     * @param field_type $sollStunden            
     */
    public function setSollStunden($sollStunden)
    {
        $this->sollStunden = $sollStunden;
    }

    /**
     *
     * @param field_type $istStunden            
     */
    public function setIstStunden($istStunden)
    {
        $this->istStunden = $istStunden;
    }
}
?>