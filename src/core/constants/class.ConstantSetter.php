<?php

/**
 * Stellt wichtige Konstanten zur Laufzeit zur Verfuegung
 * 
 * @author swatermeyer
 * @version $Revision:  $
 *
 */
class ConstantSetter
{

    private $htValues;

    private static $dbInstance;

    /**
     * Standarkonstruktor
     * 
     * @access private
     */
    public function __construct()
    {
        $this->performAutoload();
    }

    /**
     * Laedt die Konstanten aus der Datenbank in die Klasse
     */
    public function performAutoload()
    {
        $ht = new HashTable();
        $oDSOC = OptionValueUtilities::getAutoloadOptions();
        
        foreach ($oDSOC as $o) {
            $ht->add($o->getName(), $o->getValue());
        }
        $this->htValues = $ht;
    }

    public function setStandardPflegerechnungPos1($value)
    {
        $this->saveConstante("pflegerechnung_pos_1", $value);
    }

    public function setStandardPflegerechnungPos2($value)
    {
        $this->saveConstante("pflegerechnung_pos_2", $value);
    }

    public function setStandardPflegerechnungPos3($value)
    {
        $this->saveConstante("pflegerechnung_pos_3", $value);
    }

    public function setStandardPflegerechnungPos4($value)
    {
        $this->saveConstante("pflegerechnung_pos_4", $value);
    }

    public function setStandardPflegerechnungPos5($value)
    {
        $this->saveConstante("pflegerechnung_pos_5", $value);
    }

    public function setStandardPflegerechnungPos6($value)
    {
        $this->saveConstante("pflegerechnung_pos_6", $value);
    }

    public function setStandardPflegerechnungPos7($value)
    {
        $this->saveConstante("pflegerechnung_pos_7", $value);
    }

    public function setStandardPflegerechnungPos8($value)
    {
        $this->saveConstante("pflegerechnung_pos_8", $value);
    }

    public function setStandardPflegerechnungPos9($value)
    {
        $this->saveConstante("pflegerechnung_pos_9", $value);
    }

    public function setStandardPflegerechnungPos10($value)
    {
        $this->saveConstante("pflegerechnung_pos_10", $value);
    }

    public function setStandardZahlungsziel($value)
    {
        $this->saveConstante("standardzahlungsziel", $value);
    }

    public function setRechnungPflegeText($text)
    {
        $this->saveConstante("rechnung_pflege_text", $text);
    }

    public function setRechnungAuftragText($text)
    {
        $this->saveConstante("rechnung_auftrag_text", $text);
    }

    public function setRechnungAngebotText($text)
    {
        $this->saveConstante("rechnung_angebot_text", $text);
    }

    public function setStandardZahlungsziele($text)
    {
        $this->saveConstante("rechnung_zahlungsziele", $text);
    }

    public function setAbschlag1Prozent($text)
    {
        $this->saveConstante("rechnung_abschlag1_prozent", $text);
    }

    public function setAbschlag2Prozent($text)
    {
        $this->saveConstante("rechnung_abschlag2_prozent", $text);
    }

    public function setAbschlag3Prozent($text)
    {
        $this->saveConstante("rechnung_abschlag3_prozent", $text);
    }

    public function setAbschlag1Text($text)
    {
        $this->saveConstante("rechnung_abschlag1_text", $text);
    }

    public function setAbschlag2Text($text)
    {
        $this->saveConstante("rechnung_abschlag2_text", $text);
    }

    public function setAbschlag3Text($text)
    {
        $this->saveConstante("rechnung_abschlag3_text", $text);
    }

    public function setPDFUntertext1($text)
    {
        $this->saveConstante("pdf_untertext1", $text);
    }

    public function setPDFUntertext2($text)
    {
        $this->saveConstante("pdf_untertext2", $text);
    }

    public function setPflegeRechnungsNummerNaechste($text)
    {
        $this->saveConstante("rechnung_pflege_naechste_nummer", $text);
    }

    public function setAbschlagRechnungsNummerNaechste($text)
    {
        $this->saveConstante("rechnung_abschlag_naechste_nummer", $text);
    }

    private function saveConstante($name, $value)
    {
        ConstantSetter::updateOption($name, $value);
        $this->htValues->setValueOf($name, $value);
        ConstantLoader::setConstantHashtable($this->htValues);
    }

    public static function updateOption($name, $value)
    {
        if (null == ConstantSetter::$dbInstance)
            ConstantSetter::$dbInstance = DB::getInstance();
        
        $sql = "UPDATE 
					option_meta 
				SET
					option_value = '" . $value . "'
				WHERE 
					option_name = '" . $name . "'";
        Log::sql($sql);
        ConstantSetter::$dbInstance->NonSelectQuery($sql);
    }
}
?>