<?php

class AufgabeMitarbeiter extends Benutzer
{

    private $iAufgabeID;

    private $freigeschaltet;

    public function __construct()
    {
        parent::__construct(0);
    }

    public function isFreigeschaltet()
    {
        return $this->freigeschaltet;
    }

    public function getAufgabeID()
    {
        return $this->iAufgabeID;
    }

    public function setFreigeschaltet($freigeschaltet)
    {
        $this->freigeschaltet = $freigeschaltet;
    }

    public function setAufgabeID($iAufgabeID)
    {
        $this->iAufgabeID = $iAufgabeID;
    }
}
?>