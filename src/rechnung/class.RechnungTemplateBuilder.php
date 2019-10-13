<?php

abstract class RechnungTemplateBuilder
{

    /**
     * Rechnungstemplate
     *
     * @var Output
     */
    protected $template;

    /**
     * BasisRechnung
     *
     * @var Rechnung
     */
    protected $rechnung;

    /**
     * ZielGemeinde
     *
     * @var Gemeinde
     */
    protected $gemeinde;

    public function __construct(Template $t, Rechnung $r)
    {
        $this->template = $t;
        $this->rechnung = $r;
        $this->gemeinde = new Gemeinde($r->getGemeindeID());
    }

    public function erstellen()
    {
        $this->rechnung->errechneGesamtBetrag();
        
        $this->ersetzeRechnungsTags();
        
        $this->setGemeindeNamen($this->gemeinde->getRGemeinde());
        $this->setGemeinde($this->gemeinde->getRAnschrift());
        $this->setStrasse($this->gemeinde->getRechnungAdresse()
            ->getStrasse());
        $this->setHausnummer($this->gemeinde->getRechnungAdresse()
            ->getHausnummer());
        $this->setPLZ($this->gemeinde->getRechnungAdresse()
            ->getPLZ());
        $this->setOrt($this->gemeinde->getRechnungAdresse()
            ->getOrt());
        $this->setRechNr($this->rechnung->getNummer());
        $this->setDatum($this->rechnung->getDatum(true));
        $this->setZahlungsziel($this->rechnung->getZieldatum(true));
        $this->setNettoBetrag($this->rechnung->getNettoBetrag(false));
        $this->setBruttoBetrag($this->rechnung->getBruttoBetrag(false));
        $this->setMwSt($this->rechnung->getMwSt());
    }

    public function getOutput()
    {
        return $this->template->getOutput();
    }

    /**
     * Gibt das zugrunde liegende Template zurück
     *
     * @return Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    public function anzeigen()
    {
        echo $this->template->forceOutput();
    }

    public abstract function ersetzeRechnungsTags();

    protected function convertToEuro($e)
    {
        $e = round($e, 2);
        return number_format($e, 2, ",", ".");
    }

    public function setGemeinde($s)
    {
        $this->template->replace("Gemeinde", $s);
    }

    public function setGemeindeNamen($s)
    {
        $this->template->replace("Gemeindenamen", $s);
    }

    public function setStrasse($s)
    {
        $this->template->replace("Strasse", $s);
    }

    public function setHausnummer($s)
    {
        $this->template->replace("Hsnr", $s);
    }

    public function setPLZ($s)
    {
        $this->template->replace("PLZ", $s);
    }

    public function setOrt($s)
    {
        $this->template->replace("Ort", $s);
    }

    public function setRechNr($s)
    {
        $this->template->replace("Rechnungsnummer", $s);
    }

    public function setDatum($s)
    {
        $this->template->replace("Rechnungsdatum", $s);
    }

    public function setFahrtkosten($s)
    {
        $this->template->replace("Fahrtkosten", $this->convertToEuro($s));
    }

    public function setNettoBetrag($s)
    {
        $this->template->replace("NettoBetrag", $this->convertToEuro($s));
    }

    public function setMwSt($s)
    {
        $this->template->replace("MwSt", $this->convertToEuro($s));
    }

    public function setBruttoBetrag($s)
    {
        $this->template->replace("BruttoBetrag", $this->convertToEuro($s));
    }

    public function setZahlungsziel($s)
    {
        $this->template->replace("Zahlungsziel", $s);
    }
}

?>
