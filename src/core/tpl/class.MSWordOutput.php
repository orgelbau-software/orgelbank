<?php

class MSWordOutput extends Output
{

    public static $FILE_EXTENSTION = '.docx';
    
    public function loadNewTemplate($pfadOrig) : void
    {
        $pfad = $pfadOrig . ".docx";
        $pfad = $this->aenderePfad($pfad);
        
        $this->template =  new \PhpOffice\PhpWord\TemplateProcessor($pfad);
        PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $this->file = $pfad;
    }

    public function replace($platzhalter, $wert) : void
    {
        try {
            if($wert != "") {
                $this->template->setValue($platzhalter, mb_convert_encoding($this->handleSpecialChars($wert), 'ISO-8859-1', 'UTF-8'));
            }
        } catch (Exception $e) {
            echo " Variable nicht in Dokument gefunden: '" . $platzhalter . "'</br>";
        }
    }

    public function save($pPfad) : string
    {
        $this->template->saveAs($pPfad);
        return $pPfad;
    }

    /**
     * 
     */
    protected function handleSpecialChars(string $value) {
        if($value == null) {
            return $value;
        } else if($value == "") {
            return $value;
        } else {
            //return htmlspecialchars($value);
            return $value;
        }
    }
}