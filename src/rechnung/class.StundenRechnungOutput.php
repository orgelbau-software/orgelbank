<?php

class StundenRechnungOutput extends PositionsRechnungsOutput
{

    public const UNTERORDNER_STUNDE = "stunde/";
    
    public function __construct($pfad, StundenRechnung $r)
    {
        parent::__construct($pfad, $r, StundenRechnungOutput::UNTERORDNER_STUNDE);
    }

    public function ersetzeRechnungsTags()
    {
        parent::ersetzeRechnungsTags();
        $this->setAzubiStundenLohn($this->getRechnung()->getAzubiLohn());
        $this->setAzubiStd($this->getRechnung()->getAzubiStd());
        $this->setAzubiLohn($this->getRechnung()->getAzubiStd() * $this->getRechnung()->getAzubiLohn());
        $this->setGeselleStundenLohn($this->getRechnung()->getGeselleLohn());
        $this->setGeselleStd($this->getRechnung()->getGeselleStd());
        $this->setGeselleLohn($this->getRechnung()->getGeselleStd() * $this->getRechnung()->getGeselleLohn());
        $this->setMaterial($this->getRechnung()->getMaterial());
    }

    public function setAzubiStundenLohn($s)
    {
        $this->template->replace("ALohn", $this->convertToEuro($s));
    }

    public function setAzubiStd($s)
    {
        $this->template->replace("AStd", $s);
    }

    public function setGeselleStundenLohn($s)
    {
        $this->template->replace("GLohn", $this->convertToEuro($s));
    }

    public function setGeselleStd($s)
    {
        $this->template->replace("GStd", $s);
    }

    public function setMaterial($s)
    {
        $this->template->replace("Material", $this->convertToEuro($s));
    }

    public function setGeselleLohn($s)
    {
        $this->template->replace("GGesamt", $this->convertToEuro($s));
    }

    public function setAzubiLohn($s)
    {
        $this->template->replace("AGesamt", $this->convertToEuro($s));
    }
}

