<?php

class PflegeRechnung extends PositionsRechnung
{

    protected $pflegekosten;

    public static $TYPE_ID = 1;
    
    public function __construct($iRechnungsID = 0, $primaryKey = "rp_id", $tableName = "rechnung_pflege", $prefix = "rp_")
    {
        parent::__construct($iRechnungsID, $primaryKey, $tableName, $prefix);
    }

    protected function generateHashtable()
    {
        $ht = parent::generateHashtable();
        
        $ht->add($this->tablePrefix . "pflegekosten", $this->getPflegekosten());
        $this->isPersistent(true);
        
        return $ht;
    }

    protected function laden()
    {
        parent::laden();
        $rs = $this->result;
        $this->setPflegekosten($rs[$this->tablePrefix . 'pflegekosten']);
    }

    /**
     * Beinhaltet die Formel zur Berrechnung des Gesamtbetrags der Rechnung
     *
     * @param boolean $speichern            
     * @return double Rechnungsbetrag
     */
    public function errechneGesamtBetrag($speichern = false)
    {
        $retVal = $this->getPflegekosten() + $this->getFahrtkosten();
        if ($speichern) {
            $this->setNettoBetrag($retVal, true);
        }
        return $retVal;
    }

    public function getSpeicherOrt()
    {
        $oGemeinde = new Gemeinde($this->getGemeindeID());
        $rechNr = str_replace("/", "-", $this->getNummer());
        $kirche = str_replace("/", "-", $oGemeinde->getKirche());
        $ziel = parent::getSpeicherOrt() . "pflege/" . $kirche . "-" . $rechNr;
        $ziel = Utilities::ersetzeZeichen($ziel);
        return $ziel;
    }

    /**
     * Kosten der Pflege (nur für die Positionen) ohne Fahrtkosten
     *
     * @return double Kosten der Pflege
     */
    public function getPflegekosten($formatiert = false)
    {
        if ($formatiert)
            return Rechnung::toWaehrung($this->pflegekosten);
        return $this->pflegekosten;
    }

    /**
     * Setzt die Kosten der Pflege (der Positionen) ohne Fahrtkosten
     *
     * @param double $pflegekosten
     *            Summe der Pflegekosten
     */
    public function setPflegekosten($pflegekosten)
    {
        $this->pflegekosten = $pflegekosten;
    }
    
    public function getType() {
        return PflegeRechnung::$TYPE_ID;
    }
}
?>
