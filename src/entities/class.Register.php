<?php

/**
 * Registereintrag für eine Orgel
 * 
 * Im Zuge von Version 3 der Orgelbank auf SimpleDatabaseStorageObjekt umgestellt und dokumentiert.
 *
 */
class Register extends SimpleDatabaseStorageObjekt
{

    private $orgelID;

    private $manual;

    private $name;

    private $fuss;

    private $reihenfolge;

    // Transient, Häufigkeit des Registers
    private $anzahl;

    public function __construct($iID = 0, $primaryKey = "d_id", $tableName = "disposition", $tablePrefix = "d_")
    {
        parent::__construct($iID, $primaryKey, $tableName, $tablePrefix);
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add($this->tablePrefix . "name", $this->getName());
        $ht->add($this->tablePrefix . "fuss", $this->getFuss());
        $ht->add($this->tablePrefix . "reihenfolge", $this->getReihenfolge());
        $ht->add("o_id", $this->getOrgelID());
        $ht->add("m_id", $this->getManual());
        
        return $ht;
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setOrgelID($rs['o_id']);
        $this->setManual($rs['m_id']);
        $this->setName($rs[$this->tablePrefix . 'name']);
        $this->setFuss($rs[$this->tablePrefix . 'fuss']);
        $this->setReihenfolge($rs[$this->tablePrefix . 'reihenfolge']);
    }

    /**
     * Gibt die Registergröße in Fuß an
     *
     * @return String
     */
    public function getFuss()
    {
        return $this->fuss;
    }

    /**
     * ManualID des Registers
     *
     * @return int
     */
    public function getManual()
    {
        return $this->manual;
    }

    /**
     * Bezeichnung des Registers
     *
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * OrgelID des Registers
     *
     * @return int
     */
    public function getOrgelID()
    {
        return $this->orgelID;
    }

    /**
     * Reihenfolge im Manual
     *
     * @return int
     */
    public function getReihenfolge()
    {
        return $this->reihenfolge;
    }

    /**
     * Setzt die Fußgröße des Registers
     *
     * @param String $fuss            
     */
    public function setFuss($fuss)
    {
        if ($this->fuss != $fuss) {
            $this->fuss = $fuss;
            $this->setChanged(true);
        }
    }

    /**
     * Setzt die ID des Manuals der Orgel
     *
     * @param int $manual            
     */
    public function setManual($manual)
    {
        if ($this->manual != $manual) {
            $this->manual = $manual;
            $this->setChanged(true);
        }
    }

    /**
     * Setzt die Bezeichnung des Registers
     *
     * @param String $name            
     */
    public function setName($name)
    {
        if ($this->name != $name) {
            $this->name = $name;
            $this->setChanged(true);
        }
    }

    /**
     * Setzt die OrgelID des Registers
     *
     * @param int $orgelID            
     */
    public function setOrgelID($orgelID)
    {
        if ($this->orgelID != $orgelID) {
            $this->orgelID = $orgelID;
            $this->setChanged(true);
        }
    }

    /**
     * Setzt Reihenfolge des Registers im Manual
     *
     * @param int $reihenfolge            
     */
    public function setReihenfolge($reihenfolge)
    {
        if ($this->reihenfolge != $reihenfolge) {
            $this->reihenfolge = $reihenfolge;
            $this->setChanged(true);
        }
    }

    public function getAnzahl()
    {
        return $this->anzahl;
    }

    public function setAnzahl($anzahl)
    {
        $this->anzahl = $anzahl;
    }
}
?>
