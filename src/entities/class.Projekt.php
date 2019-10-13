<?php

class Projekt extends SimpleDatabaseStorageObjekt
{

    private $bezeichnung;

    private $beschreibung;

    private $start;

    private $ende;

    private $angebotsPreis;

    private $gemeindeID;

    private $geloescht;

    private $archviert;

    private $archivdatum;

    private $keineZeitenFuer;

    private $sortierung;

    public function __construct($iID = 0)
    {
        parent::__construct($iID, "proj_id", "projekt", "proj_");
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setBeschreibung($rs['proj_beschreibung']);
        $this->setBezeichnung($rs['proj_bezeichnung']);
        $this->setStart($rs['proj_start']);
        $this->setEnde($rs['proj_ende']);
        $this->setGemeindeID($rs['g_id']);
        $this->setGeloescht($rs['proj_geloescht']);
        $this->setArchivdatum($rs['proj_archivdatum']);
        $this->setArchviert($rs['proj_archiviert']);
        $this->setAngebotsPreis($rs['proj_angebotspreis']);
        $this->setKeineZeitenFuer($rs['proj_keinezeitenfuer']);
        $this->setSortierung($rs['proj_sortierung']);
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add("proj_beschreibung", $this->getBeschreibung());
        $ht->add("proj_bezeichnung", $this->getBezeichnung());
        $ht->add("proj_start", $this->getStart());
        $ht->add("proj_ende", $this->getEnde());
        $ht->add("g_id", $this->getGemeindeID());
        $ht->add("proj_geloescht", $this->getGeloescht());
        $ht->add("proj_archiviert", $this->isArchviert());
        $ht->add("proj_archivdatum", $this->getArchivdatum());
        $ht->add("proj_angebotspreis", $this->getAngebotsPreis());
        $ht->add("proj_keinezeitenfuer", $this->getKeineZeitenFuer());
        $ht->add("proj_sortierung", $this->getSortierung());
        
        return $ht;
    }

    public function loeschen()
    {
        $this->setGeloescht(1);
    }

    public function getEnde($formatiert = false, $shortFormat = false)
    {
        if ($formatiert && $this->ende != "") {
            if ($shortFormat) {
                return date("d.m.y", strtotime($this->ende));
            } else {
                return date("d.m.Y", strtotime($this->ende));
            }
        }
        return $this->ende;
    }

    public function setEnde($ende)
    {
        $ende = date("Y-m-d", strtotime($ende));
        if ($this->ende != $ende) {
            $this->ende = $ende;
            $this->setChanged(true);
        }
    }

    public function getGemeindeID()
    {
        return $this->gemeindeID;
    }

    public function setGemeindeID($gemeindeID)
    {
        if ($this->gemeindeID != $gemeindeID) {
            $this->gemeindeID = $gemeindeID;
            $this->setChanged(true);
        }
    }

    public function getStart($formatiert = false, $shortFormat = false)
    {
        if ($formatiert && $this->start != "") {
            if ($shortFormat) {
                return date("d.m.y", strtotime($this->start));
            } else {
                return date("d.m.Y", strtotime($this->start));
            }
        }
        return $this->start;
    }

    public function setStart($start)
    {
        $start = date("Y-m-d", strtotime($start));
        if ($this->start != $start) {
            $this->start = $start;
            $this->setChanged(true);
        }
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

    public function getArchivdatum($formatiert = false)
    {
        if ($formatiert) {
            return date("d.m.Y", strtotime($this->archivdatum));
        }
        return $this->archivdatum;
    }

    public function isArchviert()
    {
        if ($this->archviert == 1)
            return true;
        return false;
    }

    public function setArchivdatum($archivdatum = 0)
    {
        if ($this->archivdatum != $archivdatum) {
            if ($archivdatum == 0)
                $archivdatum = date("Y-m-d H:i:s");
            $this->archivdatum = $archivdatum;
            $this->setChanged(true);
        }
    }

    public function setArchviert($archviert)
    {
        if ($this->archviert != $archviert) {
            $this->archviert = $archviert;
            $this->setChanged(true);
        }
    }

    public function getAngebotsPreis()
    {
        return $this->angebotsPreis;
    }

    public function setAngebotsPreis($angebotsPreis)
    {
        $this->angebotsPreis = $angebotsPreis;
    }

    public function getKeineZeitenFuer()
    {
        return $this->keineZeitenFuer;
    }

    public function setKeineZeitenFuer($keineZeitenFuer)
    {
        $this->keineZeitenFuer = $keineZeitenFuer;
    }

    public function getSortierung()
    {
        return $this->sortierung;
    }

    public function setSortierung($sortierung)
    {
        $this->sortierung = $sortierung;
    }
}

?>