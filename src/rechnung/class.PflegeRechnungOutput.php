<?php

class PflegeRechnungOutput extends PositionsRechnungsOutput
{

    public const UNTERORDNER_PFLEGE = "pflege/";
    /**
     * 
     * @param String $pfad
     * @param PflegeRechnung $r
     */
    public function __construct($pfad, PflegeRechnung $r)
    {
        parent::__construct($pfad, $r, PflegeRechnungOutput::UNTERORDNER_PFLEGE);
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
