<?php

class ProjektAufgabe extends AdvancedDatabaseStorageObjekt
{

    private $plankosten;
    private $sollStunden;
    private $istStunden;
    private $sollMaterial;
    
    private $reihenfolge;
    private $bezeichnung;
    
 // Transient
    private $beschreibung;
 // Transient
    public function __construct($projektID = 0, $aufgabeID = 0)
    {
        parent::__construct(array(
            $aufgabeID,
            $projektID
        ), array(
            "au_id",
            "proj_id"
        ), "projekt_aufgabe", "pa_");
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->plankosten = $rs["pa_plankosten"];
        $this->reihenfolge = $rs["pa_reihenfolge"];
        $this->sollStunden = $rs["pa_sollstunden"];
        $this->istStunden = $rs["pa_iststunden"];
        $this->sollMaterial = $rs["pa_sollmaterial"];
    }

    protected function doNew()
    {}

    protected function generateHashtable()
    {
        $ht = new HashTable();
        $ht->add("pa_plankosten", $this->getPlankosten());
        $ht->add("pa_reihenfolge", $this->getReihenfolge());
        $ht->add("pa_sollstunden", $this->getSollStunden());
        $ht->add("pa_iststunden", $this->getIstStunden());
        $ht->add("pa_sollmaterial", $this->getSollMaterial());
        return $ht;
    }

    public function getPKAufgabeID()
    {
        return $this->primaryKeyValues["au_id"];
    }

    public function getPKProjektID()
    {
        return $this->primaryKeyValues["proj_id"];
    }

    public function setPKAufgabeID($PKaufgabeID)
    {
        $this->primaryKeyValues["au_id"] = $PKaufgabeID;
    }

    public function setPKProjektID($PKprojektID)
    {
        $this->primaryKeyValues["proj_id"] = $PKprojektID;
    }

    public function getPlankosten()
    {
        return $this->plankosten;
    }

    public function setPlankosten($plankosten)
    {
        $this->plankosten = $plankosten;
    }
    
    public function getReihenfolge()
    {
        return $this->reihenfolge;
    }
    
    public function setReihenfolge($pReihenfolge)
    {
        $this->reihenfolge = $pReihenfolge;
    }

    public function getBeschreibung()
    {
        return $this->beschreibung;
    }

    public function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    public function setBeschreibung($beschreibung)
    {
        $this->beschreibung = $beschreibung;
    }

    public function setBezeichnung($bezeichnung)
    {
        $this->bezeichnung = $bezeichnung;
    }
    
    public function getSollStunden()
    {
        return $this->sollStunden;
    }
    
    public function setSollStunden($pSollStunden)
    {
        $this->sollStunden = $pSollStunden;
    }
    
    public function getIstStunden()
    {
        return $this->istStunden;
    }
    
    public function setIstStunden($pIstStunden)
    {
        $this->istStunden = $pIstStunden;
    }
    
    public function getSollMaterial()
    {
        return $this->sollMaterial;
    }
    
    public function setSollMaterial($pSollMaterial)
    {
        $this->sollMaterial = $pSollMaterial;
    }
}
?>
