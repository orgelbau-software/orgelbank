<?php

class AbschlagRechnungOutput extends RechnungOutput
{

    public const UNTERORDNER_ABSCHLAG = "abschlag/";
    
    public function __construct($pfad, AbschlagsRechnung $r)
    {
        parent::__construct($pfad, $r, AbschlagRechnungOutput::UNTERORDNER_ABSCHLAG);
    }

    public function ersetzeRechnungsTags()
    {
        $this->setANr($this->getRechnung()->getANr());
        $this->setTitel($this->getRechnung()->getTitel());
        $this->setEinleitung($this->getRechnung()->getEinleitung());
        $this->setGesamtNetto($this->getRechnung()->getGesamtNetto());
        $this->setGesamtMwSt($this->getRechnung()->getGesamtMwSt());
        $this->setGesamtBrutto($this->getRechnung()->getGesamtBrutto());
        $this->setAbschlagNetto($this->getRechnung()->getNettoBetrag());
        $this->setAbschlagMwSt($this->getRechnung()->getMwSt());
        $this->setAbschlagBrutto($this->getRechnung()->getBruttoBetrag());
    }

    public function setANr($s)
    {
        $this->template->replace("ANr", $s);
    }

    public function setTitel($s)
    {
        $this->template->replace("Titel", $s);
    }

    public function setEinleitung($s)
    {
        $this->template->replace("Text1", $s);
    }

    public function setGesamtNetto($s)
    {
        $this->template->replace("GKostenNetto", $this->convertToEuro($s));
    }

    public function setGesamtMwSt($s)
    {
        $this->template->replace("GMwSt", $this->convertToEuro($s));
    }

    public function setGesamtBrutto($s)
    {
        $this->template->replace("GKostenBrutto", $this->convertToEuro($s));
    }

    public function setAbschlagNetto($s)
    {
        $this->template->replace("AKostenNetto", $this->convertToEuro($s));
    }

    public function setAbschlagMwSt($s)
    {
        $this->template->replace("AMwSt", $this->convertToEuro($s));
    }

    public function setAbschlagBrutto($s)
    {
        $this->template->replace("AKostenBrutto", $this->convertToEuro($s));
    }

    /**
     * @return AbschlagsRechnung
     */
    public function getRechnung() {
        return parent::getRechnung();
    }
}
?>
