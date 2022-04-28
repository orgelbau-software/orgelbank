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
        $this->setAzubiStundenLohn($this->rechnung->getAzubiLohn());
        $this->setAzubiStd($this->rechnung->getAzubiStd());
        $this->setAzubiLohn($this->rechnung->getAzubiStd() * $this->rechnung->getAzubiLohn());
        $this->setGeselleStundenLohn($this->rechnung->getGeselleLohn());
        $this->setGeselleStd($this->rechnung->getGeselleStd());
        $this->setGeselleLohn($this->rechnung->getGeselleStd() * $this->rechnung->getGeselleLohn());
        $this->setMaterial($this->rechnung->getMaterial());
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
?>
