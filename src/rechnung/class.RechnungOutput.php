<?php

abstract class RechnungOutput
{

    protected $template;

    /**
     * @var Rechnung 
     */
    private $rechnung;

    protected $gemeinde;

    protected $unterordner;

    /**
     *
     * @param string $pTemplatePfad            
     * @param Rechnung $pRechnung            
     * @param string $unterordner            
     */
    public function __construct($pTemplatePfad, Rechnung $pRechnung, $pUnterordner)
    {
        $this->template = new MSWordOutput($pTemplatePfad);
        $this->rechnung = $pRechnung;
        $this->gemeinde = new Gemeinde($pRechnung->getGemeindeID());
        
        if (strpos($pUnterordner, "/") != strlen($pUnterordner) - 1) {
            $pUnterordner .= "/";
        }
        $this->unterordner = $pUnterordner;
    }

    public function erstellen()
    {
        $this->rechnung->errechneGesamtBetrag();
        
        $this->ersetzeRechnungsTags();
        
        $this->setKundenNr($this->gemeinde->getKundenNr());
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

    public function speichern()
    {
        $zielpfad = $this->getSpeicherOrt();

        // 2020-04-28: Suffix hinzufuegen, falls er nicht existiert.
        if (false === strpos($zielpfad, MSWordOutput::$FILE_EXTENSTION)) {
            $zielpfad .= MSWordOutput::$FILE_EXTENSTION;
        }
        $originalSpeicherPfad = $this->template->save($zielpfad);
        // return $originalSpeicherPfad;
        
        // 2022-04-28: Wir geben den relativen Pfad zurueck, weil der fuern den Download gebraucht wird.
        $relativerSpeicherPfad = str_replace(ROOTDIR, "", $originalSpeicherPfad);
        return $relativerSpeicherPfad;
    }

    protected function getSpeicherOrt()
    {
        $jahr = date("Y", strtotime($this->rechnung->getDatum(true)));
        $ordner = RECHNUNGDIR . $jahr . "/" . $this->unterordner;
        
        $rechNr = str_replace("/", "-", $this->rechnung->getNummer());
        $kirche = str_replace("/", "-", $this->gemeinde->getKirche());
        $dateiname = $kirche . "-" . $rechNr;
        $dateiname = Utilities::ersetzeZeichen($dateiname);
        
        if (! is_dir($ordner)) {
             mkdir($ordner, 0755, true);
        }
        return $ordner . $dateiname . "." . MSWordOutput::$FILE_EXTENSTION;
    }

    /**
     * @return string
     */
    public function convertToEuro($e)
    {
        $retVal = "";
        // PHP8
        if($e != null) {
            $value = round($e, 2);
            $retVal = number_format($value, 2, ",", " ");
        } else {
            $retVal = "";
        }
        return $retVal;
    }

    protected function format($s)
    {
        // $s = str_replace("\\'", "\\rquote", $s); // Zeichen fuer "Fuß" ersetzen. Prio 0!
        if($s == "") {
            $s = "";
        } else {
            $s = stripslashes($s);
        }
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

    public function setKundenNr($s)
    {
        $this->template->replace("Kdnr", $s);
    }

    /**
     * @return Rechnung
     */
    protected function getRechnung() {
        return $this->rechnung;
    }
}

