<?php

class StundenRechnungTemplateBuilder extends PflegeRechnungTemplateBuilder
{

    public function __construct(Template $t, StundenRechnung $r)
    {
        parent::__construct($t, $r);
    }

    public function ersetzeRechnungsTags()
    {
        parent::ersetzeRechnungsTags();
        $this->setFahrtkosten($this->rechnung->getFahrtkosten());
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
        $this->template->replace("AStd", $this->convertToFloat($s));
    }

    public function setGeselleStundenLohn($s)
    {
        $this->template->replace("GLohn", $this->convertToEuro($s));
    }

    public function setGeselleStd($s)
    {
        $this->template->replace("GStd", $this->convertToFloat($s));
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

    public function convertToFloat($s)
    {
        return $s;
    }
}
?>
