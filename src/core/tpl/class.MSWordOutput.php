<?php
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class MSWordOutput extends Output
{

    public static $FILE_EXTENSTION = '.docx';
    
    public function loadNewTemplate($pfadOrig) : void
    {
        $pfad = $pfadOrig . ".docx";
        $pfad = $this->aenderePfad($pfad);

        if(!file_exists($pfad)) {
            throw new Exception("Template does not exist at: ".$pfad);
        }
        
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
    


        // Make sure you have `dompdf/dompdf` in your composer dependencies.
        //Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
        // Any writable directory here. It will be ignored.
        //Settings::setPdfRendererPath('.');



        $phpWord = IOFactory::load($pPfad, 'Word2007');
        $htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($phpWord);
        $htmlWriter->save($pPfad.".html");

        //$phpWord->save($pPfad.".pdf", 'PDF');

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