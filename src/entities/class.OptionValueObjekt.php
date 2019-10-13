<?php

/**
 * @author swatermeyer
 * @version $Revision: $
 *
 */
class OptionvalueObjekt extends SimpleDatabaseStorageObjekt
{

    // Variablen
    private $strOptionName;

    private $strOptionValue;

    private $strComment;

    private $boEditable;

    private $boAutoload;

    private $modul;

    // Konstruktor
    public function __construct($iID = 0, $primaryKey = "option_id", $tableName = "option_meta", $tablePrefix = "option_")
    {
        parent::__construct($iID, $primaryKey, $tableName, $tablePrefix);
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add($this->tablePrefix . "modul", $this->getModul());
        $ht->add($this->tablePrefix . "name", $this->getName());
        $ht->add($this->tablePrefix . "value", $this->getValue());
        $ht->add($this->tablePrefix . "comment", $this->getComment());
        $ht->add($this->tablePrefix . "editable", $this->isEditable());
        $ht->add($this->tablePrefix . "autoload", $this->getAutoload());
        
        return $ht;
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setModul($rs[$this->tablePrefix . 'modul']);
        $this->setName($rs[$this->tablePrefix . 'name']);
        $this->setValue($rs[$this->tablePrefix . 'value']);
        $this->setComment($rs[$this->tablePrefix . 'comment']);
        $this->isEditable($rs[$this->tablePrefix . 'editable']);
        $this->setAutoload($rs[$this->tablePrefix . 'autoload']);
    }

    public function __toString()
    {
        return $this->getName();
    }

    // Getter & Setter
    public function getName()
    {
        return $this->strOptionName;
    }

    public function setName($var)
    {
        if ($this->strOptionName != $var) {
            $this->strOptionName = $var;
            $this->setChanged(true);
        }
    }

    public function getValue()
    {
        return $this->strOptionValue;
    }

    public function setValue($var)
    {
        if ($this->strOptionValue != $var) {
            $this->strOptionValue = $var;
            $this->setChanged(true);
        }
    }

    public function getComment()
    {
        return $this->strComment;
    }

    public function setComment($var)
    {
        if ($this->strComment != $var) {
            $this->strComment = $var;
            $this->setChanged(true);
        }
    }

    public function isEditable()
    {
        return $this->boEditable;
    }

    public function setEditable($var)
    {
        if ($this->boEditable != $var) {
            $this->boEditable = $var;
            $this->setChanged(true);
        }
    }

    public function getAutoload()
    {
        return $this->boAutoload;
    }

    public function setAutoload($var)
    {
        if ($this->boAutoload != $var) {
            $this->boAutoload = $var;
            $this->setChanged(true);
        }
    }

    public function getModul()
    {
        return $this->modul;
    }

    public function setModul($modul)
    {
        $this->modul = $modul;
    }
}
?>