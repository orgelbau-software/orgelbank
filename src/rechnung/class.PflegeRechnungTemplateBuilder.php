<?php

class PflegeRechnungTemplateBuilder extends RechnungTemplateBuilder
{

    public function __construct(Template $t, PositionsRechnung $r)
    {
        parent::__construct($t, $r);
    }

    public function ersetzeRechnungsTags()
    {
        $this->setFahrtkosten($this->rechnung->getFahrtkosten());
        
        $colPositionen = RechnungsPositionUtilities::getRechnungsPositionen($this->rechnung->getID(), $this->rechnung->getType());
        $iPosCount = 1;
        foreach ($colPositionen as $currentPos) {
            $this->template->replace("Standardposition" . $iPosCount ++, ($currentPos->getText() == "" ? "" : stripslashes($currentPos->getText())));
        }
        $this->setPosition1("");
        $this->setPosition2("");
        $this->setPosition3("");
        $this->setPosition4("");
        $this->setPosition5("");
        $this->setPosition6("");
        $this->setPosition7("");
        $this->setPosition8("");
        $this->setPosition9("");
        $this->setPosition10("");
        
        $this->setText1($this->rechnung->getText1());
        $this->setText2($this->rechnung->getText2());
        $this->setBetrag($this->rechnung->getNettoBetrag());
    }

    public function setText1($s)
    {
        $this->template->replace("Bemerkung1", $s);
    }

    public function setText2($s)
    {
        $this->template->replace("Bemerkung2", $s);
    }

    public function setPosition1($s)
    {
        $this->template->replace("Standardposition1", $s);
    }

    public function setPosition2($s)
    {
        $this->template->replace("Standardposition2", $s);
    }

    public function setPosition3($s)
    {
        $this->template->replace("Standardposition3", $s);
    }

    public function setPosition4($s)
    {
        $this->template->replace("Standardposition4", $s);
    }

    public function setPosition5($s)
    {
        $this->template->replace("Standardposition5", $s);
    }

    public function setPosition6($s)
    {
        $this->template->replace("Standardposition6", $s);
    }

    public function setPosition7($s)
    {
        $this->template->replace("Standardposition7", $s);
    }

    public function setPosition8($s)
    {
        $this->template->replace("Standardposition8", $s);
    }

    public function setPosition9($s)
    {
        $this->template->replace("Standardposition9", $s);
    }

    public function setPosition10($s)
    {
        $this->template->replace("Standardposition10", $s);
    }

    public function setBetrag($s)
    {
        $this->template->replace("Betrag", $this->convertToEuro($s));
    }
}
?>
