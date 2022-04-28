<?php

abstract class Rechnung extends SimpleDatabaseStorageObjekt
{

    protected $nummer;

    protected $nettoBetrag;

    protected $bruttoBetrag;

    protected $MwSt;

    protected $MwStSatz = MWST_SATZ;

    protected $gemeindeID;

    protected $datum;

    protected $zieldatum;

    protected $eingangsDatum;

    protected $eingangsBetrag;

    protected $eingangsAnmerkung;

    // Konstruktor
    public function __construct($iID = 0, $primaryKey = "r_id", $tableName = "rechnung", $tablePrefix = "r_")
    {
        parent::__construct($iID, $primaryKey, $tableName, $tablePrefix);
    }

    // Methoden
    public abstract function errechneGesamtBetrag($speichern = false);
    
    public abstract function getType();

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add($this->tablePrefix . "nummer", $this->getNummer());
        $ht->add("g_id", $this->getGemeindeID());
        $ht->add($this->tablePrefix . "datum", $this->getDatum());
        $ht->add($this->tablePrefix . "zieldatum", $this->getZieldatum());
        $ht->add($this->tablePrefix . "nettobetrag", $this->getNettoBetrag());
        $ht->add($this->tablePrefix . "bruttobetrag", $this->getBruttoBetrag());
        $ht->add($this->tablePrefix . "mwst", $this->getMwSt());
        $ht->add($this->tablePrefix . "mwstsatz", $this->getMwStSatz());
        $ht->add($this->tablePrefix . "eingangsdatum", $this->getEingangsDatum());
        $ht->add($this->tablePrefix . "eingangsbetrag", $this->getEingangsBetrag());
        $ht->add($this->tablePrefix . "eingangsanmerkung", $this->getEingangsAnmerkung());
        
        return $ht;
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setId($rs[$this->tablePrefix . "id"]);
        $this->setNummer($rs[$this->tablePrefix . "nummer"]);
        $this->setGemeindeID($rs['g_id']);
        $this->setDatumWellFormated($rs[$this->tablePrefix . "datum"]);
        $this->setZieldatum($rs[$this->tablePrefix . "zieldatum"]);
        $this->setNettoBetrag($rs[$this->tablePrefix . "nettobetrag"]);
        $this->setBruttoBetrag($rs[$this->tablePrefix . "bruttobetrag"]);
        $this->setMwSt($rs[$this->tablePrefix . "mwst"]);
        $this->setMwStSatz($rs[$this->tablePrefix . "mwstsatz"]);
        $this->setEingangsBetrag($rs[$this->tablePrefix . "eingangsbetrag"]);
        $this->setEingangsDatum($rs[$this->tablePrefix . "eingangsdatum"]);
        $this->setEingangsAnmerkung($rs[$this->tablePrefix . "eingangsanmerkung"]);
    }

    /**
     * Der gesamte Rechnungsbetrag inkl.
     * aller Kosten
     *
     * @return double Rechnungsbetrag
     */
    public function getNettoBetrag($formatiert = false)
    {
        if ($formatiert)
            return Rechnung::toWaehrung($this->nettoBetrag);
        return $this->nettoBetrag;
    }

    public function setNettoBetrag($betrag, $berechneBrutto = false)
    {
        $this->nettoBetrag = $betrag;
        if ($berechneBrutto === true) {
            $this->berrechneBrutto();
        }
    }

    public function berrechneBrutto()
    {
        $this->MwSt = round($this->nettoBetrag * $this->MwStSatz, 2);
        $this->bruttoBetrag = $this->nettoBetrag + $this->MwSt;
    }

    public function getBruttoBetrag($formatiert = false)
    {
        if ($formatiert) {
            return Rechnung::toWaehrung($this->bruttoBetrag);
        }
        return $this->bruttoBetrag;
    }

    public function setBruttoBetrag($betrag)
    {
        $this->bruttoBetrag = $betrag;
    }

    public static function toWaehrung($betrag)
    {
        return number_format($betrag, 2, ",", ".");
    }

    public function getDatum($formatiert = false)
    {
        if ($formatiert) {
            return date("d.m.Y", strtotime($this->datum));
        }
        return $this->datum;
    }

    public function getGemeindeID()
    {
        return $this->gemeindeID;
    }

    public function getNummer()
    {
        return $this->nummer;
    }

    public function getZieldatum($formatiert = false)
    {
        if ($formatiert) {
            return date("d.m.Y", strtotime($this->zieldatum));
        }
        return $this->zieldatum;
    }

    public function setDatum($datum)
    {
        $datum = date("Y-m-d", strtotime($datum));
        $this->datum = $datum;
    }

    public function setDatumWellFormated($datum)
    {
        $this->datum = $datum;
    }

    public function setGemeindeID($gemeindeID)
    {
        $this->gemeindeID = $gemeindeID;
    }

    public function setNummer($nummer)
    {
        $this->nummer = $nummer;
    }

    public function setZieldatum($datum)
    {
        $datum = date("Y-m-d", strtotime($datum));
        $this->zieldatum = $datum;
    }

    public function getMwSt($formatiert = false)
    {
        if ($formatiert)
            return Rechnung::toWaehrung($this->MwSt);
        return $this->MwSt;
    }

    public function getMwStSatz()
    {
        return $this->MwStSatz;
    }

    public function setMwSt($MwSt)
    {
        $this->MwSt = $MwSt;
    }

    public function setMwStSatz($MwStSatz)
    {
        if ($MwStSatz >= 1) {
            throw new IllegalArgumentException("MwSt Satz muss kleiner 1 sein", null);
        }
        
        if ($MwStSatz <= 0) {
            throw new IllegalArgumentException("MwSt Satz muss groesser 0 sein", null);
        }
        $this->MwStSatz = $MwStSatz;
    }

    public function getEingangsDatum($formatiert = false)
    {
        if ($formatiert) {
            return date("d.m.Y", strtotime($this->eingangsDatum));
        }
        return $this->eingangsDatum;
    }

    public function getEingangsBetrag($formatiert = false)
    {
        if ($formatiert)
            return Rechnung::toWaehrung($this->eingangsBetrag);
        return $this->eingangsBetrag;
    }

    public function setEingangsDatum($eingangsDatum)
    {
        $this->eingangsDatum = $eingangsDatum;
    }

    public function setEingangsBetrag($eingangsBetrag)
    {
        $this->eingangsBetrag = $eingangsBetrag;
    }

    public function getEingangsAnmerkung()
    {
        return $this->eingangsAnmerkung;
    }

    public function setEingangsAnmerkung($eingangsAnmerkung)
    {
        $this->eingangsAnmerkung = $eingangsAnmerkung;
    }
    
}
?>
