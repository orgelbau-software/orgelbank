<?php

class AnsprechpartnerBean
{

    private $vorname;

    private $nachname;

    private $telefon;

    private $mobil;

    private $funktion;

    public function init($rs)
    {
        $this->setVorname($rs['a_vorname']);
        $this->setNachname($rs['a_name']);
        $this->setTelefon($rs['a_telefon']);
        $this->setMobil($rs['a_mobil']);
        $this->setFunktion($rs['a_funktion']);
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

    public function getMobil()
    {
        return $this->mobil;
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

    public function setMobil($mobil)
    {
        $this->mobil = $mobil;
    }

    public function setFunktion($funktion)
    {
        $this->funktion = $funktion;
    }
}