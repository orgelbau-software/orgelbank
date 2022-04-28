<?php

class RechnungView extends Rechnung
{

    private $gemeindeNamen;

    private $rechnungsTypId;

    private $rechnungsTyp;

    public function errechneGesamtBetrag($speichern = false)
    {
        $speichern;
    }

    public function getGemeindeName()
    {
        return $this->gemeindeNamen;
    }

    public function setGemeindeName($strGemeindeName)
    {
        $this->gemeindeNamen = $strGemeindeName;
    }

    public function getRechnungsTyp()
    {
        return $this->rechnungsTyp;
    }

    public function setRechnungsTyp($rechnungsTyp)
    {
        $this->rechnungsTyp = $rechnungsTyp;
    }

    public function getRechnungsTypId()
    {
        return $this->rechnungsTypId;
    }

    public function setRechnungsTypId($rechnungsTypId)
    {
        $this->rechnungsTypId = $rechnungsTypId;
    }
    
    public function getType() {
        return $this->getRechnungsTypId();
    }
}
?>