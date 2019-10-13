<?php

/**
 * Bei der Druckansicht der Gemeinden werden die Ansprechpartnerdaten mit 
 * ausgegeben. Zu diesem Zweck gibts diese Storage-Klasse.
 * 
 * Ein Aufruf von Speichern führt höchstens zu der Speicherung der Gemeindedaten, 
 * die Ansprechpartnerdaten werden verworfen
 *
 */
class GemeindeAnsprechpartner extends Gemeinde
{

    private $oAnsprechpartner;

    public function __construct($iGemeindeID = 0)
    {
        $this->oAnsprechpartner = new Ansprechpartner();
        parent::__construct($iGemeindeID);
    }

    public function getVorname()
    {
        return $this->oAnsprechpartner->getVorname();
    }

    public function getNachname()
    {
        return $this->oAnsprechpartner->getNachname();
    }

    public function getTelefon()
    {
        return $this->oAnsprechpartner->getTelefon();
    }

    public function getMobil()
    {
        return $this->oAnsprechpartner->getMobil();
    }

    public function getFunktion()
    {
        return $this->oAnsprechpartner->getFunktion();
    }

    public function setVorname($s)
    {
        $this->oAnsprechpartner->setVorname($s);
    }

    public function setNachname($s)
    {
        $this->oAnsprechpartner->setNachname($s);
    }

    public function setTelefon($s)
    {
        $this->oAnsprechpartner->setTelefon($s);
    }

    public function setMobil($s)
    {
        $this->oAnsprechpartner->setMobil($s);
    }

    public function setFunktion($s)
    {
        $this->oAnsprechpartner->setFunktion($s);
    }
}
?>
