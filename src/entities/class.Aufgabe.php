<?php

class Aufgabe extends SimpleDatabaseStorageObjekt
{

    private $bezeichnung;

    private $beschreibung;

    private $geloescht;

    private $parentID;

    public function __construct($iID = 0)
    {
        parent::__construct($iID, "au_id", "aufgabe", "au_");
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->beschreibung = $rs["au_beschreibung"];
        $this->bezeichnung = $rs["au_bezeichnung"];
        $this->geloescht = $rs["au_geloescht"];
        $this->parentID = $rs['au_parentid'];
    }

    protected function doNew()
    {}

    protected function generateHashtable()
    {
        $ht = new HashTable();
        $ht->add("au_beschreibung", $this->getBeschreibung());
        $ht->add("au_bezeichnung", $this->getBezeichnung());
        $ht->add("au_geloescht", $this->getGeloescht());
        $ht->add("au_parentid", $this->getParentID());
        return $ht;
    }

    public function loeschen()
    {
        $this->setGeloescht(1);
        $this->setBezeichnung($this->getBezeichnung() . " (gelöscht)");
    }

    public function getBeschreibung()
    {
        return $this->beschreibung;
    }

    public function setBeschreibung($beschreibung)
    {
        if ($this->beschreibung != $beschreibung) {
            $this->beschreibung = $beschreibung;
            $this->setChanged(true);
        }
    }

    public function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    public function setBezeichnung($bezeichnung)
    {
        if ($this->bezeichnung != $bezeichnung) {
            $this->bezeichnung = $bezeichnung;
            $this->setChanged(true);
        }
    }

    public function getGeloescht()
    {
        return $this->geloescht;
    }

    public function setGeloescht($geloescht)
    {
        if ($this->geloescht != $geloescht) {
            $this->geloescht = $geloescht;
            $this->setChanged(true);
        }
    }

    public function getParentID()
    {
        return $this->parentID;
    }

    public function setParentID($parentID)
    {
        if ($this->parentID != $parentID) {
            $this->parentID = $parentID;
            $this->setChanged(true);
        }
    }
}

?>
