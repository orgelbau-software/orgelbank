<?php

class OrgelListeExportBean extends OrgelListeBean
{

    private $vorname;

    private $nachname;

    private $telefon;

    private $funktion;
    
    private $ansprechpartnerId;

    public function init($rs)
    {
        parent::init($rs);
        $this->setVorname($rs['a_vorname']);
        $this->setNachname($rs['a_name']);
        $this->setTelefon($rs['a_telefon']);
        $this->setFunktion($rs['a_funktion']);
        $this->setAnsprechpartnerId($rs['a_id']);
    }

    public function getVorname()
    {
        return $this->vorname;
    }

    public function getNachname()
    {
        return $this->nachname;
    }

    public function getTelefon()
    {
        return $this->telefon;
    }

    public function getFunktion()
    {
        return $this->funktion;
    }

    public function setVorname($vorname)
    {
        $this->vorname = $vorname;
    }

    public function setNachname($nachname)
    {
        $this->nachname = $nachname;
    }

    public function setTelefon($telefon)
    {
        $this->telefon = $telefon;
    }

    public function setFunktion($funktion)
    {
        $this->funktion = $funktion;
    }
    
    public function getAnsprechpartnerId()
    {
        return $this->ansprechpartnerId;
    }
    
    public function setAnsprechpartnerId($ansprechpartnerId)
    {
        $this->ansprechpartnerId = $ansprechpartnerId;
    }
}