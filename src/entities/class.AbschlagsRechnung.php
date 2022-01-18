<?php

class AbschlagsRechnung extends Rechnung
{

    public static $TYPE_ID = 3;
    
    protected $titel;

    protected $aNr;

    protected $einleitung;

    protected $abschlagSatz;

    protected $gesamtNetto;

    protected $gesamtBrutto;

    protected $gesamtMwSt;

    public function __construct($iRechnungsID = 0)
    {
        parent::__construct($iRechnungsID, "ra_id", "rechnung_abschlag", "ra_");
    }

    protected function generateHashtable()
    {
        $ht = parent::generateHashtable();
        
        $ht->add($this->tablePrefix . "anr", $this->getANr());
        $ht->add($this->tablePrefix . "titel", $this->getTitel());
        $ht->add($this->tablePrefix . "einleitung", $this->getEinleitung());
        $ht->add($this->tablePrefix . "gesamtnetto", $this->getGesamtNetto());
        $ht->add($this->tablePrefix . "gesamtbrutto", $this->getGesamtBrutto());
        $ht->add($this->tablePrefix . "gesamtmwst", $this->getGesamtMwSt());
        $ht->add($this->tablePrefix . "abschlagsatz", $this->getAbschlagSatz());
        
        return $ht;
    }

    protected function laden()
    {
        parent::laden();
        $rs = $this->result;
        
        $this->setANr($rs[$this->tablePrefix . 'anr']);
        $this->setTitel($rs[$this->tablePrefix . 'titel']);
        $this->setEinleitung($rs[$this->tablePrefix . 'einleitung']);
        $this->setGesamtNetto($rs[$this->tablePrefix . 'gesamtnetto']);
        $this->setGesamtBrutto($rs[$this->tablePrefix . 'gesamtbrutto']);
        $this->setGesamtMwSt($rs[$this->tablePrefix . 'gesamtmwst']);
        $this->setAbschlagSatz($rs[$this->tablePrefix . 'abschlagsatz']);
    }

    public function errechneGesamtBetrag($speichern = false)
    {
        if ($speichern) {
            $this->setNettoBetrag($this->getNettoBetrag(), true);
        }
        return $this->getNettoBetrag();
    }

    public function getSpeicherOrt()
    {
        $oGemeinde = new Gemeinde($this->getGemeindeID());
        $rechNr = str_replace("/", "-", $this->getNummer());
        $kirche = str_replace("/", "-", $oGemeinde->getKirche());
        $ziel = parent::getSpeicherOrt() . "/abschlag/" . $kirche . "-" . $rechNr;
        $ziel = Utilities::ersetzeZeichen($ziel);
        $ziel .= MSWordOutput::$FILE_EXTENSTION;
        return $ziel;
    }

    public function getANr()
    {
        return $this->aNr;
    }

    public function getEinleitung()
    {
        return $this->einleitung;
    }

    public function getTitel()
    {
        return $this->titel;
    }

    public function setANr($aNr)
    {
        if ($this->aNr != $aNr) {
            $this->aNr = $aNr;
            $this->setChanged(true);
        }
    }

    public function setEinleitung($einleitung)
    {
        if ($this->einleitung != $einleitung) {
            $this->einleitung = $einleitung;
            $this->setChanged(true);
        }
    }

    public function setTitel($titel)
    {
        if ($this->titel != $titel) {
            $this->titel = $titel;
            $this->setChanged(true);
        }
    }

    public function getGesamtNetto($formatiert = false)
    {
        if ($formatiert)
            return Rechnung::toWaehrung($this->gesamtNetto);
        return $this->gesamtNetto;
    }

    public function setGesamtNetto($gesamtNetto, $berechneBrutto = false)
    {
        $this->gesamtNetto = $gesamtNetto;
        if ($berechneBrutto === true) {
            $this->berrechneGesamtBrutto();
        }
    }

    public function getAbschlagSatz()
    {
        return $this->abschlagSatz;
    }

    public function setAbschlagSatz($abschlagSatz)
    {
        $this->abschlagSatz = $abschlagSatz;
    }

    public function getGesamtBrutto($formatiert = false)
    {
        if ($formatiert)
            return Rechnung::toWaehrung($this->gesamtBrutto);
        return $this->gesamtBrutto;
    }

    public function getGesamtMwSt($formatiert = false)
    {
        if ($formatiert)
            return Rechnung::toWaehrung($this->gesamtMwSt);
        return $this->gesamtMwSt;
    }

    public function setGesamtBrutto($gesamtBrutto)
    {
        $this->gesamtBrutto = $gesamtBrutto;
    }

    public function setGesamtMwSt($gesamtMwSt)
    {
        $this->gesamtMwSt = $gesamtMwSt;
    }

    public function berrechneGesamtBrutto()
    {
        $this->gesamtMwSt = round($this->gesamtNetto * $this->MwStSatz, 2);
        $this->gesamtBrutto = $this->gesamtNetto + $this->gesamtMwSt;
    }
    
    public function getType() {
        return AbschlagsRechnung::$TYPE_ID;
    }
}
?>
