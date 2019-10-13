<?php

class RegisterGroessenBean
{

    private $reihenfolge;

    private $bezeichnung;

    private $bezeichnungsText;

    public function __construct($reihenfolge, $bezeichnung)
    {
        $this->setBezeichnung($bezeichnung);
        $this->setBezeichnungsText($bezeichnung . "'");
        $this->setReihenfolge($reihenfolge);
    }

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
    public function getReihenfolge()
    {
        return $this->reihenfolge;
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
     * @param unknown_type $reihenfolge            
     */
    public function setReihenfolge($reihenfolge)
    {
        $this->reihenfolge = $reihenfolge;
    }

    /**
     *
     * @return unknown
     */
    public function getBezeichnungsText()
    {
        return $this->bezeichnungsText;
    }

    /**
     *
     * @param unknown_type $bezeichnungsText            
     */
    public function setBezeichnungsText($bezeichnungsText)
    {
        $this->bezeichnungsText = $bezeichnungsText;
    }

    public function __toString()
    {
        return "RegisteGroessenbean[Reihenfolge:" . $this->reihenfolge . ", Bezeichnung:" . $this->bezeichnung . ", BezeichnungsText:" . $this->bezeichnungsText . "]";
    }
}
?>