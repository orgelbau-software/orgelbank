<?php

class MSWordOutput extends Output
{

    public static $FILE_EXTENSTION = '.docx';
    
    public function loadNewTemplate($pfadOrig)
    {
        $pfad = $pfadOrig . ".docx";
        $pfad = $this->aenderePfad($pfad);
        
        $this->template =  new \PhpOffice\PhpWord\TemplateProcessor($pfad);
        $this->file = $pfad;
    }

    public function replace($platzhalter, $wert)
    {
        try {
            $this->template->setValue($platzhalter, utf8_decode($wert));
        } catch (Exception $e) {
            echo " Variable nicht in Dokument gefunden: '" . $platzhalter . "'</br>";
        }
        return true;
    }

    public function save($pPfad)
    {
        $this->template->saveAs($pPfad);
        return $pPfad;
    }
}