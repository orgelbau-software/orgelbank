<?php

class EndRechnungTemplateBuilder extends RechnungTemplateBuilder
{

    public function __construct(Template $t, EndRechnung $r)
    {
        parent::__construct($t, $r);
    }

    public function ersetzeRechnungsTags()
    {
        // $this->rechnung = new EndRechnung();
        $this->setEinleitung($this->rechnung->getEingangsAnmerkung());
        $this->setTitel($this->rechnung->getTitel());
        $this->setGesamtNetto($this->rechnung->getGesamtNetto(false));
        $this->setGesamtMwSt($this->rechnung->getGesamtMwSt(false));
        $this->setGesamtSumme($this->rechnung->getGesamtBrutto(false));
        $this->setAbschlaegeBisher(0);
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
        $this->template->replace("GesamtNetto", $this->convertToEuro($s));
    }

    public function setGesamtMwSt($s)
    {
        $this->template->replace("GesamtMwSt", $this->convertToEuro($s));
    }

    public function setGesamtSumme($s)
    {
        $this->template->replace("GesamtBrutto", $this->convertToEuro($s));
    }

    public function setAbschlaegeBisher($s)
    {
        $this->template->replace("AbschlaegeBisher", $this->convertToEuro($s));
    }
}
?>
