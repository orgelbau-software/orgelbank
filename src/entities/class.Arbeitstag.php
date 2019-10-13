<?php

class Arbeitstag extends SimpleDatabaseStorageObjekt
{

    private $benutzerID;

    private $aufgabeID;
    
    private $arbeitswocheID;

    private $istStunden;

    private $sollStunden;

    private $difStunden;

    private $datum;

    private $kommentar;

    private $projektID;

    private $komplett;

    private $gesperrt;

    public function __construct($iArbeitstagID = 0)
    {
        parent::__construct($iArbeitstagID, "at_id", "arbeitstag", "at_");
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add("be_id", $this->getBenutzerID());
        $ht->add("au_id", $this->getAufgabeID());
        $ht->add("aw_id", $this->getArbeitswocheID());
        $ht->add("at_stunden_ist", $this->getIstStunden());
        $ht->add("at_stunden_soll", $this->getSollStunden());
        $ht->add("at_stunden_dif", $this->getDifStunden(true));
        $ht->add("at_datum", $this->getDatum());
        $ht->add("at_kommentar", $this->getKommentar());
        $ht->add("proj_id", $this->getProjektID());
        $ht->add("at_komplett", $this->getKomplett());
        $ht->add("at_gesperrt", $this->getGesperrt());
        
        return $ht;
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setBenutzerID($rs['be_id']);
        $this->setAufgabeID($rs['au_id']);
        $this->setArbeitswocheID($rs['aw_id']);
        $this->setIstStunden($rs['at_stunden_ist']);
        $this->setSollStunden($rs['at_stunden_soll']);
        $this->setDifStunden($rs['at_stunden_dif']);
        $this->setDatum($rs['at_datum']);
        $this->setKommentar($rs['at_kommentar']);
        $this->setProjektID($rs['proj_id']);
        $this->setKomplett($rs['at_komplett']);
        $this->setGesperrt($rs['at_gesperrt']);
    }

    public function getAufgabeID()
    {
        return $this->aufgabeID;
    }

    public function getBenutzerID()
    {
        return $this->benutzerID;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function setAufgabeID($aufgabeID)
    {
        if ($this->aufgabeID != $aufgabeID) {
            $this->aufgabeID = $aufgabeID;
            $this->setChanged(true);
        }
    }

    public function setBenutzerID($benutzerID)
    {
        if ($this->benutzerID != $benutzerID) {
            $this->benutzerID = $benutzerID;
            $this->setChanged(true);
        }
    }

    public function setDatum($datum)
    {
        if ($this->datum != $datum) {
            $this->datum = $datum;
            $this->setChanged(true);
        }
    }

    public function getKommentar()
    {
        return $this->kommentar;
    }

    public function setKommentar($kommentar)
    {
        if ($this->kommentar != $kommentar) {
            $this->kommentar = $kommentar;
            $this->setChanged(true);
        }
    }

    public function getProjektID()
    {
        return $this->projektID;
    }

    public function setProjektID($projektID)
    {
        if ($this->projektID != $projektID) {
            $this->projektID = $projektID;
            $this->setChanged(true);
        }
    }

    public function getGesperrt()
    {
        return $this->gesperrt;
    }

    public function getKomplett()
    {
        return $this->komplett;
    }

    public function setGesperrt($gesperrt)
    {
        if ($this->gesperrt != $gesperrt) {
            $this->gesperrt = $gesperrt;
            $this->setChanged(true);
        }
    }

    public function setKomplett($komplett)
    {
        if ($this->komplett != $komplett) {
            $this->komplett = $komplett;
            $this->setChanged(true);
        }
    }

    public function getDifStunden($neuberechnen = false)
    {
        if ($neuberechnen) {
            $this->difStunden = $this->istStunden - $this->sollStunden;
        }
        return $this->difStunden;
    }

    public function getIstStunden()
    {
        return $this->istStunden;
    }

    public function getSollStunden()
    {
        return $this->sollStunden;
    }

    public function setDifStunden($difStunden)
    {
        if ($this->difStunden != $difStunden) {
            $this->difStunden = $difStunden;
            $this->setChanged(true);
        }
    }

    public function setIstStunden($istStunden)
    {
        if ($this->istStunden != $istStunden) {
            $this->istStunden = $istStunden;
            $this->setChanged(true);
        }
    }

    public function setSollStunden($sollStunden)
    {
        if ($this->sollStunden != $sollStunden) {
            $this->sollStunden = $sollStunden;
            $this->setChanged(true);
        }
    }
    
    public function getArbeitswocheID()
    {
        return $this->arbeitswocheID;
    }
    
    public function setArbeitswocheID($arbeitswocheID)
    {
        if ($this->arbeitswocheID != $arbeitswocheID) {
            $this->arbeitswocheID = $arbeitswocheID;
            $this->setChanged(true);
        }
    }
}
?>
