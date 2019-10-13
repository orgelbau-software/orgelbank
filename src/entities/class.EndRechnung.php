<?php

class EndRechnung extends Rechnung
{

    protected $abschlag1;

    protected $abschlag2;

    protected $abschlag3;

    protected $titel;

    protected $text;

    protected $gesamtNetto;

    protected $gesamtBrutto;

    protected $gesamtMwSt;

    public function __construct($iRechnungsID = 0)
    {
        parent::__construct($iRechnungsID, "re_id", "rechnung_end", "re_");
    }

    protected function generateHashtable()
    {
        $ht = parent::generateHashtable();
        
        $ht->add($this->tablePrefix . "titel", $this->getTitel());
        $ht->add($this->tablePrefix . "text", $this->getText());
        $ht->add($this->tablePrefix . "gesamtnetto", $this->getGesamtNetto());
        $ht->add($this->tablePrefix . "gesamtbrutto", $this->getGesamtBrutto());
        $ht->add($this->tablePrefix . "gesamtmwst", $this->getGesamtMwSt());
        
        return $ht;
    }

    protected function laden()
    {
        parent::laden();
        $rs = $this->result;
        
        $this->setTitel($rs[$this->tablePrefix . 'titel']);
        $this->setText($rs[$this->tablePrefix . 'text']);
        $this->setGesamtNetto($rs[$this->tablePrefix . 'gesamtnetto']);
        $this->setGesamtBrutto($rs[$this->tablePrefix . 'gesamtbrutto']);
        $this->setGesamtMwSt($rs[$this->tablePrefix . 'gesamtmwst']);
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
        $ziel = parent::getSpeicherOrt() . "end/" . $kirche . "-" . $rechNr;
        $ziel = Utilities::ersetzeZeichen($ziel);
        return $ziel;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getTitel()
    {
        return $this->titel;
    }

    public function setText($text)
    {
        if ($this->text != $text) {
            $this->text = $text;
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

    public function getGesamtNetto()
    {
        return $this->gesamtNetto;
    }

    public function getGesamtBrutto()
    {
        return $this->gesamtBrutto;
    }

    public function getGesamtMwSt()
    {
        return $this->gesamtMwSt;
    }

    public function setGesamtNetto($gesamtNetto, $berechneBrutto = false)
    {
        $this->gesamtNetto = $gesamtNetto;
        if ($berechneBrutto === true) {
            $this->berrechneGesamtBrutto();
        }
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
}
?>