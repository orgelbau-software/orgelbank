<?php

/**
 * Nebenkosten Rechnung für Projekte. Zum Verwalten von Fahrzeugkosten oder auch Spesen.
 * @author Stephan
 *
 */
class NebenkostenRechnung extends SimpleDatabaseStorageObjekt
{

    private $kommentar;

    private $betrag;

    private $projektID;

    private $datum;

    private $nummer;

    private $lieferant;

    private $leistung;
    
    private $benutzerID;

    public function __construct($iID = 0)
    {
        parent::__construct($iID, "nk_id", "nebenkosten_rechnung", "nk_");
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->projektID = $rs['proj_id'];
        $this->betrag = $rs["nk_betrag"];
        $this->kommentar = $rs['nk_kommentar'];
        $this->datum = $rs['nk_datum'];
        $this->nummer = $rs['nk_nummer'];
        $this->lieferant = $rs['nk_lieferant'];
        $this->leistung = $rs['nk_leistung'];
        $this->benutzerID = $rs['be_id'];
    }

    protected function doNew()
    {}

    protected function generateHashtable()
    {
        $ht = new HashTable();
        $ht->add("proj_id", $this->getProjektID());
        $ht->add("nk_betrag", $this->getBetrag());
        $ht->add("nk_kommentar", $this->getKommentar());
        $ht->add("nk_datum", $this->getDatum());
        $ht->add("nk_nummer", $this->getNummer());
        $ht->add("nk_lieferant", $this->getLieferant());
        $ht->add("nk_leistung", $this->getLeistung());
        $ht->add("be_id", $this->getBenutzerID());
        return $ht;
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

    public function getLeistung()
    {
        return $this->leistung;
    }

    public function setLeistung($leistung)
    {
        $this->leistung = $leistung;
    }
    
    public function getBenutzerID()
    {
        return $this->benutzerID;
    }
    
    public function setBenutzerID($benutzerID)
    {
        $this->benutzerID = $benutzerID;
    }
}
?>
