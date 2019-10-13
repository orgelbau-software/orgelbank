<?php

class OrgelGemeinde extends Orgel
{

    private $oGemeinde;

    private $oAnsprechpartner;

    private $registerAnzahl;

    private $tmp;
 // benutzt fuer das berechnete pflegedatum in der wartungsanzeige
    public function __construct($iID = 0, $primaryKey = "o_id", $tableName = "orgel", $tablePrefix = "o_")
    {
        parent::__construct($iID, $primaryKey, $tableName, $tablePrefix);
        $this->oGemeinde = new Gemeinde();
        $this->oAnsprechpartner = new Ansprechpartner();
    }

    public function setGemeindeNamen($s)
    {
        $this->oGemeinde->setKirche($s);
    }

    public function getGemeindeNamen()
    {
        return $this->oGemeinde->getKirche();
    }

    public function getGemeindeOrt()
    {
        return $this->oGemeinde->getOrt();
    }

    public function setGemeindeOrt($s)
    {
        $this->oGemeinde->setOrt($s);
    }

    public function getGemeindePLZ()
    {
        return $this->oGemeinde->getPLZ();
    }

    public function setGemeindePLZ($s)
    {
        $this->oGemeinde->setPLZ($s);
    }

    public function getGemeindeBezirk()
    {
        return $this->oGemeinde->getBID();
    }

    public function setGemeindeBID($s)
    {
        $this->oGemeinde->setBID($s);
    }

    public function getTmp()
    {
        return $this->tmp;
    }

    public function setTmp($tmp)
    {
        $this->tmp = $tmp;
    }

    public function setFunktion($var)
    {
        $this->oAnsprechpartner->setFunktion($var);
    }

    public function getFunktion()
    {
        return $this->oAnsprechpartner->getFunktion();
    }

    public function setNachname($var)
    {
        $this->oAnsprechpartner->setNachname($var);
    }

    public function getNachname()
    {
        return $this->oAnsprechpartner->getNachname();
    }

    public function setVorname($var)
    {
        $this->oAnsprechpartner->setVorname($var);
    }

    public function getVorname()
    {
        return $this->oAnsprechpartner->getVorname();
    }

    public function setTelefon($var)
    {
        $this->oAnsprechpartner->setTelefon($var);
    }

    public function getTelefon()
    {
        return $this->oAnsprechpartner->getTelefon();
    }
}
?>
