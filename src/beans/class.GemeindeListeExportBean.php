<?php

class GemeindeListeExportBean extends GemeindeListeBean
{

    private $gemeindeStrasse;

    private $gemeindeHausnummer;

    private $distanz;

    private $fahrtzeit;

    private $aktiv;

    private $vorname;

    private $nachname;

    private $telefon;

    private $mobil;

    private $funktion;

    public function init($rs)
    {
        parent::init($rs);
        
        $this->setGemeindeStrasse($rs['ad_strasse']);
        $this->setGemeindeHausnummer($rs['ad_hsnr']);
        $this->setDistanz($rs['b_distanz']);
        $this->setFahrtzeit($rs['b_fahrzeit']);
        $this->setAktiv($rs['g_aktiv']);
        
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

    public function getAktiv()
    {
        return $this->aktiv;
    }

    public function setAktiv($aktiv)
    {
        $this->aktiv = $aktiv;
    }

    public function getFahrtzeit()
    {
        return $this->fahrtzeit;
    }

    public function setFahrtzeit($fahrtzeit)
    {
        $this->fahrtzeit = $fahrtzeit;
    }

    public function getDistanz()
    {
        return $this->distanz;
    }

    public function setDistanz($distanz)
    {
        $this->distanz = $distanz;
    }

    public function getGemeindeStrasse()
    {
        return $this->gemeindeStrasse;
    }

    public function getGemeindeHausnummer()
    {
        return $this->gemeindeHausnummer;
    }

    public function setGemeindeStrasse($gemeindeStrasse)
    {
        $this->gemeindeStrasse = $gemeindeStrasse;
    }

    public function setGemeindeHausnummer($gemeindeHausnummer)
    {
        $this->gemeindeHausnummer = $gemeindeHausnummer;
    }
}