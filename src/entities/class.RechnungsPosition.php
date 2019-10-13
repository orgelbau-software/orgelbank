<?php

class RechnungsPosition extends SimpleDatabaseStorageObjekt
{

    private $rechnungsID;

    private $position;

    private $text;

    public function __construct($iID = 0, $primaryKey = "rpos_id", $tableName = "rechnung_position", $tablePrefix = "rpos_")
    {
        parent::__construct($iID, $primaryKey, $tableName, $tablePrefix);
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        $ht->add("r_id", $this->getRechnungsID());
        $ht->add($this->tablePrefix . "position", $this->getPosition());
        $ht->add($this->tablePrefix . "text", $this->getText());
        
        return $ht;
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setRechnungsID($rs["r_id"]);
        $this->setText($rs[$this->tablePrefix . 'text']);
        $this->setPosition($rs[$this->tablePrefix . 'position']);
        $this->setChanged(true); // soll immer gespeichert werden
    }

    public function getRechnungsID()
    {
        return $this->rechnungsID;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setRechnungsID($id)
    {
        $this->rechnungsID = $id;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function setText($text)
    {
        $this->text = $text;
    }
}