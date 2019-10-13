<?php

class ProjektRechnung extends SimpleDatabaseStorageObjekt
{

    private $kommentar;

    private $betrag;

    private $projektID;

    private $aufgabeID;

    private $datum;

    private $nummer;

    private $lieferant;

    public function __construct($iID = 0)
    {
        parent::__construct($iID, "pr_id", "projekt_rechnung", "pr_");
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->projektID = $rs['proj_id'];
        $this->aufgabeID = $rs["pa_id"];
        $this->betrag = $rs["pr_betrag"];
        $this->kommentar = $rs['pr_kommentar'];
        $this->datum = $rs['pr_datum'];
        $this->nummer = $rs['pr_nummer'];
        $this->lieferant = $rs['pr_lieferant'];
    }

    protected function doNew()
    {}

    protected function generateHashtable()
    {
        $ht = new HashTable();
        $ht->add("proj_id", $this->getProjektID());
        $ht->add("pa_id", $this->getAufgabeID());
        $ht->add("pr_betrag", $this->getBetrag());
        $ht->add("pr_kommentar", $this->getKommentar());
        $ht->add("pr_datum", $this->getDatum());
        $ht->add("pr_nummer", $this->getNummer());
        $ht->add("pr_lieferant", $this->getLieferant());
        return $ht;
    }

    public function getAufgabeID()
    {
        return $this->aufgabeID;
    }

    public function getBetrag()
    {
        return $this->betrag;
    }

    public function getKommentar()
    {
        return $this->kommentar;
    }

    public function getProjektID()
    {
        return $this->projektID;
    }

    public function setAufgabeID($aufgabeID)
    {
        $this->aufgabeID = $aufgabeID;
    }

    public function setBetrag($betrag)
    {
        $this->betrag = $betrag;
    }

    public function setKommentar($kommentar)
    {
        $this->kommentar = $kommentar;
    }

    public function setProjektID($projektID)
    {
        $this->projektID = $projektID;
    }

    public function getDatum($formatiert = false)
    {
        if ($formatiert) {
            return date("d.m.Y", strtotime($this->datum));
        }
        return $this->datum;
    }

    public function setDatum($datum)
    {
        $this->datum = $datum;
    }

    public function getNummer()
    {
        return $this->nummer;
    }

    public function setNummer($nummer)
    {
        $this->nummer = $nummer;
    }

    public function getLieferant()
    {
        return $this->lieferant;
    }

    public function setLieferant($lieferant)
    {
        $this->lieferant = $lieferant;
    }
}
?>
