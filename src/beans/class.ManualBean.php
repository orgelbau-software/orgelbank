<?php

class ManualBean
{

    private $manualID;

    private $bezeichnung;

    /**
     *
     * @return unknown
     */
    public function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    /**
     *
     * @return unknown
     */
    public function getManualID()
    {
        return $this->manualID;
    }

    /**
     *
     * @param unknown_type $bezeichnung            
     */
    public function setBezeichnung($bezeichnung)
    {
        $this->bezeichnung = $bezeichnung;
    }

    /**
     *
     * @param unknown_type $manualID            
     */
    public function setManualID($manualID)
    {
        $this->manualID = $manualID;
    }

    public function __toString()
    {
        return "ManualBean[ManualID:" . $this->manualID . ", Bezeichnung:" . $this->bezeichnung . "]";
    }
}
?>