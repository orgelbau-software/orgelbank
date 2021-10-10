<?php

class Wartungsprotokoll extends SimpleDatabaseStorageObjekt
{

    private $name;

    private $bemerkung;

    private $dateiname;

    /**
     *
     * Enter description here ...
     *
     * @param int $iID            
     * @param String $primaryKey            
     * @param String $tableName            
     * @param String $tablePrefix            
     */
    public function __construct($iID = 0, $primaryKey = "wp_id", $tableName = "wartungsprotokoll", $tablePrefix = "wp_")
    {
        parent::__construct($iID, $primaryKey, $tableName, $tablePrefix);
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add($this->tablePrefix . "name", $this->getName());
        $ht->add($this->tablePrefix . "bemerkung", $this->getBemerkung());
        $ht->add($this->tablePrefix . "dateiname", $this->getDateiname());
        
        return $ht;
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setName($rs['wp_name']);
        $this->setBemerkung($rs['wp_bemerkung']);
        $this->setDateiname($rs['wp_dateiname']);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        if ($this->name != $name) {
            $this->name = $name;
            $this->setChanged(true);
        }
    }

    public function getBemerkung()
    {
        return $this->bemerkung;
    }

    public function setBemerkung($pBemerkung)
    {
        if ($this->bemerkung != $pBemerkung) {
            $this->bemerkung = $pBemerkung;
            $this->setChanged(true);
        }
    }

    public function getDateiname()
    {
        return $this->dateiname;
    }

    public function setDateiname($pDateiname)
    {
        if ($this->dateiname != $pDateiname) {
            $this->dateiname = $pDateiname;
            $this->setChanged(true);
        }
    }
}