<?php

class EndRechnungOutput extends RechnungOutput
{

    /**
     *
     * @var DatabaseStorageObjektCollection
     */
    private $abschlagsRechnungen;

    private $tplAbschlagsContent;

    private $abschlagContent;
    
    public const UNTERORDNER_ENDRECHNUNG = "end/";

    public function __construct($pfad, EndRechnung $r)
    {
        parent::__construct($pfad, $r, EndRechnungOutput::UNTERORDNER_ENDRECHNUNG);
        // $this->tplAbschlagsContent = new Output("resources/vorlagen/rechnung_end_abschlag.rtf");
        $this->loadAbschlagsRechnungen();
    }

    private function loadAbschlagsRechnungen()
    {
        if ($this->getRechnung() != null) {
            // START IF -> MSWORD
            $this->abschlagsRechnungen = AbschlagrechnungUtilities::getAbschlagsRechnungenFuerEndRechnung($this->getRechnung()->getID());
            Log::debug("gefunden abschlagsrechnungen, count=" . count($this->abschlagsRechnungen));
            
            $iCounter = 1;
            foreach ($this->abschlagsRechnungen as $current) {
                $this->template->replace("AKostenNetto" . $iCounter, $current->getNettoBetrag(true));
                $this->template->replace("AKostenBrutto" . $iCounter, $current->getBruttoBetrag(true));
                $this->template->replace("ADatum" . $iCounter, $current->getDatum(true));
                $this->template->replace("ASteuer" . $iCounter, $current->getMwSt(true));
                // $this->tplAbschlagsContent->replace("AKostenNetto" . $iCounter, $current->getNettoBetrag(true));
                // $this->tplAbschlagsContent->replace("AKostenBrutto" . $iCounter, $current->getBruttoBetrag(true));
                // $this->tplAbschlagsContent->replace("ADatum" . $iCounter, $current->getDatum(true));
                // $this->tplAbschlagsContent->replace("ASteuer" . $iCounter, $current->getMwSt(true));
                $iCounter ++;
            }
            // END IF -> MSWORD
        }
    }

    public function ersetzeRechnungsTags()
    {
        $this->setTitel($this->getRechnung()->getTitel());
        $this->setText($this->getRechnung()->getText());
        $this->setGesamtNetto($this->getRechnung()->getGesamtNetto());
        $this->setGesamtSteuer($this->getRechnung()->getGesamtMwSt());
        $this->setGesamtBrutto($this->getRechnung()->getGesamtBrutto());
        
        $this->setRestNetto($this->getRechnung()->getNettoBetrag());
        $this->setRestSteuer($this->getRechnung()->getMwSt());
        $this->setRestBrutto($this->getRechnung()->getBruttoBetrag());
        
        $this->setAbschlagsRechnungenContent($this->abschlagContent);
    }

    public function setTitel($s)
    {
        $this->template->replace("Titel", $s);
    }

    public function setText($s)
    {
        $this->template->replace("Text1", $s);
    }

    public function setGesamtNetto($s)
    {
        $this->template->replace("GKostenNetto", $this->convertToEuro($s));
    }

    public function setGesamtSteuer($s)
    {
        $this->template->replace("GSteuer", $this->convertToEuro($s));
    }

    public function setGesamtBrutto($s)
    {
        $this->template->replace("GKostenBrutto", $this->convertToEuro($s));
    }

    public function setAbschlagsRechnungenContent($s)
    {
        $this->template->replace("AbschlagsRechnungContent", $s);
    }

    public function setRestNetto($s)
    {
        $this->template->replace("Restbetrag", $this->convertToEuro($s));
    }

    public function setRestSteuer($s)
    {
        $this->template->replace("RestSteuer", $this->convertToEuro($s));
    }

    public function setRestBrutto($s)
    {
        $this->template->replace("RestBetragBrutto", $this->convertToEuro($s));
    }

     /**
     * @return EndRechnung
     */
    protected function getRechnung() {
        return parent::getRechnung();
    }
}
