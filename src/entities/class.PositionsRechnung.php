<?php

abstract class PositionsRechnung extends Rechnung
{

    /**
     * @var string
     */
    protected $text1;

    /**
     * @var string
     */
    protected $text2;

    /**
     * 
     * @var double
     */
    protected $fahrtkosten;

    /**
     * 
     * @param int $iRechnungsID 
     * @param int $primaryKey 
     * @param string $tableName 
     * @param string $prefix 
     * @return void 
     */
    public function __construct($iRechnungsID, $primaryKey, $tableName, $prefix) 
    {
        parent::__construct($iRechnungsID, $primaryKey, $tableName, $prefix);
    }

    protected function generateHashtable()
    {
        $ht = parent::generateHashtable();
        
        $ht->add($this->tablePrefix . "fahrtkosten", $this->getFahrtkosten());
        $ht->add($this->tablePrefix . "text1", $this->getText1());
        $ht->add($this->tablePrefix . "text2", $this->getText2());
        $this->setPersistent(true);
        
        return $ht;
    }

    protected function laden()
    {
        parent::laden();
        $rs = $this->result;
        $this->setFahrtkosten($rs[$this->tablePrefix . "fahrtkosten"]);
        $this->setText1($rs[$this->tablePrefix . 'text1']);
        $this->setText2($rs[$this->tablePrefix . 'text2']);
    }
    
    /**
     * @return string
     */
    public function getText1()
    {
        return $this->text1;
    }

    /**
     * @return string
     */
    public function getText2()
    {
        return $this->text2;
    }

    /**
     * 
     * @param string $text1 
     * @return void 
     */
    public function setText1($text1)
    {
        if ($this->text1 != $text1) {
            $this->text1 = $text1;
            $this->setChanged(true);
        }
    }

    /**
     * 
     * @param string $text2 
     * @return void 
     */
    public function setText2($text2)
    {
        if ($this->text2 != $text2) {
            $this->text2 = $text2;
            $this->setChanged(true);
        }
    }

    public function getFahrtkosten($formatiert = false)
    {
        if ($formatiert)
            return Rechnung::toWaehrung($this->fahrtkosten);
        return $this->fahrtkosten;
    }

    /**
     * 
     * @param float $fahrtkosten 
     * @return void 
     */
    public function setFahrtkosten($fahrtkosten)
    {
        $this->fahrtkosten = $fahrtkosten;
    }
    
}
?>
