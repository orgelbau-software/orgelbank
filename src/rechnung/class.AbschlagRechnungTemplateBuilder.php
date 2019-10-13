<?php

class AbschlagRechnungTemplateBuilder extends RechnungTemplateBuilder
{

    public function __construct(Template $t, AbschlagsRechnung $r)
    {
        parent::__construct($t, $r);
    }

    public function ersetzeRechnungsTags()
    {
        // $this->rechnung = new AbschlagsRechnung();
        $this->setEinleitung($this->rechnung->getEinleitung());
        $this->setTitel($this->rechnung->getTitel());
        $this->setGesamtNetto($this->rechnung->getGesamtNetto(true));
        $this->setGesamtMwSt($this->rechnung->getGesamtMwSt(true));
        $this->setGesamtSumme($this->rechnung->getGesamtBrutto(true));
        $this->setAbschlagSatz($this->rechnung->getAbschlagSatz(true));
        $this->setAbschlagNetto($this->rechnung->getNettoBetrag(true));
        $this->setAbschlagMwSt($this->rechnung->getMwSt(true));
        $this->setAbschlagSumme($this->rechnung->getBruttoBetrag(true));
        
        $this->setAbschlags1Text($this->rechnung->getEinleitung());
        $this->setAbschlags2Text("");
        $this->setAbschlags3Text("");
        
        $this->setAbschlag1Prozent("");
        $this->setAbschlag2Prozent("");
        $this->setAbschlag3Prozent("");
    }

    public function setEinleitung($s)
    {
        $this->template->replace("Einleitung", $s);
    }

    public function setTitel($s)
    {
        $this->template->replace("Titel", $s);
    }

    public function setGesamtNetto($s)
    {
        $this->template->replace("GesamtNetto", $s);
    }

    public function setGesamtMwSt($s)
    {
        $this->template->replace("GesamtMwSt", $this->convertToEuro($s));
    }

    public function setGesamtSumme($s)
    {
        $this->template->replace("GesamtSumme", $this->convertToEuro($s));
    }

    public function setAbschlagSatz($s)
    {
        $this->template->replace("AbschlagSatz", $this->convertToEuro($s));
    }

    public function setAbschlagNetto($s)
    {
        $this->template->replace("AbschlagNetto", $s);
    }

    public function setAbschlagMwSt($s)
    {
        $this->template->replace("AbschlagMwSt", $this->convertToEuro($s));
    }

    public function setAbschlagSumme($s)
    {
        $this->template->replace("AbschlagSumme", $this->convertToEuro($s));
    }

    public function setAbschlags1Text($s)
    {
        $this->template->replace("Abschlag1Text", $s);
    }

    public function setAbschlags2Text($s)
    {
        $this->template->replace("Abschlag2Text", $s);
    }

    public function setAbschlags3Text($s)
    {
        $this->template->replace("Abschlag3Text", $s);
    }

    public function setAbschlag1Prozent($s)
    {
        $this->template->replace("Abschlag1Prozent", $s);
    }

    public function setAbschlag2Prozent($s)
    {
        $this->template->replace("Abschlag2Prozent", $s);
    }

    public function setAbschlag3Prozent($s)
    {
        $this->template->replace("Abschlag3Prozent", $s);
    }
}
?>
