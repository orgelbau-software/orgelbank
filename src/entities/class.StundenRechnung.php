<?php

class StundenRechnung extends PositionsRechnung
{

    protected $azubiLohn;

    protected $azubiStd;

    protected $geselleLohn;

    protected $geselleStd;

    protected $material;

    public function __construct($iRechnungsID = 0)
    {
        parent::__construct($iRechnungsID, "rs_id", "rechnung_stunde", "rs_");
    }

    protected function generateHashtable()
    {
        $ht = parent::generateHashtable();
        $ht->add($this->tablePrefix . "azubi_lohn", $this->getAzubiLohn());
        $ht->add($this->tablePrefix . "azubi_std", $this->getAzubiStd());
        $ht->add($this->tablePrefix . "geselle_lohn", $this->getGeselleLohn());
        $ht->add($this->tablePrefix . "geselle_std", $this->getGeselleStd());
        $ht->add($this->tablePrefix . "material", $this->getMaterial());
        
        return $ht;
    }

    protected function laden()
    {
        parent::laden();
        $rs = $this->result;
        
        $this->setFahrtkosten($rs[$this->tablePrefix . "fahrtkosten"]);
        $this->setAzubiLohn($rs[$this->tablePrefix . 'azubi_lohn']);
        $this->setAzubiStd($rs[$this->tablePrefix . 'azubi_std']);
        $this->setGeselleLohn($rs[$this->tablePrefix . 'geselle_lohn']);
        $this->setGeselleStd($rs[$this->tablePrefix . 'geselle_std']);
        $this->setMaterial($rs[$this->tablePrefix . 'material']);
    }

    public function errechneGesamtBetrag($speichern = false)
    {
        if ($speichern) {
            $retVal = $this->getAzubiLohn() * $this->getAzubiStd();
            $retVal += $this->getGeselleLohn() * $this->getGeselleStd();
            $retVal += $this->getMaterial();
            $retVal += $this->getFahrtkosten();
            $this->setNettoBetrag($retVal, true);
        }
        return $this->getNettoBetrag();
    }

    public function getSpeicherOrt()
    {
        $oGemeinde = new Gemeinde($this->getGemeindeID());
        $rechNr = str_replace("/", "-", $this->getNummer());
        $kirche = str_replace("/", "-", $oGemeinde->getKirche());
        $ziel = parent::getSpeicherOrt() . "stunde/" . $kirche . "-" . $rechNr;
        $ziel = Utilities::ersetzeZeichen($ziel);
        return $ziel;
    }

    public function getAzubiLohn($formatiert = false)
    {
        if ($formatiert)
            return Rechnung::toWaehrung($this->azubiLohn);
        return $this->azubiLohn;
    }

    public function getAzubiStd()
    {
        return $this->azubiStd;
    }

    public function getGeselleLohn($formatiert = false)
    {
        if ($formatiert)
            return Rechnung::toWaehrung($this->geselleLohn);
        return $this->geselleLohn;
    }

    public function getGeselleStd()
    {
        return $this->geselleStd;
    }

    public function getMaterial($formatiert = false)
    {
        if ($formatiert)
            return Rechnung::toWaehrung($this->material);
        return $this->material;
    }

    public function setAzubiLohn($azubiLohn)
    {
        if ($this->azubiLohn != $azubiLohn) {
            $this->azubiLohn = WaehrungUtil::KommaToPunkt($azubiLohn);
            $this->setChanged(true);
        }
    }

    public function setAzubiStd($azubiStd)
    {
        if ($this->azubiStd != $azubiStd) {
            $this->azubiStd = WaehrungUtil::KommaToPunkt($azubiStd);
            // $this->azubiStd = $azubiStd;
            $this->setChanged(true);
        }
    }

    public function setGeselleLohn($geselleLohn)
    {
        if ($this->geselleLohn != $geselleLohn) {
            $this->geselleLohn = WaehrungUtil::KommaToPunkt($geselleLohn);
            $this->setChanged(true);
        }
    }

    public function setGeselleStd($geselleStd)
    {
        if ($this->geselleStd != $geselleStd) {
            $this->geselleStd = WaehrungUtil::KommaToPunkt($geselleStd);
            $this->setChanged(true);
        }
    }

    public function setMaterial($material)
    {
        if ($this->material != $material) {
            $this->material = WaehrungUtil::KommaToPunkt($material);
            $this->setChanged(true);
        }
    }
}
?>