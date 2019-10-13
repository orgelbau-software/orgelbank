<?php

class AbschlagRechnungOutput extends RechnungOutput
{

    public function __construct($pfad, AbschlagsRechnung $r)
    {
        parent::__construct($pfad, $r);
    }

    public function ersetzeRechnungsTags()
    {
        $this->setANr($this->rechnung->getANr());
        $this->setTitel($this->rechnung->getTitel());
        $this->setEinleitung($this->rechnung->getEinleitung());
        $this->setGesamtNetto($this->rechnung->getGesamtNetto());
        $this->setGesamtMwSt($this->rechnung->getGesamtMwSt());
        $this->setGesamtBrutto($this->rechnung->getGesamtBrutto());
        $this->setAbschlagNetto($this->rechnung->getNettoBetrag());
        $this->setAbschlagMwSt($this->rechnung->getMwSt());
        $this->setAbschlagBrutto($this->rechnung->getBruttoBetrag());
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
}
?>
