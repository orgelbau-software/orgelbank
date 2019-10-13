<?php

class PflegeRechnungOutput extends PositionsRechnungsOutput
{

    public function __construct($pfad, PflegeRechnung $r)
    {
        parent::__construct($pfad, $r);
    }

    public function ersetzeRechnungsTags()
    {
        parent::ersetzeRechnungsTags();
        $this->setPflegeKosten($this->rechnung->getPflegekosten());
    }

    public function setPflegeKosten($s)
    {
        $this->template->replace("PflegeKosten", $this->convertToEuro($s));
    }
}
?>
