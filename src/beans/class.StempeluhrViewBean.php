<?php

class StempeluhrViewBean implements Bean
{

    private $stId;

    private $mitarbeiterId;

    private $benutzername;

    private $projektId;

    private $projektBezeichnung;

    private $aufgabeId;

    private $unteraufgabeId;

    private $aufgabeBezeichnung;

    private $unteraufgabeBezeichnung;

    private $zeit;

    private $dauer;

    private $status;

    public function init($rs)
    {
        $this->setStId($rs['st_id']);
        $this->setDauer($rs['st_dauer']);
        $this->setZeit($rs['st_zeit']);
        $this->setStatus($rs['st_status']);
        $this->setBenutzername($rs['be_benutzername']);
        $this->setProjektBezeichnung($rs['proj_bezeichnung']);
        $this->setAufgabeBezeichnung($rs['st_aua_bezeichnung']);
        $this->setUnteraufgabeBezeichnung($rs['st_uau_bezeichnung']);
    }

    /**
     *
     * @return the $stId
     */
    public function getStId()
    {
        return $this->stId;
    }

    /**
     *
     * @param field_type $stId            
     */
    public function setStId($stId)
    {
        $this->stId = $stId;
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
     * @return the $benutzername
     */
    public function getBenutzername()
    {
        return $this->benutzername;
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
     * @return the $projektBezeichnung
     */
    public function getProjektBezeichnung()
    {
        return $this->projektBezeichnung;
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
     * @return the $aufgabeBezeichnung
     */
    public function getAufgabeBezeichnung()
    {
        return $this->aufgabeBezeichnung;
    }

    /**
     *
     * @return the $unteraufgabeBezeichnung
     */
    public function getUnteraufgabeBezeichnung()
    {
        return $this->unteraufgabeBezeichnung;
    }

    /**
     *
     * @param field_type $mitarbeiterId            
     */
    public function setMitarbeiterId($mitarbeiterId)
    {
        $this->mitarbeiterId = $mitarbeiterId;
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
     * @param field_type $projektId            
     */
    public function setProjektId($projektId)
    {
        $this->projektId = $projektId;
    }

    /**
     *
     * @param field_type $projektBezeichnung            
     */
    public function setProjektBezeichnung($projektBezeichnung)
    {
        $this->projektBezeichnung = $projektBezeichnung;
    }

    /**
     *
     * @param field_type $aufgabeId            
     */
    public function setAufgabeId($aufgabeId)
    {
        $this->aufgabeId = $aufgabeId;
    }

    /**
     *
     * @param field_type $unteraufgabeId            
     */
    public function setUnteraufgabeId($unteraufgabeId)
    {
        $this->unteraufgabeId = $unteraufgabeId;
    }

    /**
     *
     * @param field_type $aufgabeBezeichnung            
     */
    public function setAufgabeBezeichnung($aufgabeBezeichnung)
    {
        $this->aufgabeBezeichnung = $aufgabeBezeichnung;
    }

    /**
     *
     * @param field_type $unteraufgabeBezeichnung            
     */
    public function setUnteraufgabeBezeichnung($unteraufgabeBezeichnung)
    {
        $this->unteraufgabeBezeichnung = $unteraufgabeBezeichnung;
    }

    /**
     *
     * @return the $zeit
     */
    public function getZeit()
    {
        return $this->zeit;
    }

    /**
     *
     * @return the $dauer
     */
    public function getDauer()
    {
        return $this->dauer;
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
     * @param field_type $zeit            
     */
    public function setZeit($zeit)
    {
        $this->zeit = $zeit;
    }

    /**
     *
     * @param field_type $dauer            
     */
    public function setDauer($dauer)
    {
        $this->dauer = $dauer;
    }

    /**
     *
     * @param field_type $status            
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}