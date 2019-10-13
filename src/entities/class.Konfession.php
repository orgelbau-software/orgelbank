<?php

class Konfession extends SimpleDatabaseStorageObjekt
{

    private $bezeichnung;

    private $anschrift;

    private $kurzform;

    private $genitiv;

    public function __construct($iID = 0)
    {
        parent::__construct($iID, "k_id", "konfession", "k_");
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->bezeichnung = $rs['k_name'];
        $this->anschrift = $rs['k_anschrift'];
        $this->genitiv = $rs['k_genitiv'];
        $this->kurzform = $rs['k_kurzform'];
    }

    protected function doNew()
    {}

    protected function generateHashtable()
    {
        $ht = new HashTable();
        $ht->add("k_name", $this->getBezeichnung());
        $ht->add("k_anschrift", $this->getAnschrift());
        $ht->add("k_genitiv", $this->getGenitiv());
        $ht->add("k_kurzform", $this->getKurzform());
        return $ht;
    }

    public function loeschen()
    {}

    public function getAnschrift()
    {
        return $this->anschrift;
    }

    public function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    public function setAnschrift($anschrift)
    {
        if ($this->anschrift != $anschrift) {
            $this->anschrift = $anschrift;
            $this->setChanged(true);
        }
    }

    public function setBezeichnung($bezeichnung)
    {
        if ($this->bezeichnung != $bezeichnung) {
            $this->bezeichnung = $bezeichnung;
            $this->setChanged(true);
        }
    }

    public function getGenitiv()
    {
        return $this->genitiv;
    }

    public function setGenitiv($genitiv)
    {
        if ($this->genitiv != $genitiv) {
            $this->genitiv = $genitiv;
            $this->setChanged(true);
        }
    }

    public function getKurzform()
    {
        return $this->kurzform;
    }

    public function setKurzform($kurzform)
    {
        if ($this->kurzform != $kurzform) {
            $this->kurzform = $kurzform;
            $this->setChanged(true);
        }
    }
}
?>