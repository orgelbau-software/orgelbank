<?php

class RTFOutput extends Output
{

    public function loadNewTemplate($pfadOrig)
    {
        $pfad = $pfadOrig . ".rtf";
        parent::loadNewTemplate($pfad);
    }

    public function replace($platzhalter, $wert)
    {
        return parent::replace("<" . $platzhalter . ">", $wert);
    }

    public function save($file)
    {
        $zielpfad = $file . ".rtf";
        $fp = fopen($zielpfad, 'w');
        if (! $fp) {
            throw new Exception("Pfad nicht schreibbar");
        }
        
        fputs($fp, $this->getOutput());
        fclose($fp);
    }
}