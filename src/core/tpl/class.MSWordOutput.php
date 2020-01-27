<?php

class MSWordOutput extends Output
{

    public static $FILE_EXTENSTION = '.docx';
    
    public function loadNewTemplate($pfadOrig)
    {
        $pfad = $pfadOrig . ".docx";
        $pfad = $this->aenderePfad($pfad);
        
        $PHPWord = new PHPWord();
        $this->template = $PHPWord->loadTemplate($pfad);
        $this->file = $pfad;
    }

    public function replace($platzhalter, $wert)
    {
        try {
            // echo ("Ersetze '" . $platzhalter . "' mit Wert '" . $wert . "'<br/>");
//             $this->template->setValue($platzhalter, $wert);
            $this->template->setValue($platzhalter, utf8_decode($wert));
        } catch (Exception $e) {
            echo " Variable nicht in Dokument gefunden: '" . $platzhalter . "'</br>";
        }
        // return parent::replace($platzhalter, $wert);
        return true;
    }

    public function save($file)
    {
        $this->template->save($file . MSWordOutput::$FILE_EXTENSTION);
        return $file . MSWordOutput::$FILE_EXTENSTION;
    }
}