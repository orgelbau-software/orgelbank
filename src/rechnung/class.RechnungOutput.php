<?php

abstract class RechnungOutput
{

    protected $template;

    protected $rechnung;

    protected $gemeinde;

    public function __construct($pfad, Rechnung $r)
    {
        // $this->template = new RTFOutput($pfad);
        // $this->template = new ODTOutput($pfad) ;
        $this->template = new MSWordOutput($pfad);
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
        $this->setMwSt($this->rechnung->getMwSt(false));
        $this->setBruttoBetrag($this->rechnung->getBruttoBetrag(false));
        
        $a = new Ansprechpartner(1);
        $this->setFirmensitz($a->getAdresse()
            ->getOrt());
        $this->setSteuerNr($a->getAndere());
    }

    public abstract function ersetzeRechnungsTags();

    public function speichern($zielpfad)
    {
        $zielpfad = Utilities::ersetzeZeichen($zielpfad);
        $verzeichnisse = array(
            RECHNUNGDIR,
            RECHNUNGDIR . date("Y"),
            RECHNUNGDIR . date("Y") . "/abschlag",
            RECHNUNGDIR . date("Y") . "/stunde",
            RECHNUNGDIR . date("Y") . "/end",
            RECHNUNGDIR . date("Y") . "/pflege"
        );
        foreach ($verzeichnisse as $dir) {
            if (! is_dir($dir)) {
                mkdir($dir);
            }
        }
        return $this->template->save($zielpfad);
    }

    public function convertToEuro($e)
    {
        $e = round($e, 2);
        return number_format($e, 2, ",", " ");
    }

    protected function format($s)
    {
        // $s = str_replace("\\'", "\\rquote", $s); // Zeichen fuer "Fuß" ersetzen. Prio 0!
        $s = stripslashes($s);
        return $s;
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
        $this->template->replace("RechNr", $s);
    }

    public function setDatum($s)
    {
        $this->template->replace("Datum", $s);
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

    public function setFirmensitz($s)
    {
        $this->template->replace("Firmensitz", $s);
    }

    public function setSteuerNr($s)
    {
        $this->template->replace("SteuerNr", $s);
    }

    public function setZahlungsziel($s)
    {
        $this->template->replace("Zahlungsziel", $s);
    }
}

?>
