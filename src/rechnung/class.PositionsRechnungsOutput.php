<?php

abstract class PositionsRechnungsOutput extends RechnungOutput
{

    public function __construct($pfad, PositionsRechnung $r)
    {
        parent::__construct($pfad, $r);
    }

    public function ersetzeRechnungsTags()
    {
        $col = RechnungsPositionUtilities::getRechnungsPositionen($this->rechnung->getID(), $this->rechnung->getType());
        
        $iPos = 1;
        foreach ($col as $currentPos) {
            $this->template->replace("Position" . $iPos ++ . "", $this->format($currentPos->getText()));
        }
        $this->setText1($this->rechnung->getText1());
        $this->setText2($this->rechnung->getText2());
        $this->setBetrag($this->rechnung->getNettoBetrag());
        $this->setFahrtkosten($this->rechnung->getFahrtkosten());
    }

    public function setText1($s)
    {
        $this->template->replace("Text1", $this->format($s));
    }

    public function setText2($s)
    {
        $this->template->replace("Text2", $this->format($s));
    }

    public function setPosition1($s)
    {
        $this->template->replace("Position1", $this->format($s));
    }

    public function setPosition2($s)
    {
        $this->template->replace("Position2", $this->format($s));
    }

    public function setPosition3($s)
    {
        $this->template->replace("Position3", $this->format($s));
    }

    public function setPosition4($s)
    {
        $this->template->replace("Position4", $this->format($s));
    }

    public function setPosition5($s)
    {
        $this->template->replace("Position5", $this->format($s));
    }

    public function setPosition6($s)
    {
        $this->template->replace("Position6", $this->format($s));
    }

    public function setPosition7($s)
    {
        $this->template->replace("Position7", $this->format($s));
    }

    public function setPosition8($s)
    {
        $this->template->replace("Position8", $this->format($s));
    }

    public function setPosition9($s)
    {
        $this->template->replace("Position9", $this->format($s));
    }

    public function setPosition10($s)
    {
        $this->template->replace("Position10", $this->format($s));
    }

    public function setBetrag($s)
    {
        $this->template->replace("NettoBetrag", $this->convertToEuro($s));
    }

    public function setFahrtkosten($s)
    {
        $this->template->replace("Fahrtkosten", $this->convertToEuro($s));
    }
}
?>
